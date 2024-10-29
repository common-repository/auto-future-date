<?php
/* Copyright 2012 Ryan Nutt - Aelora Web Services LLC
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public Licese, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation,Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

/*
Plugin Name: Auto Future Date
Plugin URI: http://www.nutt.net/tag/auto-future-date/
Description: Automatically schedule posts in the future. Uses a small Ajax call to get the latest post date on your site and sets the post date and time for the post you're working on to a configurable bit of time in the future. 
Author: Ryan Nutt
Version: 0.5.2
Author URI: http://www.nutt.net
*/

register_activation_hook(__FILE__,'afd_activate');
register_deactivation_hook(__FILE__,'afd_deactivate');

function afd_activate() {
    add_option('afd_options',
        array(
            'minTime' => '1:00:00',
            'maxTime' => '5:00:00',
            'startTime' => '8:00',
            'endTime' => '17:00'
        ));
}
function afd_deactivate() {
    delete_option('afd_options'); 
}

if (is_admin()) {
    add_action('admin_print_scripts-post-new.php', 'afd_admin_scripts');
    add_action('admin_print_scripts-post.php', 'afd_admin_scripts');
    add_action('admin_print_scripts-settings_page_afd-options', 'afd_admin_scripts');
    wp_register_script('afd-js', plugins_url('/autoFutureDate.js', __FILE__));
    add_action('wp_ajax_afd_get_date', 'afd_ajax');

    // Option pages
    add_action('admin_menu', 'afd_create_menu');
    add_filter('pre_update_option_afd_options', 'afd_update_options');

    function afd_admin_scripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('afd-js');
    }

    function afd_ajax() {
        $dateNow = date('U');
        $opts = get_option('afd_options');
        $opts = array_merge(array(
            'minTime' => '1:00:00',
            'maxTime' => '5:00:00',
            'startTime' => '8:00',
            'endTime' => '17:00'
        ), $opts);

        $posts = get_posts(array(
            'numberposts' => 1,
            'orderby' => 'post_date',
            'order' => 'DESC',
            'post_type', 'post',
            'post_status' => 'publish,future'
        ));
        if (!isset($posts[0])) {
            $lastPostDate = 0;
        }
        else {
            $lastPostDate = strtotime($posts[0]->post_date);
        }
        $currentTime = strtotime(current_time('mysql'));
        
        $startingDate = max($lastPostDate, $currentTime);

        $numMinutes = rand(afd_get_minutes($opts['minTime']), afd_get_minutes($opts['maxTime']));

        $date = $startingDate + ($numMinutes * 60);

        $mins = afd_get_minutes(date('0:G:i', $date));
        $maxMins = afd_get_minutes($opts['endTime']);
        $minMins = afd_get_minutes($opts['startTime']);
        //echo $mins.'/'.$maxMins.'/'.$minMins;
        $latestTime = strtotime(date('m/j/Y '.$opts['endTime']), $startingDate);
        // Check if it's too late
        if ($maxMins < $mins) {
            $date = strtotime($posts[0]->post_date) + (60 * 24 * 60); // add a day
            $date = strtotime(date('Y-M-d', $date).' ' .$opts['startTime']);
        }
        else if ($mins < $minMins) {
            $date = strtotime(date('Y-M-d', $date). ' ' . $opts['startTime']);
        }

        
        $newDate = date('D M j Y H:i:s', $date);
        echo $newDate;

        die();
    }

    function afd_random_time($start, $end) {
        $start = strtotime($start);
        $end = strtotime($end);

        $ts = rand($start, $end);
        return $ts; 
    }

    function afd_create_menu() {
        add_options_page('Auto Future Date Options', 'Auto Future Date', 'manage_options', 'afd-options', 'afd_options_page');
    }
    function afd_options_page() {
        $opts = get_option('afd_options'); 
        ?>
        <div class="wrap">
            <h2>Auto Future Date Settings
                <a target="_blank" href="http://www.nutt.net/tag/auto-future-date/?utm_source=wp&utm_medium=link&utm_campaign=afd">
            <img src="<?php echo plugins_url('help.png', __FILE__); ?>" />
                </a>
            </h2>
        <form method="post" action="options.php">
        <?php wp_nonce_field('update-options'); ?>
        <table class="form-table">
            <tr>
                <th scope="row">No posts before</th>
                <td><input type="text" name="afd_options[startTime]" value="<?php echo $opts['startTime'];?>" /></td>
            </tr>
            <tr>
                <th scope="row">No posts after</th>
                <td><input type="text" name="afd_options[endTime]" value="<?php echo $opts['endTime']; ?>" /></td>
            </tr>
            <tr>
                <th scope="row">Minimum time between posts</th>
                <td><input id="afd_minTime" type="text" name="afd_options[minTime]" value="<?php echo $opts['minTime']; ?>" />
                    <span id="afd_minFormatted" style="padding-left:10px;"></span>
                </td>
            </tr>
            <tr>
                <th scope="row">Maximum time between posts</th>
                <td><input id="afd_maxTime" type="text" name="afd_options[maxTime]" value="<?php echo $opts['maxTime']; ?>" />
                    <span id="afd_maxFormatted" style="padding-left:10px;"></span>
                </td>
            </tr>
        </table>
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="afd_options" />

        
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
        </p>
        </form>
        <p style="margin-top:20px;">
            <a href="https://twitter.com/RyanNutt" class="twitter-follow-button" data-show-count="false" data-lang="en">Follow @RyanNutt</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </p>
    
            </div>
<?php
    }

    function afd_update_options($args) {
        $newSettings = array(
            'startTime' => '8:00',
            'endTime' => '17:00',
            'minTime' => afd_get_string(afd_get_minutes($args['minTime'])),
            'maxTime' => afd_get_string(afd_get_minutes($args['maxTime']))
        );
        
        // Validate the time entries to make sure they're formatted correctly
        $start = strtotime($args['startTime']);
        if ($start != 0) {
            $newSettings['startTime'] = date('G:i', $start);
        }
        $end = strtotime($args['endTime']);
        if ($end != 0) {
            $newSettings['endTime'] = date('G:i', $end);
        }

        return $newSettings; 
    }

    function afd_get_string($num) {
        $num = intval($num);
        $d = floor($num / 1440);
        $h = floor(($num - $d * 1440) / 60);
        $m = $num - ($d * 1440) - ($h * 60);

        return $d.':'.str_pad($h, 2, '0', STR_PAD_LEFT).':'.str_pad($m, 2, '0', STR_PAD_LEFT);

    }

    /**
     * get the number of minutes in a string formatted like 1:23:45 (day:hours:mins)
     */
    function afd_get_minutes($str) {
        $ray = preg_split('/:|\./', $str);
        if (count($ray)>3) {
            $ray = array_slice($ray, count($ray) - 3);
        }
        while (count($ray)<3) {
            array_unshift($ray, 0); 
        }

        return intval($ray[2]) + (60 * intval($ray[1])) + (60 * 24 * intval($ray[0]));
    }
}


?>