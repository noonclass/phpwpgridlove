<?php
/**
 * Main Meks Time Ago plugin class.
 *
 * This class loads plugin options, sets filters and converts the date on selected hooks.
 *
 * @subpackage Frontend interfaces
 * @author Sibin Grasic
 * @since 1.0
 * @var opts - plugin options
 */
class Meks_TA_Frontend {

	private $opts;

	/**
	 * Class Constructor
	 *
	 * Loads default options, sets default filter list and adds convert_date filter to selected locations
	 *
	 * @author Sibin Grasic
	 * @since 1.0
	 */
	public function __construct() {

		$default_opts = array(
			'active'   => array( 'date' => true, 'time' => true ),
			'position' => 'after',
			'time'    => array( 'number' => '12', 'type'  => 'months' ),
			'ago_label' => ''
		);

		$default_opts = apply_filters( 'meks_ta_modify_default_opts', $default_opts );

		$this->opts = get_option( 'meks_ta_opts', $default_opts );

		$this->opts = wp_parse_args( $this->opts, $default_opts);

		$filter_list = array();

		if ( $this->opts['active']['date'] ):
			$filter_list = array_merge( $filter_list, array( 'the_date', 'get_the_date' ) );
		endif;

		if ( $this->opts['active']['time'] ) :
			$filter_list = array_merge( $filter_list, array( 'get_the_time', 'the_time' ) );
		endif;


		/**
		 * Filter the list of applicable filter locations
		 *
		 * @since 1.0
		 * @param array   $filter_list List of filters for time appearance change
		 *
		 */
		$filters = apply_filters(
			'meks_ta_filters',
			$filter_list
		);

		

			foreach ( $filters as $filter ) :

				add_filter( $filter, array( &$this, 'convert_date' ), 10, 1 );

			endforeach;

	}


	/**
	 * Main plugin function which does the date conversion.
	 *
	 * If the plugin is not set as 'active', returns original time / date string.
	 * If the plugin is active, gets the current time, as well as the post time and displays the custom string
	 *
	 * @param string  $orig_time Original time / date string
	 * @author Sibin Grasic
	 * @since 1.0
	 */

	public function convert_date( $orig_time ) {

		if( !$this->check_compatibility() ){
			return $orig_time;
		}

		$time_arr = array(
			'minutes' => 60,
			'hours' => HOUR_IN_SECONDS,
			'days' => DAY_IN_SECONDS,
			'months' => YEAR_IN_SECONDS / 12,
		);

		//If option not set as active return original string.
		if ( !$this->opts['active'] ){
			return $orig_time;
		}

		$time = current_time( 'timestamp' );

		$limit = (int)$this->opts['time']['number'] * $time_arr[$this->opts['time']['type']] ;

		global $post;
		$post_time = strtotime( $post->post_date );

		if ( ( $time - $post_time ) <= $limit ) {

			$ago_label = !empty( $this->opts['ago_label'] ) ? $this->opts['ago_label'] : __( 'ago', 'meks-time-ago' );

			if ( $this->opts['position'] === 'after' ) {
				return human_time_diff( $post_time, $time ).' '.$ago_label;
			} else {
				return $ago_label.' '.human_time_diff( $post_time, $time );
			}

		}

		return $orig_time;

	}

	/**
	 * Check compatibility
	 *
	 * Check if there are plugins which may cause a conflict with our time ago functionality
	 * For example: AMP WordPress plugin already has time ago functionality
	 *
	 * @since 1.1.1
	 */
	
	function check_compatibility(){

		//Prevent conflict with AMP plugin
		if( function_exists( 'is_amp_endpoint') && is_amp_endpoint() ){
			return false;
		}

		return true;

	}


}

?>