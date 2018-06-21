<?php
/*
 Plugin Name: Widgets Device Display Option
 Plugin URI: https://wordpress.org/plugins/widgets-device-display-option/
 Description: Adds option for widgets to choose on which device they are displayed : mobile, desktop, or both.
 Version: 1.0
 Author: Mathieu Thollet
 Author URI: http://www.awebvision.fr
*/



class Widgets_Device_Display_Option
{

    private static $devices_fields = [
        ['value' => 'all', 'title' => 'All'],
        ['value' => 'mobile', 'title' => 'Mobile only'],
        ['value' => 'desktop', 'title' => 'Desktop only'],
    ];


    /**
     * Register the form elements (The widget Control)
     * @param WP_Widget $widget
     * @param $return
     * @param string[] $instance
     * @return array
     */
    public static function in_widget_form($widget, $return, $instance)
    {
        ?>
        <hr/>
        <p>
        <b>Device :</b><br/>
        <select id="<?= $widget->get_field_id('device'); ?>" name="<?= $widget->get_field_name('device'); ?>">
            <?php foreach (self::$devices_fields as $field) { ?>
                <option value="<?= $field['value'] ?>" <?= isset($instance['device']) && $instance['device'] == $field['value'] ? 'selected' : '' ?>>
                    <?= $field['title'] ?>
                </option>
                </label>
            <?php } ?>
        </select>
        </p><?php
        $return = null;
        return array($widget, $return, $instance);
    }


    /**
     * Save the Widget input data
     * @param string[] $instance
     * @param string[] $new_instance
     * @param string[] $old_instance
     * @return mixed
     */
    public static function widget_update_callback($instance, $new_instance, $old_instance)
    {
        $instance['device'] = !empty($new_instance['device']) ? $new_instance['device'] : '';
        return $instance;
    }


    /**
     * Display or hides widget
     * @param $params
     * @param $widget
     * @param $args
     * @return mixed
     */
    public static function widget_display_callback($instance, $widget, $args)
    {
        if (isset($instance['device']) && $instance['device'] == 'mobile' && !wp_is_mobile())
            return false;
        if (isset($instance['device']) && $instance['device'] == 'desktop' && wp_is_mobile())
            return false;
        return $instance;
    }

}



add_action('in_widget_form', ['Widgets_Device_Display_Option', 'in_widget_form'], 5, 3);
add_filter('widget_update_callback', ['Widgets_Device_Display_Option', 'widget_update_callback'], 5, 3);
add_filter('widget_display_callback', ['Widgets_Device_Display_Option', 'widget_display_callback'], 10, 3);
