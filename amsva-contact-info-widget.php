<?php
/**
 * Plugin Name: AMSVA Contact Info Widget
 * Description: A customizable widget to showcase your contact information with ease.
 * Version: 1.0.0
 * Author: Ahmed Mahmoud
 * Author URI: https://amsvaservices.com
 * Text Domain: amsva-contact-info-widget
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Stable Tag: 1.0.0
 * Tested Up To: 6.6
 */


// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load plugin text domain for translations
function ciw_load_textdomain() {
    load_plugin_textdomain('amsva-contact-info-widget', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'ciw_load_textdomain');

/**
 * Enqueue Font Awesome if not already included in the theme.
 */
function ciw_enqueue_font_awesome() {
    // Check if we are on the frontend and on specific pages
    if (!is_admin() && (is_page('your-page-slug') || is_single('your-post-id'))) {
        // Check if Font Awesome is already enqueued
        if (!wp_style_is('font-awesome', 'enqueued')) {
            // Enqueue the local Font Awesome CSS file
            wp_enqueue_style('font-awesome', plugin_dir_url(__FILE__) . 'css/amsva-contact-info-widget.css', array(), '6.0.0-beta3');
        }
    }
}

// Hook into the 'wp_enqueue_scripts' action to ensure styles are loaded
add_action('wp_enqueue_scripts', 'ciw_enqueue_font_awesome');



// Register the widget
function register_contact_info_widget() {
    register_widget('Contact_Info_Widget');
}
add_action('widgets_init', 'register_contact_info_widget');

/**
 * AMSVA Contact Info Widget class.
 */
class Contact_Info_Widget extends WP_Widget {

    /**
     * Construct the widget.
     */
    public function __construct() {
        parent::__construct(
            'contact_info_widget', // Base ID
            __('AMSVA Contact Info Widget', 'amsva-contact-info-widget'), // Name
            array('description' => __('A widget to display contact information', 'amsva-contact-info-widget')) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        // Add custom class to the title based on widget class name
        $title_class = sanitize_title($this->id_base) . '-title';

// Display widget title
echo wp_kses_post($args['before_widget']); // Allow HTML as it's defined by the theme
if (!empty($instance['title'])) {
    // Escape the widget title properly
    $widget_title = apply_filters('widget_title', esc_html($instance['title'])); // Apply filters to the title
    echo wp_kses_post($args['before_title']) . esc_html($widget_title) . wp_kses_post($args['after_title']); // Escape widget title before output
}

    

        // Display contact information
        if (!empty($instance['name'])) {
            echo '<p><i class="fas fa-user"></i> ' . esc_html($instance['name']) . '</p>';
        }
        if (!empty($instance['position'])) {
            echo '<p><i class="fas fa-briefcase"></i> ' . esc_html($instance['position']) . '</p>';
        }
        if (!empty($instance['company'])) {
            echo '<p><i class="fas fa-building"></i> ' . esc_html($instance['company']) . '</p>';
        }
        if (!empty($instance['address'])) {
            echo '<p><i class="fas fa-map-marker-alt"></i> ' . esc_html($instance['address']) . '</p>';
        }
        if (!empty($instance['phone'])) {
            echo '<p><i class="fas fa-phone"></i> ' . esc_html($instance['phone']) . '</p>';
        }
        if (!empty($instance['email'])) {
            echo '<p><i class="fas fa-envelope"></i> <a href="mailto:' . esc_attr($instance['email']) . '">' . esc_html($instance['email']) . '</a></p>';
        }

        echo wp_kses_post($args['after_widget']);
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        // Get saved values
        $title = !empty($instance['title']) ? $instance['title'] : __('Contact Information', 'amsva-contact-info-widget');
        $name = !empty($instance['name']) ? $instance['name'] : '';
        $position = !empty($instance['position']) ? $instance['position'] : '';
        $company = !empty($instance['company']) ? $instance['company'] : '';
        $address = !empty($instance['address']) ? $instance['address'] : '';
        $phone = !empty($instance['phone']) ? $instance['phone'] : '';
        $email = !empty($instance['email']) ? $instance['email'] : '';

        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'amsva-contact-info-widget'); ?></label> 
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
                   type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('name')); ?>"><?php esc_html_e('Name:', 'amsva-contact-info-widget'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('name')); ?>" name="<?php echo esc_attr($this->get_field_name('name')); ?>" type="text" value="<?php echo esc_attr($name); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('position')); ?>"><?php esc_html_e('Position:', 'amsva-contact-info-widget'); ?></label> 
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('position')); ?>" name="<?php echo esc_attr($this->get_field_name('position')); ?>" type="text" value="<?php echo esc_attr($position); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('company')); ?>"><?php esc_html_e('Company Name:', 'amsva-contact-info-widget'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('company')); ?>" name="<?php echo esc_attr($this->get_field_name('company')); ?>" type="text" value="<?php echo esc_attr($company); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('address')); ?>"><?php esc_html_e('Address:', 'amsva-contact-info-widget'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('address')); ?>" name="<?php echo esc_attr($this->get_field_name('address')); ?>" type="text" value="<?php echo esc_attr($address); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('phone')); ?>"><?php esc_html_e('Phone No:', 'amsva-contact-info-widget'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('phone')); ?>" name="<?php echo esc_attr($this->get_field_name('phone')); ?>" type="text" value="<?php echo esc_attr($phone); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('email')); ?>"><?php esc_html_e('Email Id:', 'amsva-contact-info-widget'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('email')); ?>" name="<?php echo esc_attr($this->get_field_name('email')); ?>" type="text" value="<?php echo esc_attr($email); ?>">
        </p>
        <?php
    }

    /**
     * Sanitize and save widget form values.
     *
     * @param array $new_instance New values.
     * @param array $old_instance Old values.
     * @return array Updated values.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? wp_strip_all_tags($new_instance['title']) : '';
        $instance['name'] = (!empty($new_instance['name'])) ? wp_strip_all_tags($new_instance['name']) : '';
        $instance['position'] = (!empty($new_instance['position'])) ? wp_strip_all_tags($new_instance['position']) : '';
        $instance['company'] = (!empty($new_instance['company'])) ? wp_strip_all_tags($new_instance['company']) : '';
        $instance['address'] = (!empty($new_instance['address'])) ? wp_strip_all_tags($new_instance['address']) : '';
        $instance['phone'] = (!empty($new_instance['phone'])) ? wp_strip_all_tags($new_instance['phone']) : '';
        $instance['email'] = (!empty($new_instance['email'])) ? sanitize_email($new_instance['email']) : '';

        return $instance;
    }
}


