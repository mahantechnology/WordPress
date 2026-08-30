<?php
/**
 * License client for قالب ماهان.
 *
 * Holds the licence state, talks to the Mahan Technology validation API, and
 * answers the one question the rest of the theme asks: is this copy licensed?
 *
 * Two rules shape the design.
 *
 * A licence is revoked only when the server says so. A timeout, a 502 or a
 * body that will not parse says nothing about the customer's licence, so an
 * unreachable server leaves the last known answer standing for a grace period
 * rather than locking a live site over someone else's outage.
 *
 * The stored answer is bound to this site. The saved row carries a hash of the
 * key, the product and the host it was activated on, so lifting the row into
 * another database does not carry the licence with it.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

final class Mahan_License {

	/**
	 * Option holding the licence state.
	 */
	const OPTION = 'mahan_license';

	/**
	 * Validation endpoint.
	 */
	const ENDPOINT = 'https://mahantechnology.com/License/manager-code-license/api/validate.php';

	/**
	 * Product name sent with every request. Must match the product registered
	 * in the licence manager; override with the MAHAN_LICENSE_PRODUCT constant
	 * or the `mahan_license_product` filter.
	 */
	const PRODUCT = 'Mahan Theme';

	/**
	 * Daily re-validation hook.
	 */
	const CRON_HOOK = 'mahan_license_check';

	/**
	 * Days a licence keeps working while the server cannot be reached.
	 */
	const GRACE_DAYS = 7;

	/**
	 * Shape of a licence key: MT- followed by five four-character blocks.
	 */
	const KEY_PATTERN = '/^MT(-[A-Z0-9]{4}){5}$/';

	/**
	 * Transient counting activation attempts, and the hourly ceiling.
	 */
	const THROTTLE_KEY = 'mahan_license_attempts';
	const THROTTLE_MAX = 10;

	/**
	 * Shared instance.
	 *
	 * @var Mahan_License|null
	 */
	private static $instance = null;

	/**
	 * Memoised state so one request reads the option once.
	 *
	 * @var array|null
	 */
	private $state = null;

	/**
	 * Returns the shared instance.
	 *
	 * @return Mahan_License
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Hooks the cron re-check and the AJAX actions.
	 */
	public function __construct() {
		self::$instance = $this;

		add_action( self::CRON_HOOK, array( $this, 'scheduled_check' ) );
		add_action( 'after_switch_theme', array( $this, 'schedule' ) );
		add_action( 'switch_theme', array( $this, 'unschedule' ) );
		add_action( 'admin_init', array( $this, 'ensure_schedule' ) );

		add_action( 'wp_ajax_mahan_license_activate', array( $this, 'ajax_activate' ) );
		add_action( 'wp_ajax_mahan_license_refresh', array( $this, 'ajax_refresh' ) );
		add_action( 'wp_ajax_mahan_license_remove', array( $this, 'ajax_remove' ) );
	}

	/* --------------------------------------------------------------------
	 * The gate
	 * ----------------------------------------------------------------- */

	/**
	 * Whether the protected features may run.
	 *
	 * @return bool
	 */
	public function is_active() {
		$state = $this->state();

		if ( '' === $state['key'] || empty( $state['valid'] ) ) {
			return false;
		}

		// A row copied from another site, or a site that changed host.
		if ( ! hash_equals( $state['fingerprint'], $this->fingerprint( $state['key'] ) ) ) {
			return false;
		}

		if ( $state['expiry'] && $this->is_past( $state['expiry'] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * A short machine-readable reason for the current state.
	 *
	 * @return string One of: active, empty, expired, unbound, invalid, unreachable.
	 */
	public function condition() {
		$state = $this->state();

		if ( '' === $state['key'] ) {
			return 'empty';
		}

		if ( ! hash_equals( $state['fingerprint'], $this->fingerprint( $state['key'] ) ) ) {
			return 'unbound';
		}

		if ( $state['expiry'] && $this->is_past( $state['expiry'] ) ) {
			return 'expired';
		}

		if ( empty( $state['valid'] ) ) {
			return 'unreachable' === $state['status'] ? 'unreachable' : 'invalid';
		}

		return 'active';
	}

	/* --------------------------------------------------------------------
	 * State
	 * ----------------------------------------------------------------- */

	/**
	 * The stored licence state, with every key present.
	 *
	 * @return array
	 */
	public function state() {
		if ( null === $this->state ) {
			$this->state = wp_parse_args(
				(array) get_option( self::OPTION, array() ),
				array(
					'key'          => '',
					'valid'        => false,
					'status'       => '',
					'expiry'       => '',
					'message'      => '',
					'fingerprint'  => '',
					'checked_at'   => 0,
					'tried_at'     => 0,
					'activated_at' => 0,
				)
			);
		}

		return $this->state;
	}

	/**
	 * Writes the state back, keeping the memoised copy in step.
	 *
	 * @param array $changes Keys to overwrite.
	 */
	private function save( array $changes ) {
		$this->state = array_merge( $this->state(), $changes );

		update_option( self::OPTION, $this->state, false );
	}

	/**
	 * The licence key, or an empty string.
	 *
	 * @return string
	 */
	public function key() {
		$state = $this->state();

		return $state['key'];
	}

	/**
	 * The key with its middle blocks hidden, for display.
	 *
	 * @return string
	 */
	public function masked_key() {
		$key = $this->key();

		if ( '' === $key ) {
			return '';
		}

		$blocks = explode( '-', $key );
		$last   = count( $blocks ) - 1;

		foreach ( $blocks as $index => $block ) {
			if ( $index > 0 && $index < $last ) {
				$blocks[ $index ] = str_repeat( '•', strlen( $block ) );
			}
		}

		return implode( '-', $blocks );
	}

	/**
	 * Expiry date as stored, or an empty string when the licence never expires.
	 *
	 * @return string
	 */
	public function expiry() {
		$state = $this->state();

		return $state['expiry'];
	}

	/**
	 * Whole days left before the licence expires.
	 *
	 * @return int|null Null when there is no expiry date.
	 */
	public function days_left() {
		$expiry = $this->expiry();

		if ( ! $expiry ) {
			return null;
		}

		$end = $this->end_of_day( $expiry );

		if ( null === $end ) {
			return null;
		}

		return (int) floor( ( $end - time() ) / DAY_IN_SECONDS );
	}

	/**
	 * Whether the licence is inside the window where expiry is worth warning about.
	 *
	 * @return bool
	 */
	public function expires_soon() {
		$days = $this->days_left();

		return null !== $days && $days >= 0 && $days <= 30;
	}

	/**
	 * Timestamp of the last successful conversation with the server.
	 *
	 * @return int
	 */
	public function checked_at() {
		$state = $this->state();

		return (int) $state['checked_at'];
	}

	/**
	 * The last message the server or the transport produced.
	 *
	 * @return string
	 */
	public function message() {
		$state = $this->state();

		return $state['message'];
	}

	/* --------------------------------------------------------------------
	 * Activation
	 * ----------------------------------------------------------------- */

	/**
	 * Validates a key and, when the server accepts it, stores it.
	 *
	 * @param string $key Licence key as typed.
	 * @return array{ok:bool,message:string,condition:string}
	 */
	public function activate( $key ) {
		$key = self::normalise_key( $key );

		if ( ! self::looks_like_key( $key ) ) {
			return $this->result( false, __( 'قالب لایسنس درست نیست. کلید باید به شکل MT-XXXX-XXXX-XXXX-XXXX-XXXX باشد.', 'mahan' ) );
		}

		if ( ! $this->take_attempt() ) {
			return $this->result( false, __( 'تعداد تلاش‌ها زیاد بوده است. یک ساعت دیگر دوباره امتحان کنید.', 'mahan' ) );
		}

		$answer = $this->remote_check( $key );

		$this->save( array( 'tried_at' => time() ) );

		if ( ! $answer['ok'] ) {
			return $this->result( false, $answer['message'] );
		}

		if ( ! $answer['valid'] ) {
			// The server knows this key but will not vouch for it; say which.
			$reasons = array(
				'expired'   => __( 'اعتبار این لایسنس به پایان رسیده است. برای فعال‌سازی، آن را تمدید کنید.', 'mahan' ),
				'suspended' => __( 'این لایسنس معلق شده است. با پشتیبانی ماهان تکنولوژی تماس بگیرید.', 'mahan' ),
				'disabled'  => __( 'این لایسنس غیرفعال شده است. با پشتیبانی ماهان تکنولوژی تماس بگیرید.', 'mahan' ),
			);

			if ( isset( $reasons[ $answer['status'] ] ) ) {
				return $this->result( false, $reasons[ $answer['status'] ] );
			}

			return $this->result( false, $answer['message'] ? $answer['message'] : __( 'این لایسنس معتبر نیست یا برای محصول دیگری صادر شده است.', 'mahan' ) );
		}

		$this->save(
			array(
				'key'          => $key,
				'valid'        => true,
				'status'       => $answer['status'],
				'expiry'       => $answer['expiry'],
				'message'      => '',
				'fingerprint'  => $this->fingerprint( $key ),
				'checked_at'   => time(),
				'activated_at' => time(),
			)
		);

		$this->clear_attempts();
		$this->schedule();

		/**
		 * Fires once a licence has been accepted and stored.
		 *
		 * @param string $key    The licence key.
		 * @param array  $answer The server's answer.
		 */
		do_action( 'mahan_license_activated', $key, $answer );

		return $this->result( true, __( 'لایسنس شما تأیید شد و قالب ماهان فعال است.', 'mahan' ) );
	}

	/**
	 * Re-validates the stored key.
	 *
	 * @return array{ok:bool,message:string,condition:string}
	 */
	public function refresh() {
		$key = $this->key();

		if ( '' === $key ) {
			return $this->result( false, __( 'هنوز لایسنسی ثبت نشده است.', 'mahan' ) );
		}

		if ( ! $this->take_attempt() ) {
			return $this->result( false, __( 'تعداد تلاش‌ها زیاد بوده است. کمی بعد دوباره امتحان کنید.', 'mahan' ) );
		}

		$answer = $this->remote_check( $key );

		$this->apply( $key, $answer );

		if ( ! $answer['ok'] ) {
			return $this->result( false, $answer['message'] );
		}

		return $this->result(
			! empty( $answer['valid'] ),
			! empty( $answer['valid'] )
				? __( 'لایسنس بررسی شد و همچنان معتبر است.', 'mahan' )
				: ( $answer['message'] ? $answer['message'] : __( 'سرور این لایسنس را معتبر نمی‌داند.', 'mahan' ) )
		);
	}

	/**
	 * Forgets the stored licence and locks the protected features again.
	 */
	public function remove() {
		delete_option( self::OPTION );

		$this->state = null;

		$this->unschedule();

		do_action( 'mahan_license_removed' );
	}

	/**
	 * The daily cron callback.
	 */
	public function scheduled_check() {
		$key = $this->key();

		if ( '' === $key ) {
			return;
		}

		$this->apply( $key, $this->remote_check( $key ) );
	}

	/**
	 * Folds a server answer into the stored state.
	 *
	 * A definite "no" from the server revokes at once. Anything else — a
	 * timeout, an HTTP error, an unparseable body, an API-level error that
	 * says nothing about this key — leaves the last good answer standing
	 * until the grace period runs out.
	 *
	 * @param string $key    Key that was checked.
	 * @param array  $answer Result of remote_check().
	 */
	private function apply( $key, array $answer ) {
		$now = time();

		if ( $answer['ok'] ) {
			$this->save(
				array(
					'key'         => $key,
					'valid'       => (bool) $answer['valid'],
					'status'      => $answer['status'],
					'expiry'      => $answer['expiry'],
					'message'     => $answer['valid'] ? '' : $answer['message'],
					'fingerprint' => $this->fingerprint( $key ),
					'checked_at'  => $now,
					'tried_at'    => $now,
				)
			);

			return;
		}

		$state   = $this->state();
		$changes = array(
			'tried_at' => $now,
			'message'  => $answer['message'],
		);

		// Out of grace: stop vouching for a licence nobody has confirmed in a week.
		$since = $state['checked_at'] ? $now - (int) $state['checked_at'] : PHP_INT_MAX;

		if ( $since > self::GRACE_DAYS * DAY_IN_SECONDS ) {
			$changes['valid']  = false;
			$changes['status'] = 'unreachable';
		}

		$this->save( $changes );
	}

	/* --------------------------------------------------------------------
	 * Transport
	 * ----------------------------------------------------------------- */

	/**
	 * Asks the licence server about one key.
	 *
	 * @param string $key Licence key.
	 * @return array{ok:bool,valid:bool,status:string,expiry:string,message:string}
	 */
	private function remote_check( $key ) {
		$fail = static function ( $message ) {
			return array(
				'ok'      => false,
				'valid'   => false,
				'status'  => '',
				'expiry'  => '',
				'message' => $message,
			);
		};

		$response = wp_remote_post(
			self::endpoint(),
			array(
				'timeout'    => 20,
				'redirection' => 3,
				'sslverify'  => true,
				'headers'    => array( 'Accept' => 'application/json' ),
				'body'       => array(
					'license_key' => $key,
					'product'     => self::product(),
				),
				'user-agent' => 'Mahan/' . MAHAN_VERSION . '; ' . home_url( '/' ),
			)
		);

		if ( is_wp_error( $response ) ) {
			return $fail(
				sprintf(
					/* translators: %s: transport error message. */
					__( 'ارتباط با سرور لایسنس برقرار نشد: %s', 'mahan' ),
					$response->get_error_message()
				)
			);
		}

		$code = (int) wp_remote_retrieve_response_code( $response );
		$body = trim( (string) wp_remote_retrieve_body( $response ) );

		if ( 200 !== $code ) {
			return $fail(
				sprintf(
					/* translators: %s: HTTP status code. */
					__( 'سرور لایسنس پاسخ %s داد. کمی بعد دوباره تلاش کنید.', 'mahan' ),
					mahan_fa_numbers( (string) $code )
				)
			);
		}

		$data = json_decode( $body, true );

		if ( ! is_array( $data ) ) {
			return $fail( __( 'پاسخ سرور لایسنس قابل خواندن نبود.', 'mahan' ) );
		}

		$message = '';

		foreach ( array( 'message', 'error', 'msg' ) as $field ) {
			if ( ! empty( $data[ $field ] ) && is_string( $data[ $field ] ) ) {
				$message = sanitize_text_field( $data[ $field ] );
				break;
			}
		}

		// `success` reports whether the API call itself worked. A false here is
		// the server's own problem, not a verdict on the key, so it must not
		// revoke a licence that was good a moment ago.
		if ( array_key_exists( 'success', $data ) && ! self::truthy( $data['success'] ) && ! array_key_exists( 'valid', $data ) ) {
			return $fail( $message ? $message : __( 'سرور لایسنس درخواست را نپذیرفت.', 'mahan' ) );
		}

		$valid  = self::truthy( isset( $data['valid'] ) ? $data['valid'] : false );
		$status = isset( $data['status'] ) ? sanitize_key( (string) $data['status'] ) : ( $valid ? 'active' : 'invalid' );
		$expiry = '';

		foreach ( array( 'expiry_date', 'expires_at', 'expiry' ) as $field ) {
			if ( ! empty( $data[ $field ] ) && is_string( $data[ $field ] ) ) {
				$expiry = sanitize_text_field( $data[ $field ] );
				break;
			}
		}

		// An expiry in the past outranks whatever the status field claims.
		if ( $valid && $expiry && $this->is_past( $expiry ) ) {
			$valid  = false;
			$status = 'expired';
		}

		return array(
			'ok'      => true,
			'valid'   => $valid,
			'status'  => $status,
			'expiry'  => $expiry,
			'message' => $message,
		);
	}

	/**
	 * The validation endpoint.
	 *
	 * @return string
	 */
	public static function endpoint() {
		$endpoint = defined( 'MAHAN_LICENSE_ENDPOINT' ) ? MAHAN_LICENSE_ENDPOINT : self::ENDPOINT;

		/**
		 * Filters the licence validation endpoint.
		 *
		 * @param string $endpoint Absolute URL.
		 */
		return apply_filters( 'mahan_license_endpoint', $endpoint );
	}

	/**
	 * The product name sent to the licence server.
	 *
	 * @return string
	 */
	public static function product() {
		$product = defined( 'MAHAN_LICENSE_PRODUCT' ) ? MAHAN_LICENSE_PRODUCT : self::PRODUCT;

		/**
		 * Filters the product name sent with every validation request. It has
		 * to match the product registered in the licence manager exactly.
		 *
		 * @param string $product Product name.
		 */
		return apply_filters( 'mahan_license_product', $product );
	}

	/* --------------------------------------------------------------------
	 * AJAX
	 * ----------------------------------------------------------------- */

	/**
	 * Handles the activation form.
	 */
	public function ajax_activate() {
		$this->guard();

		$key = isset( $_POST['license_key'] ) ? sanitize_text_field( wp_unslash( $_POST['license_key'] ) ) : '';

		$this->respond( $this->activate( $key ) );
	}

	/**
	 * Re-checks the stored key on demand.
	 */
	public function ajax_refresh() {
		$this->guard();

		$this->respond( $this->refresh() );
	}

	/**
	 * Removes the stored licence.
	 */
	public function ajax_remove() {
		$this->guard();

		$this->remove();

		$this->respond( $this->result( true, __( 'لایسنس از این سایت حذف شد.', 'mahan' ) ) );
	}

	/**
	 * Rejects anyone without the capability or a fresh nonce.
	 */
	private function guard() {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'دسترسی لازم را ندارید.', 'mahan' ) ), 403 );
		}

		check_ajax_referer( 'mahan_license', 'nonce' );
	}

	/**
	 * Sends a result array back as JSON, with the refreshed view state.
	 *
	 * @param array $result Result of an activate/refresh/remove call.
	 */
	private function respond( array $result ) {
		$payload = array_merge(
			$result,
			array(
				'active'    => $this->is_active(),
				'maskedKey' => $this->masked_key(),
				'expiry'    => $this->expiry(),
				'daysLeft'  => $this->days_left(),
			)
		);

		if ( $result['ok'] ) {
			wp_send_json_success( $payload );
		}

		wp_send_json_error( $payload );
	}

	/**
	 * Packs a result with the condition that followed it.
	 *
	 * @param bool   $ok      Whether the operation succeeded.
	 * @param string $message Message for the user.
	 * @return array{ok:bool,message:string,condition:string}
	 */
	private function result( $ok, $message ) {
		return array(
			'ok'        => (bool) $ok,
			'message'   => $message,
			'condition' => $this->condition(),
		);
	}

	/* --------------------------------------------------------------------
	 * Scheduling
	 * ----------------------------------------------------------------- */

	/**
	 * Books the daily re-check.
	 */
	public function schedule() {
		if ( ! wp_next_scheduled( self::CRON_HOOK ) ) {
			wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', self::CRON_HOOK );
		}
	}

	/**
	 * Drops the daily re-check.
	 */
	public function unschedule() {
		$timestamp = wp_next_scheduled( self::CRON_HOOK );

		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, self::CRON_HOOK );
		}
	}

	/**
	 * Re-books the check if it went missing while a licence is stored.
	 */
	public function ensure_schedule() {
		if ( '' !== $this->key() ) {
			$this->schedule();
		}
	}

	/* --------------------------------------------------------------------
	 * Helpers
	 * ----------------------------------------------------------------- */

	/**
	 * Uppercases a typed key and strips everything that is not part of one.
	 *
	 * @param string $key Raw input.
	 * @return string
	 */
	public static function normalise_key( $key ) {
		$key = strtoupper( trim( (string) $key ) );
		$key = preg_replace( '/[^A-Z0-9-]/', '', $key );

		return (string) $key;
	}

	/**
	 * Whether a normalised key has the right shape.
	 *
	 * @param string $key Normalised key.
	 * @return bool
	 */
	public static function looks_like_key( $key ) {
		return (bool) preg_match( self::KEY_PATTERN, $key );
	}

	/**
	 * Reads the loose booleans an API may send.
	 *
	 * @param mixed $value Raw value.
	 * @return bool
	 */
	private static function truthy( $value ) {
		if ( is_bool( $value ) ) {
			return $value;
		}

		if ( is_numeric( $value ) ) {
			return (int) $value > 0;
		}

		return in_array( strtolower( (string) $value ), array( 'true', 'yes', 'active', 'valid' ), true );
	}

	/**
	 * Binds a licence to this key, product and host.
	 *
	 * @param string $key Licence key.
	 * @return string
	 */
	private function fingerprint( $key ) {
		$host = wp_parse_url( home_url( '/' ), PHP_URL_HOST );

		return wp_hash( $key . '|' . self::product() . '|' . (string) $host );
	}

	/**
	 * End of the given day, as a timestamp, or null when unparseable.
	 *
	 * @param string $date Date string from the server.
	 * @return int|null
	 */
	private function end_of_day( $date ) {
		$stamp = strtotime( $date );

		if ( false === $stamp ) {
			return null;
		}

		// A bare Y-m-d lands at midnight; the licence is good for that whole day.
		return preg_match( '/^\d{4}-\d{2}-\d{2}$/', trim( $date ) ) ? $stamp + DAY_IN_SECONDS - 1 : $stamp;
	}

	/**
	 * Whether a date has already gone by.
	 *
	 * @param string $date Date string.
	 * @return bool
	 */
	private function is_past( $date ) {
		$end = $this->end_of_day( $date );

		return null !== $end && $end < time();
	}

	/**
	 * Counts one attempt against the hourly ceiling.
	 *
	 * @return bool False once the ceiling is reached.
	 */
	private function take_attempt() {
		$tries = (int) get_transient( self::THROTTLE_KEY );

		if ( $tries >= self::THROTTLE_MAX ) {
			return false;
		}

		set_transient( self::THROTTLE_KEY, $tries + 1, HOUR_IN_SECONDS );

		return true;
	}

	/**
	 * Clears the attempt counter after a success.
	 */
	private function clear_attempts() {
		delete_transient( self::THROTTLE_KEY );
	}
}
