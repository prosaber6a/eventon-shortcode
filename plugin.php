<?php
/*
 * Plugin Name: EventOn Shortcode
 * Plugin URI:        http://saberhr.me/
 * Description:       Custom event on display shortcode plugin
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Saber Hossen Rabbani
 * Author URI:        http://saberhr.me/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       eventshortcode
 */

add_action('plugins_loaded', 'eventshortcode_load_textdomain');
function eventshortcode_load_textdomain()
{
    load_plugin_textdomain('eventshortcode', false, plugin_dir_url(__FILE__) . '/languages');
}


add_action('wp_enqueue_scripts', 'eventon_shortcode_assets');
function eventon_shortcode_assets($screen)
{
    wp_enqueue_style('fontawessome-css', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
    wp_enqueue_style('tailwind-css', '//unpkg.com/tailwindcss@^2/dist/tailwind.min.css');

}


add_filter('manage_edit-ajde_events_columns', 'eventon_table_shortcode_column', 10, 2);
function eventon_table_shortcode_column($array)
{
    $array['evonshortcode'] = __('Shortcode', 'eventshortcode');
    return $array;

}

add_action('manage_ajde_events_posts_custom_column', 'shortcode_print_in_table', 10, 2);

function shortcode_print_in_table($column, $post_id)
{
    if ('evonshortcode' == $column) {
        echo '[eventon id="' . $post_id . '"]';
    }
}


add_filter('evo_max_cmd_count','custom_function_2',10,1);
function custom_function_2($number){
    return 16;
}


add_shortcode('eventon', function ($attr) {


    $id = intval($attr['id']);

	$location_name = "";
	$event_terms = wp_get_post_terms($id, 'event_location');
	if ( $event_terms && ! is_wp_error( $event_terms ) ){
		$event_location_term = $event_terms[0];
		$location_name = $event_location_term->name;
	}



    $title = esc_html(get_the_title($id));
    $content = get_the_content(null, false, $id);
    $thumbnail = get_the_post_thumbnail_url($id, 'full');
    $start_time = get_post_meta($id, '_start_hour', true) . " : ". get_post_meta($id, '_start_minute', true). " : ". get_post_meta($id, '_start_ampm', true);
    $end_time = get_post_meta($id, '_end_hour', true) . " : ". get_post_meta($id, '_end_minute', true). " : ". get_post_meta($id, '_end_ampm', true);



    return <<<EOD

<div class="container">
    <div class="bg-gray-800 p-2">
        <div class="bg-gray-600 grid gap-2 grid-cols-10 px-4 py-2 rounded text-white" style="border-left: 5px solid skyblue;">
            <div>
                <p class="p-0 text-2xl">06</p>
                <p>September</p>
            </div>
            <div class="col-span-9 pl-4 text-2xl">
                $title
            </div>
        </div>
        <div class="bg-center bg-cover bg-no-repeat h-96 mt-2 rounded w-full" style="background-image: url($thumbnail);"></div>
        <div class="bg-gray-600 grid grid-cols-10 mt-2 p-2 rounded text-white">
            <p class="text-center">
                <i class="fas fa-align-justify"></i>
            </p>
            <div class="col-span-9">
                <h3 class="text-white">Event Details</h3>
                <p>$content</p>
            </div>
        </div>
        <div class="gap-3 grid grid-cols-2 mt-2 p-2 text-white">
            <div class="grid grid-cols-5 bg-gray-600 rounded p-2">
                <p class="text-center"><i class="fas fa-clock"></i></p>
                <div class="col-span-4">
                    <h3 class="text-white">Time</h3>
                    <p>(Friday) $start_time - $end_time</p>
                </div>
            </div>
            <div class="grid grid-cols-5 bg-gray-600 rounded p-2">
                <p class="text-center"><i class="fas fa-location"></i></p>
                <div class="col-span-4">
                    <h3 class="text-white">Location</h3>
                    <p>$location_name</p>
                </div>
            </div>
        </div>

    </div>
</div>


EOD;

});