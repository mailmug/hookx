<?php 

// $obj = new Hookx\Action;
// $obj->add_callback('some_callback', 10, 0, 1);
// $obj->add_callback('some_callback', 9, 0, 1);



// $obj->add_filter('some_callback', 'a_some_callback_funct', 10, 3);
// $obj->add_filter('some_callback_1', 'a_some_callback_funct', 10, 1);
// $obj->add_filter('some_callback_5', 'a_some_callback_funct', 10, 1);
// $obj->add_filter('some_callback_7', 'a_some_callback_funct', 10, 1);

function abc( $a){
    echo 88;
}
add_filter('init', 'abc', 10, 3);
add_filter('init', 'b', 7, 3);
echo apply_filters('init', null).'hh';


$credentials = [343];
do_action_ref_array( 'wp_authenticate', array( &$credentials ) );

add_action('my_custom_action', 'do_first_task');
apply_filters('my_custom_action', 99);

function do_first_task() { echo '----++';

    add_action('wp_authenticate', 'wp_authenticate1');
add_action('wp_authenticate', 'wp_authenticate2');
add_action('wp_authenticate', 'wp_authenticate3');
add_action('wp_authenticate', 'wp_authenticate4', 10, 3);
add_action('wp_authenticate', 'wp_authenticate5', 20);
   // First task
   echo 'First task done.<br>';

   // Trigger a nested action hook
   apply_filters('my_second_action', 0);
}

// $this->callbacks[ $priority ][ $idx ] = array(
		// 	'function'      => $callback,
		// 	'accepted_args' => (int) $accepted_args,
		// );
function wp_authenticate2(){}
function wp_authenticate3(){}
function wp_authenticate4(){}
function wp_authenticate5(){}
function wp_authenticate6(){}
 
// Nested hook
add_action('my_second_action', 'do_second_task');

function do_second_task() {
    echo 'Second task done.<br>';
}

function wp_authenticate1(
    $username
) {
echo 5555555;
}
die;


function do_action_ref_array( $hook_name, $args ) {
	global $wp_filter, $wp_actions, $wp_current_filter;

	if ( ! isset( $wp_actions[ $hook_name ] ) ) {
		$wp_actions[ $hook_name ] = 1;
	} else {
		++$wp_actions[ $hook_name ];
	}

	// Do 'all' actions first.
	if ( isset( $wp_filter['all'] ) ) {
		$wp_current_filter[] = $hook_name;
		$all_args            = func_get_args(); // phpcs:ignore PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue.NeedsInspection
		_wp_call_all_hook( $all_args );
	}

    if ( ! isset( $wp_filter[ $hook_name ] ) ) {
		if ( isset( $wp_filter['all'] ) ) {
			array_pop( $wp_current_filter );
		}

		return;
	}

	if ( ! isset( $wp_filter['all'] ) ) {
		$wp_current_filter[] = $hook_name;
	}

	$wp_filter[ $hook_name ]->do_action( $args );

	array_pop( $wp_current_filter );
}


function add_filter( $hook_name, $callback, $priority = 10, $accepted_args = 1 ) {
	global $wp_filter;

	if ( ! isset( $wp_filter[ $hook_name ] ) ) {
		$wp_filter[ $hook_name ] = new WP_Hook();
	}

	$wp_filter[ $hook_name ]->add_filter( $hook_name, $callback, $priority, $accepted_args );

	return true;
}



function add_action( $hook_name, $callback, $priority = 10, $accepted_args = 1 ) {
	return add_filter( $hook_name, $callback, $priority, $accepted_args );
}

function apply_filters( $hook_name, $value, ...$args ) {  
	global $wp_filter, $wp_filters, $wp_current_filter;

	if ( ! isset( $wp_filters[ $hook_name ] ) ) {
		$wp_filters[ $hook_name ] = 1;
	} else {
		++$wp_filters[ $hook_name ];
	}

    // Do 'all' actions first.
	if ( isset( $wp_filter['all'] ) ) {
		$wp_current_filter[] = $hook_name;

		$all_args = func_get_args(); // phpcs:ignore PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue.NeedsInspection
		_wp_call_all_hook( $all_args );
	}

	if ( ! isset( $wp_filter[ $hook_name ] ) ) {
		if ( isset( $wp_filter['all'] ) ) {
			array_pop( $wp_current_filter );
		}

		return $value;
	}

	if ( ! isset( $wp_filter['all'] ) ) {
		$wp_current_filter[] = $hook_name;
	}

	// Pass the value to WP_Hook.
	array_unshift( $args, $value );

	$filtered = $wp_filter[ $hook_name ]->apply_filters( $value, $args );

	array_pop( $wp_current_filter );

	return $filtered;
}


global $wp_filter;

function a(&$r){
    return $r.'dddd';
}

echo apply_filters( 'update_term_metadata_cache',  1);
echo apply_filters( 'delete_term_metadata_by_mid',  "sdd");
echo apply_filters( 'update_term_metadata_by_mid',  "sdd");

add_filter( 'get_term_metadata', 'wp_check_term_meta_support_prefilter' );
add_filter( 'add_term_metadata', 'wp_check_term_meta_support_prefilter' );
add_filter( 'update_term_metadata', 'wp_check_term_meta_support_prefilter' );
add_filter( 'delete_term_metadata', 'wp_check_term_meta_support_prefilter' );
add_filter( 'get_term_metadata_by_mid', 'wp_check_term_meta_support_prefilter' );
add_filter( 'update_term_metadata_by_mid', 'wp_check_term_meta_support_prefilter' );
add_filter( 'delete_term_metadata_by_mid', 'wp_check_term_meta_support_prefilter' );
add_filter( 'update_term_metadata_cache', 'wp_check_term_meta_support_prefilter' );

function wp_check_term_meta_support_prefilter( $check ) {
	 

	return $check;
}

function has_filter( $hook_name, $callback = false ) {
	global $wp_filter;

	if ( ! isset( $wp_filter[ $hook_name ] ) ) {
		return false;
	}

	return $wp_filter[ $hook_name ]->has_filter( $hook_name, $callback );
}

function remove_filter( $hook_name, $callback, $priority = 10 ) {
	global $wp_filter;

	$r = false;

	if ( isset( $wp_filter[ $hook_name ] ) ) {
		$r = $wp_filter[ $hook_name ]->remove_filter( $hook_name, $callback, $priority );

		if ( ! $wp_filter[ $hook_name ]->callbacks ) {
			unset( $wp_filter[ $hook_name ] );
		}
	}

	return $r;
}

function wpautop($d){
    return 6;
}

add_filter('the_content','_restore_wpautop_hook');

$current_priority = has_filter( 'the_content', '_restore_wpautop_hook' );
add_filter( 'the_content', 'wpautop', $current_priority - 1 );
remove_filter( 'the_content', '_restore_wpautop_hook', $current_priority );

apply_filters('the_content', 99);

add_filter('init', 'a', 10, 3);

// for ($i=0; $i < 300000; $i++) { 
//     echo apply_filters('init', 55);
//     echo $i.'-';
// }




/**
 * Plugin API: WP_Hook class
 *
 * @package WordPress
 * @subpackage Plugin
 * @since 4.7.0
 */

/**
 * Core class used to implement action and filter hook functionality.
 *
 * @since 4.7.0
 *
 * @see Iterator
 * @see ArrayAccess
 */
#[AllowDynamicProperties]
class WP_Hook extends Hookx\Action {

	/**
	 * Hook callbacks.
	 *
	 * @since 4.7.0
	 * @var array
	 */
	public $callbacks = array();

	/**
	 * Priorities list.
	 *
	 * @since 6.4.0
	 * @var array
	 */
	protected $priorities = array();

	/**
	 * The priority keys of actively running iterations of a hook.
	 *
	 * @since 4.7.0
	 * @var array
	 */
	private $iterations = array();

	/**
	 * The current priority of actively running iterations of a hook.
	 *
	 * @since 4.7.0
	 * @var array
	 */
	private $current_priority = array();

	/**
	 * Number of levels this hook can be recursively called.
	 *
	 * @since 4.7.0
	 * @var int
	 */
	private $nesting_level = 0;

	/**
	 * Flag for if we're currently doing an action, rather than a filter.
	 *
	 * @since 4.7.0
	 * @var bool
	 */
	private $doing_action = false;

	/**
	 * Adds a callback function to a filter hook.
	 *
	 * @since 4.7.0
	 *
	 * @param string   $hook_name     The name of the filter to add the callback to.
	 * @param callable $callback      The callback to be run when the filter is applied.
	 * @param int      $priority      The order in which the functions associated with a particular filter
	 *                                are executed. Lower numbers correspond with earlier execution,
	 *                                and functions with the same priority are executed in the order
	 *                                in which they were added to the filter.
	 * @param int      $accepted_args The number of arguments the function accepts.
	 */
	public function add_filter( $hook_name, $callback, $priority, $accepted_args ) {
		$idx = md5( $hook_name );

        $priority_existed = isset( $this->callbacks[ $priority ] );

		// $this->callbacks[ $priority ][ $idx ] = array(
		// 	'function'      => $callback,
		// 	'accepted_args' => (int) $accepted_args,
		// );

		$accepted_args = (int)$accepted_args;

		$this->add_callback($callback, $priority, $idx, $accepted_args );

        // echo 'oooo'.PHP_EOL;
		// $this->add_callback($callback, $priority, $idx, $accepted_args );


		// If we're adding a new priority to the list, put them back in sorted order.
		if ( ! $priority_existed && count( $this->callbacks ) > 1 ) {
			ksort( $this->callbacks, SORT_NUMERIC );
		}

		$this->priorities = array_keys( $this->callbacks );

		if ( $this->nesting_level > 0 ) {
			$this->resort_active_iterations( $priority, $priority_existed );
		}
	}

	/**
	 * Handles resetting callback priority keys mid-iteration.
	 *
	 * @since 4.7.0
	 *
	 * @param false|int $new_priority     Optional. The priority of the new filter being added. Default false,
	 *                                    for no priority being added.
	 * @param bool      $priority_existed Optional. Flag for whether the priority already existed before the new
	 *                                    filter was added. Default false.
	 */
	private function resort_active_iterations( $new_priority = false, $priority_existed = false ) {
		$new_priorities = $this->priorities;

		// If there are no remaining hooks, clear out all running iterations.
		if ( ! $new_priorities ) {
			foreach ( $this->iterations as $index => $iteration ) {
				$this->iterations[ $index ] = $new_priorities;
			}

			return;
		}

		$min = min( $new_priorities );

		foreach ( $this->iterations as $index => &$iteration ) {
			$current = current( $iteration );

			// If we're already at the end of this iteration, just leave the array pointer where it is.
			if ( false === $current ) {
				continue;
			}

			$iteration = $new_priorities;

			if ( $current < $min ) {
				array_unshift( $iteration, $current );
				continue;
			}

			while ( current( $iteration ) < $current ) {
				if ( false === next( $iteration ) ) {
					break;
				}
			}

			// If we have a new priority that didn't exist, but ::apply_filters() or ::do_action() thinks it's the current priority...
			if ( $new_priority === $this->current_priority[ $index ] && ! $priority_existed ) {
				/*
				 * ...and the new priority is the same as what $this->iterations thinks is the previous
				 * priority, we need to move back to it.
				 */

				if ( false === current( $iteration ) ) {
					// If we've already moved off the end of the array, go back to the last element.
					$prev = end( $iteration );
				} else {
					// Otherwise, just go back to the previous element.
					$prev = prev( $iteration );
				}

				if ( false === $prev ) {
					// Start of the array. Reset, and go about our day.
					reset( $iteration );
				} elseif ( $new_priority !== $prev ) {
					// Previous wasn't the same. Move forward again.
					next( $iteration );
				}
			}
		}

		unset( $iteration );
	}

	/**
	 * Removes a callback function from a filter hook.
	 *
	 * @since 4.7.0
	 *
	 * @param string                $hook_name The filter hook to which the function to be removed is hooked.
	 * @param callable|string|array $callback  The callback to be removed from running when the filter is applied.
	 *                                         This method can be called unconditionally to speculatively remove
	 *                                         a callback that may or may not exist.
	 * @param int                   $priority  The exact priority used when adding the original filter callback.
	 * @return bool Whether the callback existed before it was removed.
	 */
	public function remove_filter( $hook_name, $callback, $priority ) {
		$function_key = md5( $hook_name );

		$exists = isset( $this->callbacks[ $priority ][ $function_key ] );

		if ( $exists ) {
			unset( $this->callbacks[ $priority ][ $function_key ] );

			if ( ! $this->callbacks[ $priority ] ) {
				unset( $this->callbacks[ $priority ] );

				$this->priorities = array_keys( $this->callbacks );

				if ( $this->nesting_level > 0 ) {
					$this->resort_active_iterations();
				}
			}
		}

		return $exists;
	}

	/**
	 * Checks if a specific callback has been registered for this hook.
	 *
	 * When using the `$callback` argument, this function may return a non-boolean value
	 * that evaluates to false (e.g. 0), so use the `===` operator for testing the return value.
	 *
	 * @since 4.7.0
	 *
	 * @param string                      $hook_name Optional. The name of the filter hook. Default empty.
	 * @param callable|string|array|false $callback  Optional. The callback to check for.
	 *                                               This method can be called unconditionally to speculatively check
	 *                                               a callback that may or may not exist. Default false.
	 * @return bool|int If `$callback` is omitted, returns boolean for whether the hook has
	 *                  anything registered. When checking a specific function, the priority
	 *                  of that hook is returned, or false if the function is not attached.
	 */
	public function has_filter( $hook_name = '', $callback = false ) {
		if ( false === $callback ) {
			return $this->has_filters();
		}

		$function_key = md5( $hook_name);

		if ( ! $function_key ) {
			return false;
		}

		foreach ( $this->callbacks as $priority => $callbacks ) {
			if ( isset( $callbacks[ $function_key ] ) ) {
				return $priority;
			}
		}

		return false;
	}

	/**
	 * Checks if any callbacks have been registered for this hook.
	 *
	 * @since 4.7.0
	 *
	 * @return bool True if callbacks have been registered for the current hook, otherwise false.
	 */
	public function has_filters() {
		foreach ( $this->callbacks as $callbacks ) {
			if ( $callbacks ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Removes all callbacks from the current filter.
	 *
	 * @since 4.7.0
	 *
	 * @param int|false $priority Optional. The priority number to remove. Default false.
	 */
	public function remove_all_filters( $priority = false ) {
		if ( ! $this->callbacks ) {
			return;
		}

		if ( false === $priority ) {
			$this->callbacks  = array();
			$this->priorities = array();
		} elseif ( isset( $this->callbacks[ $priority ] ) ) {
			unset( $this->callbacks[ $priority ] );
			$this->priorities = array_keys( $this->callbacks );
		}

		if ( $this->nesting_level > 0 ) {
			$this->resort_active_iterations();
		}
	}

	/**
	 * Calls the callback functions that have been added to a filter hook.
	 *
	 * @since 4.7.0
	 *
	 * @param mixed $value The value to filter.
	 * @param array $args  Additional parameters to pass to the callback functions.
	 *                     This array is expected to include $value at index 0.
	 * @return mixed The filtered value after all hooked functions are applied to it.
	 */
	public function apply_filters( $value, $args ) {  
		if ( ! $this->callbacks ) {
			return $value;
		}

        $nesting_level = $this->nesting_level++;
		$this->iterations[ $nesting_level ] = $this->priorities;

		$num_args = count( $args );

		do {
			$this->current_priority[ $nesting_level ] = current( $this->iterations[ $nesting_level ] );

			$priority = $this->current_priority[ $nesting_level ];
          
			foreach ( $this->callbacks[ $priority ] as $the_ ) {
				if ( ! $this->doing_action ) {
					$args[0] = $value;
				}
               
				// Avoid the array_slice() if possible.
                // if( empty($the_['accepted_args']) ){
                //     // echo 888;
                //     // var_dump( $wp_filter );
                // }
                $function = $this->get_callback_func($the_);
                $accepted_args = $this->get_callback_arg_num($the_);

                if ( 0 === $accepted_args ) {
					$value = call_user_func( $function );
				} elseif ( $accepted_args >= $num_args ) {
					$value = call_user_func_array( $function, $args );
				} else {
					$value = call_user_func_array( $function, array_slice( $args, 0, $accepted_args ) );
				}
			}
		} while ( false !== next( $this->iterations[ $nesting_level ] ) );

		unset( $this->iterations[ $nesting_level ] );
		unset( $this->current_priority[ $nesting_level ] );

		--$this->nesting_level;

		return $value;
	}

	/**
	 * Calls the callback functions that have been added to an action hook.
	 *
	 * @since 4.7.0
	 *
	 * @param array $args Parameters to pass to the callback functions.
	 */
	public function do_action( $args ) {
		$this->doing_action = true;
		$this->apply_filters( '', $args );

		// If there are recursive calls to the current action, we haven't finished it until we get to the last one.
		if ( ! $this->nesting_level ) {
			$this->doing_action = false;
		}
	}

	/**
	 * Processes the functions hooked into the 'all' hook.
	 *
	 * @since 4.7.0
	 *
	 * @param array $args Arguments to pass to the hook callbacks. Passed by reference.
	 */
	public function do_all_hook( &$args ) {
		$nesting_level                      = $this->nesting_level++;
		$this->iterations[ $nesting_level ] = $this->priorities;

		do {
			$priority = current( $this->iterations[ $nesting_level ] );

			foreach ( $this->callbacks[ $priority ] as $the_ ) {
                $function = $this->get_callback_func($the_);
				call_user_func_array( $function, $args );
			}
		} while ( false !== next( $this->iterations[ $nesting_level ] ) );

		unset( $this->iterations[ $nesting_level ] );
		--$this->nesting_level;
	}

	/**
	 * Return the current priority level of the currently running iteration of the hook.
	 *
	 * @since 4.7.0
	 *
	 * @return int|false If the hook is running, return the current priority level.
	 *                   If it isn't running, return false.
	 */
	public function current_priority() {
		if ( false === current( $this->iterations ) ) {
			return false;
		}

		return current( current( $this->iterations ) );
	}

	/**
	 * Normalizes filters set up before WordPress has initialized to WP_Hook objects.
	 *
	 * The `$filters` parameter should be an array keyed by hook name, with values
	 * containing either:
	 *
	 *  - A `WP_Hook` instance
	 *  - An array of callbacks keyed by their priorities
	 *
	 * Examples:
	 *
	 *     $filters = array(
	 *         'wp_fatal_error_handler_enabled' => array(
	 *             10 => array(
	 *                 array(
	 *                     'accepted_args' => 0,
	 *                     'function'      => function() {
	 *                         return false;
	 *                     },
	 *                 ),
	 *             ),
	 *         ),
	 *     );
	 *
	 * @since 4.7.0
	 *
	 * @param array $filters Filters to normalize. See documentation above for details.
	 * @return WP_Hook[] Array of normalized filters.
	 */
	public static function build_preinitialized_hooks( $filters ) {
		/** @var WP_Hook[] $normalized */
		$normalized = array();

		foreach ( $filters as $hook_name => $callback_groups ) {
			if ( $callback_groups instanceof WP_Hook ) {
				$normalized[ $hook_name ] = $callback_groups;
				continue;
			}

			$hook = new WP_Hook();

			// Loop through callback groups.
			foreach ( $callback_groups as $priority => $callbacks ) {

				// Loop through callbacks.
				foreach ( $callbacks as $cb ) {
                    $function = $this->get_callback_func($cb);
                    $accepted_args = $this->get_callback_arg_num($cb);
					$hook->add_filter( $hook_name, $function, $priority, $accepted_args );
				}
			}

			$normalized[ $hook_name ] = $hook;
		}

		return $normalized;
	}

	/**
	 * Determines whether an offset value exists.
	 *
	 * @since 4.7.0
	 *
	 * @link https://www.php.net/manual/en/arrayaccess.offsetexists.php
	 *
	 * @param mixed $offset An offset to check for.
	 * @return bool True if the offset exists, false otherwise.
	 */
	#[ReturnTypeWillChange]
	public function offsetExists( $offset ) {
		return isset( $this->callbacks[ $offset ] );
	}

	/**
	 * Retrieves a value at a specified offset.
	 *
	 * @since 4.7.0
	 *
	 * @link https://www.php.net/manual/en/arrayaccess.offsetget.php
	 *
	 * @param mixed $offset The offset to retrieve.
	 * @return mixed If set, the value at the specified offset, null otherwise.
	 */
	#[ReturnTypeWillChange]
	public function offsetGet( $offset ) {
		return isset( $this->callbacks[ $offset ] ) ? $this->callbacks[ $offset ] : null;
	}

	/**
	 * Sets a value at a specified offset.
	 *
	 * @since 4.7.0
	 *
	 * @link https://www.php.net/manual/en/arrayaccess.offsetset.php
	 *
	 * @param mixed $offset The offset to assign the value to.
	 * @param mixed $value The value to set.
	 */
	#[ReturnTypeWillChange]
	public function offsetSet( $offset, $value ) {
		if ( is_null( $offset ) ) {
			$this->callbacks[] = $value;
		} else {
			$this->callbacks[ $offset ] = $value;
		}

		$this->priorities = array_keys( $this->callbacks );
	}

	/**
	 * Unsets a specified offset.
	 *
	 * @since 4.7.0
	 *
	 * @link https://www.php.net/manual/en/arrayaccess.offsetunset.php
	 *
	 * @param mixed $offset The offset to unset.
	 */
	#[ReturnTypeWillChange]
	public function offsetUnset( $offset ) {
		unset( $this->callbacks[ $offset ] );
		$this->priorities = array_keys( $this->callbacks );
	}

	/**
	 * Returns the current element.
	 *
	 * @since 4.7.0
	 *
	 * @link https://www.php.net/manual/en/iterator.current.php
	 *
	 * @return array Of callbacks at current priority.
	 */
	#[ReturnTypeWillChange]
	public function current() {
		return current( $this->callbacks );
	}

	/**
	 * Moves forward to the next element.
	 *
	 * @since 4.7.0
	 *
	 * @link https://www.php.net/manual/en/iterator.next.php
	 *
	 * @return array Of callbacks at next priority.
	 */
	#[ReturnTypeWillChange]
	public function next() {
		return next( $this->callbacks );
	}

	/**
	 * Returns the key of the current element.
	 *
	 * @since 4.7.0
	 *
	 * @link https://www.php.net/manual/en/iterator.key.php
	 *
	 * @return mixed Returns current priority on success, or NULL on failure
	 */
	#[ReturnTypeWillChange]
	public function key() {
		return key( $this->callbacks );
	}

	/**
	 * Checks if current position is valid.
	 *
	 * @since 4.7.0
	 *
	 * @link https://www.php.net/manual/en/iterator.valid.php
	 *
	 * @return bool Whether the current position is valid.
	 */
	#[ReturnTypeWillChange]
	public function valid() {
		return key( $this->callbacks ) !== null;
	}

	/**
	 * Rewinds the Iterator to the first element.
	 *
	 * @since 4.7.0
	 *
	 * @link https://www.php.net/manual/en/iterator.rewind.php
	 */
	#[ReturnTypeWillChange]
	public function rewind() {
		reset( $this->callbacks );
	}
}


 

// $obj = new WP_HOOK_WP;
// $obj->add_filter('some_callback', 'a_some_callback_funct', 10, 3);
// $obj->add_filter('some_callback_1', 'a_some_callback_funct', 10, 1);
// $obj->add_filter('some_callback_5', 'a_some_callback_funct', 10, 1);
// $obj->add_filter('some_callback_7', 'a_some_callback_funct', 10, 1);

// foreach ($obj->callbacks as $key => $value) {
//     foreach($value as $v){
//         $a = $v;
//     }
// }





add_action('init', function() {
    // This runs very early
    echo('Init hook triggered!');
});

// add_action('wp_footer', function() {
//     // This prints at the bottom of the site
//     echo '<p style="text-align:center;">Footer injected by anonymous function!</p>';
// });

// add_action('the_content', function($content) {
//     // This modifies the post content
//     $extra = '<p><em>Thanks for reading this post!</em></p>';
//     return $content . $extra;
// }, 15); // priority 15 (after default 10)

// add_action('comment_post', function($comment_ID, $comment_approved) {
//     // This logs when a comment is posted
//     if (1 === $comment_approved) {
//         error_log('A comment was approved! ID: ' . $comment_ID);
//     }
// }, 10, 2);

// OPTIONAL: You can even hook admin-only things
add_action('admin_notices', function() {
    echo '<div class="notice notice-success is-dismissible"><p>Custom admin notice from anonymous function.</p></div>';
});


class My_Plugin {
    public function __construct() { echo 'arra';
        add_filter('init', [$this, 'init_hook']);
        add_filter('init', [$this, 'okkkk3'], 13);
    }

    public function init_hook() { echo 9999;
        // Do something on 'init'
        echo('Plugin initialized!df');
    }
}
new My_Plugin();


function _wp_call_all_hook( $args ) {
	global $wp_filter;

	$wp_filter['all']->do_all_hook( $args );
}

add_filter('init', 'a', 10, 3);
add_filter('init', 'b', 10, 3);

function b(){

}
 echo json_encode($wp_filter );

