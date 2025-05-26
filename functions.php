<?php
/**
 * Sina Amiri Theme Functions
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Theme Setup
 */
function sina_amiri_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'sina-amiri'),
    ));

    // Image sizes
    add_image_size('portfolio-thumbnail', 600, 338, true);
}
add_action('after_setup_theme', 'sina_amiri_theme_setup');

/**
 * Register Custom Post Types
 */
function sina_amiri_register_cpt() {
    // Portfolio CPT
    register_post_type('portfolio', array(
        'labels' => array(
            'name' => __('Portfolio', 'sina-amiri'),
            'singular_name' => __('Portfolio Item', 'sina-amiri'),
            'add_new_item' => __('Add New Portfolio Item', 'sina-amiri'),
            'edit_item' => __('Edit Portfolio Item', 'sina-amiri'),
            'view_item' => __('View Portfolio Item', 'sina-amiri'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-portfolio',
        'rewrite' => array('slug' => 'portfolio'),
        'show_in_rest' => true,
    ));

    // Testimonials CPT
    register_post_type('testimonial', array(
        'labels' => array(
            'name' => __('Testimonials', 'sina-amiri'),
            'singular_name' => __('Testimonial', 'sina-amiri'),
        ),
        'public' => true,
        'supports' => array('title', 'editor','thumbnail'),
        'menu_icon' => 'dashicons-testimonial',
        'show_in_rest' => true,
    ));
}
add_action('init', 'sina_amiri_register_cpt');

/**
 * Theme Customizer
 */
function sina_amiri_customize_register($wp_customize) {


    // Pricing Section
$wp_customize->add_section('sina_amiri_pricing', array(
    'title' => __('Pricing Section', 'sina-amiri'),
    'priority' => 45,
));

// Pricing Background Color
$wp_customize->add_setting('pricing_bg_color', array(
    'default' => '#f9f9f9',
    'sanitize_callback' => 'sanitize_hex_color',
));
$wp_customize->add_control(new WP_Customize_Color_Control(
    $wp_customize,
    'pricing_bg_color',
    array(
        'label' => __('Pricing Background Color', 'sina-amiri'),
        'section' => 'sina_amiri_pricing',
    )
));



    // Colors Section
    $wp_customize->add_section('sina_amiri_colors', array(
        'title' => __('Theme Colors', 'sina-amiri'),
        'priority' => 30,
    ));

    // Primary Color
    $wp_customize->add_setting('primary_color', array(
        'default' => '#2d2d2d',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'primary_color',
        array(
            'label' => __('Primary Color', 'sina-amiri'),
            'section' => 'sina_amiri_colors',
        )
    ));

    // Header Background
    $wp_customize->add_setting('header_bg', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'header_bg',
        array(
            'label' => __('Header Background', 'sina-amiri'),
            'section' => 'sina_amiri_colors',
        )
    ));

    // Typography Section
    $wp_customize->add_section('sina_amiri_typography', array(
        'title' => __('Typography', 'sina-amiri'),
        'priority' => 35,
    ));

    // Body Font
    $wp_customize->add_setting('body_font', array(
        'default' => 'Arial, sans-serif',
        'sanitize_callback' => 'sina_amiri_sanitize_font',
    ));
    $wp_customize->add_control('body_font', array(
        'label' => __('Body Font', 'sina-amiri'),
        'section' => 'sina_amiri_typography',
        'type' => 'select',
        'choices' => array(
            'Arial, sans-serif' => 'Arial',
            'Helvetica, sans-serif' => 'Helvetica',
            'Georgia, serif' => 'Georgia',
            'Times New Roman, serif' => 'Times New Roman',
            'system-ui, sans-serif' => 'System UI',
        )
    ));

    // Layout Section
    $wp_customize->add_section('sina_amiri_layout', array(
        'title' => __('Layout', 'sina-amiri'),
        'priority' => 40,
    ));

    // Container Width
    $wp_customize->add_setting('container_width', array(
        'default' => '1200px',
        'sanitize_callback' => 'sina_amiri_sanitize_container_width',
    ));
    $wp_customize->add_control('container_width', array(
        'label' => __('Container Width', 'sina-amiri'),
        'section' => 'sina_amiri_layout',
        'type' => 'text',
    ));

    // Header/Footer Section
    $wp_customize->add_section('sina_amiri_header_footer', array(
        'title' => __('Header/Footer', 'sina-amiri'),
        'priority' => 50,
    ));

    // Header Alignment
    $wp_customize->add_setting('header_alignment', array(
        'default' => 'space-between',
        'sanitize_callback' => 'sina_amiri_sanitize_alignment',
    ));
    $wp_customize->add_control('header_alignment', array(
        'label' => __('Header Alignment', 'sina-amiri'),
        'section' => 'sina_amiri_header_footer',
        'type' => 'select',
        'choices' => array(
            'space-between' => __('Space Between', 'sina-amiri'),
            'center' => __('Center', 'sina-amiri'),
            'flex-start' => __('Left', 'sina-amiri'),
        )
    ));

    // Footer Content
    $wp_customize->add_setting('footer_content', array(
        'default' => '© ' . date('Y') . ' ' . get_bloginfo('name'),
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('footer_content', array(
        'label' => __('Footer Content', 'sina-amiri'),
        'section' => 'sina_amiri_header_footer',
        'type' => 'textarea',
    ));

    // Custom Logo
    $wp_customize->add_setting('custom_logo');
    $wp_customize->add_control(new WP_Customize_Image_Control(
        $wp_customize,
        'custom_logo',
        array(
            'label' => __('Upload Logo', 'sina-amiri'),
            'section' => 'sina_amiri_header_footer',
        )
    ));
}
add_action('customize_register', 'sina_amiri_customize_register');

/**
 * Sanitization Functions
 */
function sina_amiri_sanitize_font($input) {
    $valid = array(
        'Arial, sans-serif',
        'Helvetica, sans-serif',
        'Georgia, serif',
        'Times New Roman, serif',
        'system-ui, sans-serif',
    );
    return in_array($input, $valid) ? $input : 'Arial, sans-serif';
}

function sina_amiri_sanitize_container_width($input) {
    return preg_match('/^\d+(px|%|em|rem)$/', $input) ? $input : '1200px';
}

function sina_amiri_sanitize_alignment($input) {
    $valid = array('space-between', 'center', 'flex-start');
    return in_array($input, $valid) ? $input : 'space-between';
}

/**
 * Enqueue Scripts and Styles
 */
function sina_amiri_enqueue_assets() {

    



    // Main stylesheet
    wp_enqueue_style(
        'sina-amiri-style',
        get_stylesheet_uri(),
        array(),
        filemtime(get_stylesheet_directory() . '/style.css')
    );

    // Custom scripts
    wp_enqueue_script(
        'sina-amiri-scripts',
        get_template_directory_uri() . '/js/main.js',
        array(),
        filemtime(get_template_directory() . '/js/main.js'),
        true
    );

    // Customizer styles
    $custom_css = sina_amiri_generate_custom_css();
    wp_add_inline_style('sina-amiri-style', $custom_css);

    // Localize script with ajaxurl
    wp_localize_script('sina-amiri-scripts', 'sinaAmiri', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('sina_amiri_ajax_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'sina_amiri_enqueue_assets');

/**
 * Generate Custom CSS from Customizer
 */
function sina_amiri_generate_custom_css() {
    return "
        :root {
            --primary-color: " . get_theme_mod('primary_color', '#2d2d2d') . ";
            --header-bg: " . get_theme_mod('header_bg', '#ffffff') . ";
            --body-font: " . get_theme_mod('body_font', 'Arial, sans-serif') . ";
            --pricing-bg: " . get_theme_mod('pricing_bg_color', '#f9f9f9') . ";
        }
        .minimal-header {
            justify-content: " . get_theme_mod('header_alignment', 'space-between') . ";
        }
        .container {
            max-width: " . get_theme_mod('container_width', '1200px') . ";
        }
    ";
}

/**
 * Portfolio Taxonomy Setup
 */
function register_portfolio_taxonomy() {
    register_taxonomy(
        'portfolio_category',
        'portfolio',
        array(
            'label' => __('Portfolio Categories'),
            'rewrite' => array('slug' => 'portfolio-category'),
            'hierarchical' => true,
            'show_in_rest' => true,
        )
    );
}
add_action('init', 'register_portfolio_taxonomy');


/*   newly added editor to description     */
// Enqueue scripts and styles for term edit screen
function enqueue_term_editor_scripts($hook) {
    if ('term.php' !== $hook && 'edit-tags.php' !== $hook) {
        return;
    }

    // Check if editing 'portfolio_category' taxonomy
    $screen = get_current_screen();
    if (isset($screen->taxonomy) && $screen->taxonomy === 'portfolio_category') {
        // Enqueue WordPress editor scripts and styles
        wp_enqueue_media();
        wp_enqueue_editor();
        wp_enqueue_script('jquery');
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
    }
}
add_action('admin_enqueue_scripts', 'enqueue_term_editor_scripts');



/**
 * Portfolio Category Fields
 */
function portfolio_category_fields($term) {
    $youtube_id = get_term_meta($term->term_id, 'youtube_video_id', true);
    $drive_id = get_term_meta($term->term_id, 'drive_video_id', true);
    $thumbnail_url = get_term_meta($term->term_id, 'category_thumbnail', true);
    $video_description = get_term_meta($term->term_id, 'video_description', true);
    ?>
    <tr class="form-field term-group">
        <th scope="row">
            <label for="youtube-video-id"><?php _e('YouTube Video ID'); ?></label>
        </th>
        <td>
            <input type="text" id="youtube-video-id" name="youtube_video_id" 
                   value="<?php echo esc_attr($youtube_id); ?>" 
                   style="width:95%">
            <p class="description"><?php _e('Enter YouTube Video ID (e.g. dQw4w9WgXcQ)'); ?></p>
        </td>
    </tr>


    <tr class="form-field term-group">
        <th scope="row">
            <label for="drive-video-id"><?php _e('Google Drive Video ID'); ?></label>
        </th>
        <td>
            <input type="text" id="drive-video-id" name="drive_video_id" 
                   value="<?php echo esc_attr($drive_id); ?>" 
                   style="width:95%">
            <p class="description"><?php _e('Enter Google Drive Video ID (e.g. dQw4w9WgXcQ)'); ?></p>
        </td>
    </tr>


    <tr class="form-field term-group">
        <th scope="row">
            <label for="video-description"><?php _e('Video Description'); ?></label>
        </th>
        <td>
            <?php
            // Settings for the editor
            $settings = array(
                'textarea_name' => 'video_description',
                'editor_class'  => 'wp-editor-area',
                'media_buttons' => true,
                'textarea_rows' => 10,
                'tinymce'       => array(
                    'wp_autoresize_on' => true,
                ),
                'quicktags'     => true
            );

            // Output the editor
            wp_editor(wp_kses_post($video_description), 'video_description_editor', $settings);
            ?>
        </td>
    </tr>
    <tr class="form-field term-group">
        <th scope="row">
            <label for="category-thumbnail"><?php _e('Thumbnail URL'); ?></label>
        </th>
        <td>
            <input type="url" id="category-thumbnail" name="category_thumbnail" 
                   value="<?php echo esc_url($thumbnail_url); ?>" 
                   style="width:95%">
            <p class="description"><?php _e('Enter image URL for category thumbnail'); ?></p>
        </td>
    </tr>
    <?php
}
add_action('portfolio_category_edit_form_fields', 'portfolio_category_fields');

function save_portfolio_category_fields($term_id) {
    if(isset($_POST['youtube_video_id'])) {
        update_term_meta($term_id, 'youtube_video_id', sanitize_text_field($_POST['youtube_video_id']));
    }
    if(isset($_POST['drive_video_id'])) {
        update_term_meta($term_id, 'drive_video_id', sanitize_text_field($_POST['drive_video_id']));
    }
    if (isset($_POST['video_description'])) {
        update_term_meta($term_id, 'video_description', wp_kses_post($_POST['video_description']));
    }
    if(isset($_POST['category_thumbnail'])) {
        update_term_meta($term_id, 'category_thumbnail', esc_url_raw($_POST['category_thumbnail']));
    }
}
add_action('edited_portfolio_category', 'save_portfolio_category_fields');

/**
 * Gutenberg Support
 */
function sina_amiri_gutenberg_setup() {
    add_theme_support('editor-styles');
    add_editor_style('css/editor-styles.css');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    
    add_theme_support('editor-color-palette', array(
        array(
            'name' => __('Primary', 'sina-amiri'),
            'slug' => 'primary',
            'color' => get_theme_mod('primary_color', '#2d2d2d'),
        ),
        array(
            'name' => __('White', 'sina-amiri'),
            'slug' => 'white',
            'color' => '#ffffff',
        ),
        array(
            'name' => __('Black', 'sina-amiri'),
            'slug' => 'black',
            'color' => '#000000',
        ),
    ));
}
add_action('after_setup_theme', 'sina_amiri_gutenberg_setup');



// Add fields to the post edit screen
function sina_amiri_add_video_fields() {
    add_meta_box(
        'sina_amiri_video_fields',
        __('Video Settings', 'sina-amiri'),
        'sina_amiri_video_fields_callback',
        array('portfolio', 'testimonial'), // Add to both portfolio and testimonial CPTs
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sina_amiri_add_video_fields');

// Callback function to display the fields
function sina_amiri_video_fields_callback($post) {
    wp_nonce_field('sina_amiri_video_nonce', 'sina_amiri_video_nonce_field');
    
    // Get existing video IDs
    $youtube_video_id = get_post_meta($post->ID, '_sina_amiri_youtube_video_id', true);
    $google_drive_video_id = get_post_meta($post->ID, '_sina_amiri_google_drive_video_id', true);
    
    echo '<label for="sina_amiri_youtube_video_id">' . __('YouTube Video ID', 'sina-amiri') . '</label>';
    echo '<input type="text" id="sina_amiri_youtube_video_id" name="sina_amiri_youtube_video_id" value="' . esc_attr($youtube_video_id) . '" style="width:100%;">';
    
    echo '<br><br>';
    
    echo '<label for="sina_amiri_google_drive_video_id">' . __('Google Drive Video ID', 'sina-amiri') . '</label>';
    echo '<input type="text" id="sina_amiri_google_drive_video_id" name="sina_amiri_google_drive_video_id" value="' . esc_attr($google_drive_video_id) . '" style="width:100%;">';
}

// Save the video ID data when the post is saved
function sina_amiri_save_video_fields($post_id) {
    if (!isset($_POST['sina_amiri_video_nonce_field']) || !wp_verify_nonce($_POST['sina_amiri_video_nonce_field'], 'sina_amiri_video_nonce')) {
        return;
    }
    
    if (isset($_POST['sina_amiri_youtube_video_id'])) {
        update_post_meta($post_id, '_sina_amiri_youtube_video_id', sanitize_text_field($_POST['sina_amiri_youtube_video_id']));
    }
    
    if (isset($_POST['sina_amiri_google_drive_video_id'])) {
        update_post_meta($post_id, '_sina_amiri_google_drive_video_id', sanitize_text_field($_POST['sina_amiri_google_drive_video_id']));
    }
}
add_action('save_post', 'sina_amiri_save_video_fields');




/**
 * Script Deferral
 
function defer_all_scripts($tag, $handle) {
    $excluded_scripts = ['jquery', 'admin-bar'];
    if (!in_array($handle, $excluded_scripts)) {
        return str_replace(' src=', ' defer="defer" src=', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'defer_all_scripts', 10, 2);

*/



function create_blog_page() {
    // Check if the page already exists
    $page = get_page_by_title( 'Blog' );
    if ( ! $page ) {
        // Create the page
        $page_id = wp_insert_post( array(
            'post_title' => 'Blog',
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'page'
        ) );
        // Set the template
        update_post_meta( $page_id, '_wp_page_template', 'blog.php' );
    } else {
        $page_id = $page->ID;
        // Check if the template is already set to blog.php
        $current_template = get_post_meta( $page_id, '_wp_page_template', true );
        if ( $current_template != 'blog.php' ) {
            update_post_meta( $page_id, '_wp_page_template', 'blog.php' );
        }
    }
    // Set as posts page
    update_option( 'page_for_posts', $page_id );
}
add_action( 'init', 'create_blog_page' );

/**
 * Pricing Section Custom Post Type
 */
function sina_amiri_register_pricing_cpt() {
    register_post_type('pricing_plan',
        array(
            'labels'      => array(
                'name'          => __('Pricing Plans', 'sina-amiri'),
                'singular_name' => __('Pricing Plan', 'sina-amiri'),
            ),
            'public'      => true,
            'has_archive' => true,
            'supports'    => array('title', 'editor', 'thumbnail', 'excerpt'),
            'menu_icon'   => 'dashicons-money-alt',
            'show_in_rest' => true,
            'rewrite'     => array('slug' => 'plans'),
        )
    );
}
add_action('init', 'sina_amiri_register_pricing_cpt');

function sina_amiri_pricing_seo() {
    add_post_type_support('pricing_plan', array(
        'yoast-seo',
        'title-tag',
        'meta-description'
    ));
}
add_action('init', 'sina_amiri_pricing_seo');

/**
 * Form Submissions CPT
 */
function sina_amiri_register_form_submissions_cpt() {
    register_post_type('form_submission',
        array(
            'labels'      => array(
                'name'          => __('Form Submissions', 'sina-amiri'),
                'singular_name' => __('Form Submission', 'sina-amiri'),
            ),
            'public'      => false,
            'show_ui'     => true,
            'supports'    => array('title'),
            'menu_icon'   => 'dashicons-email-alt',
        )
    );
}
add_action('init', 'sina_amiri_register_form_submissions_cpt');

/**
 * Pricing Plan Meta Boxes
 */
function sina_amiri_add_pricing_meta_boxes() {
    add_meta_box(
        'pricing_details',
        __('Plan Details', 'sina-amiri'),
        'sina_amiri_pricing_meta_callback',
        'pricing_plan',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sina_amiri_add_pricing_meta_boxes');

function sina_amiri_pricing_meta_callback($post) {
    wp_nonce_field('sina_amiri_pricing_nonce', 'pricing_nonce');
    
    $price_durations = get_post_meta($post->ID, '_price_durations', true);
    $features = get_post_meta($post->ID, '_features', true);
    $button_text = get_post_meta($post->ID, '_button_text', true);




    $youtube_example = get_post_meta($post->ID, '_youtube_example_id', true);
    $drive_example = get_post_meta($post->ID, '_drive_example_id', true);
    
    echo '<label>YouTube Example Video ID</label>';
    echo '<input type="text" name="youtube_example" value="'.esc_attr($youtube_example).'">';
    
    echo '<label>Google Drive Example Video ID</label>';
    echo '<input type="text" name="drive_example" value="'.esc_attr($drive_example).'">';








    echo '<div class="price-duration-repeater">';
    echo '<div class="repeater-items">';
    
    if(is_array($price_durations)) {
        foreach($price_durations as $index => $pd) {
            echo '<div class="repeater-item">';
            echo '<input type="text" name="price_durations['.$index.'][duration]" 
                    placeholder="Duration" value="'.esc_attr($pd['duration']).'">';
            echo '<input type="text" name="price_durations['.$index.'][price]" 
                    placeholder="Price" value="'.esc_attr($pd['price']).'">';
            echo '<button class="remove-row">Remove</button>';
            echo '</div>';
        }
    }
    
    echo '</div>';
    echo '<button class="add-row">Add Duration-Price</button>';
    echo '</div>';

    echo '<label>'.__('Features (one per line)', 'sina-amiri').'</label>';
    echo '<textarea name="features" rows="5">'.esc_textarea($features).'</textarea>';
    
    echo '<label>'.__('Button Text', 'sina-amiri').'</label>';
    echo '<input type="text" name="button_text" value="'.esc_attr($button_text).'">';
    
    // Add JavaScript for repeater
    echo '<script>
    jQuery(document).ready(function($) {
        $(".add-row").click(function(e) {
            e.preventDefault();
            var index = $(".repeater-item").length;
            $(".repeater-items").append(`
                <div class="repeater-item">
                    <input type="text" name="price_durations[${index}][duration]" placeholder="Duration">
                    <input type="text" name="price_durations[${index}][price]" placeholder="Price">
                    <button class="remove-row">Remove</button>
                </div>
            `);
        });
        
        $(".repeater-items").on("click", ".remove-row", function(e) {
            e.preventDefault();
            $(this).parent().remove();
        });
    });
    </script>';
}

function sina_amiri_save_pricing_meta($post_id) {
    if (!isset($_POST['pricing_nonce']) || !wp_verify_nonce($_POST['pricing_nonce'], 'sina_amiri_pricing_nonce')) return;

    update_post_meta($post_id, '_price_durations', array_map(function($pd) {
        return [
            'duration' => sanitize_text_field($pd['duration']),
            'price' => sanitize_text_field($pd['price'])
        ];
    }, $_POST['price_durations'] ?? []));

    update_post_meta($post_id, '_features', sanitize_textarea_field($_POST['features']));
    update_post_meta($post_id, '_button_text', sanitize_text_field($_POST['button_text']));


    update_post_meta($post_id, '_youtube_example_id', sanitize_text_field($_POST['youtube_example']));
    update_post_meta($post_id, '_drive_example_id', sanitize_text_field($_POST['drive_example']));
}
add_action('save_post', 'sina_amiri_save_pricing_meta');

function sina_amiri_register_pricing_taxonomy() {
    register_taxonomy('plan_type', 'pricing_plan', array(
        'labels' => array(
            'name' => 'Plan Types',
            'singular_name' => 'Plan Type'
        ),
        'hierarchical' => true,
        'show_admin_column' => true
    ));
}
add_action('init', 'sina_amiri_register_pricing_taxonomy');


// Add admin columns for pricing plans
function sina_amiri_pricing_columns($columns) {
    return array(
        'cb' => $columns['cb'],
        'title' => __('Plan Name'),
        'price_range' => __('Price Range'),
        'plan_type' => __('Plan Type'),
        'date' => __('Date')
    );
}
add_filter('manage_pricing_plan_posts_columns', 'sina_amiri_pricing_columns');

function sina_amiri_pricing_column_data($column, $post_id) {
    switch ($column) {
        case 'price_range':
            $prices = get_post_meta($post_id, '_price_durations', true);
            if(!empty($prices)) {
                $min = min(array_column($prices, 'price'));
                $max = max(array_column($prices, 'price'));
                echo esc_html("$min - $max");
            }
            break;
            
        case 'plan_type':
            $terms = get_the_terms($post_id, 'plan_type');
            if($terms) {
                echo esc_html(join(', ', wp_list_pluck($terms, 'name')));
            }
            break;
    }
}
add_action('manage_pricing_plan_posts_custom_column', 'sina_amiri_pricing_column_data', 10, 2);

/**
 * Form Handling
 */
function sina_amiri_handle_form_submission() {
    if (!isset($_POST['form_nonce']) || !wp_verify_nonce($_POST['form_nonce'], 'sina_amiri_form_nonce')) {
        wp_send_json_error(__('Invalid request', 'sina-amiri'));
    }

    $submission_data = array(
        'name' => sanitize_text_field($_POST['name']),
        'email' => sanitize_email($_POST['email']),
        'phone' => sanitize_text_field($_POST['phone']),
        'duration' => sanitize_text_field($_POST['duration']),
        'price' => sanitize_text_field($_POST['price']),
        'message' => sanitize_textarea_field($_POST['message']),
        'plan' => sanitize_text_field($_POST['plan']),
        'plan_type' => sanitize_text_field($_POST['plan_type'])
    );

    // Create submission post
    $post_id = wp_insert_post(array(
        'post_title'  => $submission_data['name'] . ' - ' . $submission_data['plan'],
        'post_type'   => 'form_submission',
        'post_status' => 'private'
    ));

    if ($post_id) {
        // Save meta fields
        foreach ($submission_data as $key => $value) {
            update_post_meta($post_id, '_' . $key, $value);
        }

        // Send email
        $to = get_option('admin_email');
        $subject = __('New Form Submission - ', 'sina-amiri') . $submission_data['plan'];
        $message = "New contact form submission:\n\n";
        $message .= "Plan Type: " . $submission_data['plan_type'] . "\n";
        foreach ($submission_data as $key => $value) {
            $message .= ucfirst($key) . ": $value\n";
        }
        
        wp_mail($to, $subject, $message);

        wp_send_json_success(__('Message sent successfully!', 'sina-amiri'));
    } else {
        wp_send_json_error(__('Failed to save submission.', 'sina-amiri'));
    }
}
add_action('wp_ajax_submit_contact_form', 'sina_amiri_handle_form_submission');
add_action('wp_ajax_nopriv_submit_contact_form', 'sina_amiri_handle_form_submission');


/**
 * Form Submissions Admin Columns
 */
// Define columns
function sina_amiri_form_submission_columns($columns) {
    $new_columns = array(
        'cb' => $columns['cb'],
        'title' => __('Name'),
        'email' => __('Email'),
        'phone' => __('Phone'),
        'plan' => __('Selected Plan'),
        'duration' => __('Duration'),
        'price' => __('Price'),
        'message' => __('Message'),
        'date' => __('Date'),
        'plan_type' => __('Plan Type')
    );
    return $new_columns;
}
add_filter('manage_form_submission_posts_columns', 'sina_amiri_form_submission_columns');

// Populate column data
function sina_amiri_form_submission_column_data($column, $post_id) {
    switch ($column) {
        case 'plan_type':
            echo esc_html(get_post_meta($post_id, '_plan_type', true));
            break;
        case 'email':
            echo esc_html(get_post_meta($post_id, '_email', true));
            break;
        case 'phone':
            echo esc_html(get_post_meta($post_id, '_phone', true));
            break;
        case 'plan':
            echo esc_html(get_post_meta($post_id, '_plan', true));
            break;
        case 'duration':
            $duration = get_post_meta($post_id, '_duration', true);
            echo esc_html($duration === 'unknown' ? 'Unknown' : $duration);
            break;
        case 'price':
            echo esc_html(get_post_meta($post_id, '_price', true));
            break;
        case 'message':
            echo esc_textarea(get_post_meta($post_id, '_message', true));
            break;
    }
}
add_action('manage_form_submission_posts_custom_column', 'sina_amiri_form_submission_column_data', 10, 2);

// Make columns sortable
add_filter('manage_edit-form_submission_sortable_columns', 'sina_amiri_sortable_form_columns');
function sina_amiri_sortable_form_columns($columns) {
    $columns['email'] = 'email';
    $columns['plan'] = 'plan';
    return $columns;
}


// front end client panel


// Make sure WP loads jQuery on our front-end dashboard
add_action( 'wp_enqueue_scripts', 'amiri_enqueue_jquery' );
function amiri_enqueue_jquery() {
    if ( is_page( 'dashboard' ) ) {
        wp_enqueue_script( 'jquery' );
    }
}


// 1) point the plugin at our template file
add_filter('pocscd_dashboard_page_template_file', 'amiri_dashboard_template', 10, 2);
function amiri_dashboard_template($template, $dashboard_page_id) {
    // load our custom template in the theme’s /template/ folder
    return get_stylesheet_directory() . '/template-parts/app-dashboard.php';
}

// 2) tell POCS we’ll use a “screen” query-var
add_filter('pocscd_add_page_param', function($params){
    $params[] = 'screen';
    return $params;
}, 5, 1);

// 3) pretty URLs like /dashboard/screen/messages
add_filter('pocscd_create_rewrite_rules','amiri_dashboard_rewrites');
function amiri_dashboard_rewrites(){
    return [
        'screen' => [
            'regex' => '^dashboard/screen/([^/]+)/?$',
            'query' => 'index.php?pagename=dashboard&screen=$matches[1]',
        ],
    ];
}
// only allow access if user is logged in
add_filter('pocscd_lock_page', function(){
    return ! is_user_logged_in();
});




// AJAX handler for sending message
add_action('wp_ajax_amiri_send_message', function(){
    $u          = get_current_user_id();
    $msg        = sanitize_text_field( $_POST['msg'] );
    $project_id = intval( $_POST['project_id'] );

    if ( ! $project_id ) {
      wp_send_json_error( 'Missing project_id' );
    }

    $post_id = wp_insert_post([
      'post_type'    => 'message',
      'post_title'   => 'msg-'.time(),
      'post_content' => $msg,
      'post_status'  => 'publish',
      'post_author'  => $u,
    ]);

    if ( $post_id ) {
      // save the project relationship
      update_post_meta( $post_id, 'project_id', $project_id );

      // notify admin…
      $admin_email = get_option('admin_email');
      wp_mail(
        $admin_email,
        '📩 New message on project #'.$project_id,
        "A new message has been posted. Edit it in WP-Admin: "
        . admin_url("post.php?post={$post_id}&action=edit")
      );

      wp_send_json_success();
    }
    wp_send_json_error();
});

add_action('init', function(){
  $labels = [
    'name'          => 'Messages',
    'singular_name' => 'Message',
    'add_new_item'  => 'Add New Message',
    'edit_item'     => 'Edit Message',
    'parent_item'   => 'Parent Message',
    'parent_item_colon' => 'Parent Message:',
    'menu_name'     => 'Messages',
  ];
  $args = [
    'labels'             => $labels,
    'public'             => false,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'hierarchical'       => true,                // ← enable parent/child :contentReference[oaicite:0]{index=0}
    'supports'           => ['title','editor','author','page-attributes'], 
    'capability_type'    => 'post',
    'capabilities'       => [
      'edit_post'          =>'edit_message',
      'read_post'          =>'read_message',
      'delete_post'        =>'delete_message',
      'edit_posts'         =>'edit_messages',
      'edit_others_posts'  =>'edit_others_messages',
      'publish_posts'      =>'publish_messages',
      'read_private_posts' =>'read_private_messages',
    ],
    'map_meta_cap'       => true,
  ];
  register_post_type('message',$args);
});


// 3a) add the meta box
add_action('add_meta_boxes', function(){
    add_meta_box(
      'message_replies',
      'Replies',
      'render_message_replies_metabox',
      'message',
      'normal',
      'high'
    );
  });
  
  // 3b) render its contents
  function render_message_replies_metabox( $post ){
    // 1) figure out which project this message belongs to
    $project_id = intval( get_post_meta( $post->ID, 'project_id', true ) );

    // 2) fetch all replies that are children of this message AND belong to same project
    $replies = get_posts([
      'post_type'      => 'message',
      'post_parent'    => $post->ID,
      'orderby'        => 'date',
      'order'          => 'ASC',
      'posts_per_page' => -1,
      'meta_query'     => [[
         'key'   => 'project_id',
         'value' => $project_id,
         'type'  => 'NUMERIC',
      ]],
    ]);

    echo '<div style="max-height:200px; overflow:auto;">';
    if ( $replies ) {
      foreach ( $replies as $r ) {
        $author = get_userdata( $r->post_author )->display_name;
        echo "<p><strong>{$author}:</strong> " . esc_html( $r->post_content ) . "</p><hr>";
      }
    } else {
      echo '<p><em>No replies yet for this message.</em></p>';
    }
    echo '</div>';
  
    // reply form
    ?>
    <textarea id="admin-reply" rows="3" style="width:100%"></textarea>
    <button class="button button-primary" id="send-admin-reply">Send Reply</button>
    <script>
    jQuery('#send-admin-reply').on('click', function(){
      jQuery.post( ajaxurl, {
        action:      'admin_send_reply',
        parent_id:   <?php echo $post->ID ?>,
        reply:       jQuery('#admin-reply').val()
      }, function(r){
        if(r.success) location.reload();
        else alert('Error sending reply');
      });
    });
    </script>
    <?php
  }
  
  // 3c) handle the AJAX reply
  add_action('wp_ajax_admin_send_reply', function(){
    if ( ! current_user_can('edit_messages') ) wp_send_json_error();
    $parent = intval($_POST['parent_id']);
    $msg    = sanitize_text_field($_POST['reply']);
    $reply_id = wp_insert_post([
      'post_type'    => 'message',
      'post_parent'  => $parent,
      'post_content' => $msg,
      'post_status'  => 'publish',
      'post_author'  => get_current_user_id()
    ]);
    if($reply_id){
      // notify the original author
      $orig = get_post($parent);
      $user = get_userdata($orig->post_author);
      $proj = get_post_meta( $parent, 'project_id', true );
       update_post_meta( $reply_id, 'project_id', intval($proj) );
      if($user){
        wp_mail(
          $user->user_email,
          'Reply to your message',
          "Your message has a new reply. View it: "
          . get_permalink( pocscd_dashboard_page_id() ) . '?screen=messages'
        );
      }
      wp_send_json_success();
    }
    wp_send_json_error();
  });
  add_filter( 'register_post_type_args', function( $args, $post_type ) {
    if ( $post_type === 'message' ) {
        // ensure the admin UI is generated
        $args['show_ui']           = true;                                // 
        // put it as a top-level menu (you can also use 'edit.php?post_type=dashboard' to nest)
        $args['show_in_menu']      = true;                                // 
        // allow it in the admin-bar “+New” menu
        $args['show_in_admin_bar'] = true;                                // 
        // give it its own menu icon (optional)
        $args['menu_icon']         = 'dashicons-email';                  
    }
    return $args;
}, 20, 2 );


// 2a) tell WP to build message-caps instead of post-caps:
add_filter( 'register_post_type_args', function( $args, $post_type ) {
    if ( $post_type === 'message' ) {
        // use a custom capability type so WP creates message-specific caps
        $args['capability_type'] = 'message';
        // map meta capabilities so WP knows how to check them
        $args['map_meta_cap']    = true;                                 // :contentReference[oaicite:0]{index=0}
    }
    return $args;
}, 20, 2 );

// 2b) give those new caps to the Administrator role
add_action( 'admin_init', function() {
    // the caps that WP will now expect on 'message' CPT
    $caps = [
      'edit_message', 'read_message', 'delete_message',
      'edit_messages', 'edit_others_messages', 'publish_messages',
      'read_private_messages', 'delete_messages', 'delete_others_messages',
      'delete_private_messages', 'delete_published_messages',
      'edit_private_messages', 'edit_published_messages'
    ];
    $role = get_role( 'administrator' );
    foreach ( $caps as $cap ) {
        $role->add_cap( $cap );                                        // :contentReference[oaicite:1]{index=1}
    }
});











// 1) add a “Conversations” sub-page under the Messages CPT menu
add_action('admin_menu', function(){
    add_submenu_page(
        'edit.php?post_type=message',   // parent = Messages CPT menu
        'Conversations',                // page <title>
        'Conversations',                // menu label
        'edit_messages',                // capability required
        'message_conversations',        // menu slug
        'render_message_conversations'  // callback to output the page
    );  // :contentReference[oaicite:0]{index=0}
});

// 2) add a “View Chat” action link on each message row
add_filter('post_row_actions', function($actions, $post){
    if($post->post_type==='message'){
        $user_id = $post->post_author;
        $url = add_query_arg([
            'page'    => 'message_conversations',
            'user_id' => $user_id
        ], admin_url('edit.php?post_type=message'));
        $actions['view_chat'] = '<a href="'.esc_url($url).'">View Chat</a>';
    }
    return $actions;
}, 10, 2);  // :contentReference[oaicite:1]{index=1}

// 3) render the Conversations page
function render_message_conversations(){
    if(! current_user_can('edit_messages') ) wp_die('No permission');

    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
    echo '<div class="wrap"><h1>Conversations</h1>';

    // — 1a) list authors if none selected
    if( ! $user_id ){
        global $wpdb;
        $admin = get_current_user_id();
    
        // get all authors of top-level messages
        $authors = $wpdb->get_col("
          SELECT DISTINCT post_author
            FROM {$wpdb->posts}
           WHERE post_type='message'
             AND post_parent=0
        ");  // :contentReference[oaicite:0]{index=0}
    
        // build array [ user_id => unread_count ]
        $data = [];
        foreach( $authors as $aid ){
          $last = get_last_read( $admin, $aid );
          $unread = count( get_posts([
             'post_type'      => 'message',
             'post_parent'    => 0,
             'author'         => $aid,
             'date_query'     => [['after'=>$last,'inclusive'=>false]],
             'fields'         => 'ids',
             'posts_per_page' => -1
          ]) );
          $data[ $aid ] = $unread;
        }
    
        // sort by unread desc
        arsort( $data );
    
        echo '<ul>';
        foreach( $data as $aid => $unread ){
          if( $unread === 0 ) {
            $badge = '';
          } else {
            $badge = " <span class='awaiting-mod'>($unread)</span>";
          }
          $u   = get_userdata( $aid );
          $url = add_query_arg(
            ['page'=>'message_conversations','user_id'=>$aid],
            admin_url('edit.php?post_type=message')
          );
          echo '<li><a href="'.esc_url($url).'">'
               . esc_html($u->display_name)
               . $badge
               .'</a></li>';
        }
        echo '</ul>';
        return;
    }
    

    // — 1b) user selected: show their chat
    $user = get_userdata($user_id);
    if(!$user){ echo '<p>Invalid user.</p></div>'; return; }
    echo '<h2>Chat with '.esc_html($user->display_name).'</h2>';

    // fetch top-level + replies
    $threads = get_posts([
      'post_type'      => 'message',
      'post_parent'    => 0,
      'author'         => $user_id,
      'orderby'        => 'date',
      'order'          => 'ASC',
      'posts_per_page' => -1,
    ]);
    $ids     = wp_list_pluck($threads,'ID');
    $replies = get_posts([
      'post_type'        => 'message',
      'post_parent__in'  => $ids,
      'orderby'          => 'date',
      'order'            => 'ASC',
      'posts_per_page'   => -1,
    ]);
    $all = array_merge($threads,$replies);
    usort($all, function($a,$b){ return strcmp($a->post_date,$b->post_date); });

    // mark their top-level unread as read now
    foreach($threads as $t){
      delete_post_meta($t->ID,'unread');
    }




    update_user_meta(
        get_current_user_id(),
        'last_read_messages_for_' . $user_id,
        current_time('mysql')
      );
    // render bubbles
    echo '<div style="max-width:600px;">';
    foreach($all as $m){
        $message_author_id = (int) $m->post_author;
        $client_viewed_id = (int) $user_id; // $user_id is the ID of the client whose conversation is being viewed

        if ($message_author_id == $client_viewed_id) {
            $client_info = get_userdata($client_viewed_id);
            $who = $client_info ? esc_html($client_info->display_name) : 'Client'; // Label as Client's name or "Client"
            $bg = '#eef'; // Client's messages background
        } else {
            // Assumed to be an admin reply
            $admin_info = get_userdata($message_author_id);
            $who = $admin_info ? esc_html($admin_info->display_name) : 'Admin'; // Label as Admin's name or "Admin"
            $bg = '#fee'; // Admin's messages background
        }
        echo "<div style=\"background:{$bg};padding:8px;border-radius:4px;margin:8px 0;\">
                <strong>{$who}:</strong> ".esc_html($m->post_content)."
                <br><small>".esc_html($m->post_date)."</small>
              </div>";
    }
    echo '</div>';

    // reply box (same AJAX you already have)
    $last = end(array_filter($all, fn($m)=>$m->post_author==$user_id));
    $pid  = $last ? $last->ID : 0;
    echo '<h2>Send Reply</h2>
          <textarea id="admin-reply" rows="4" style="width:100%;max-width:600px;"></textarea><br>
          <button class="button button-primary" id="send-admin-reply">Send</button>';
    ?>
    <script>
    jQuery('#send-admin-reply').on('click', function(){
      var r = jQuery('#admin-reply').val().trim();
      if(!r){ alert('Type a reply first.'); return; }
      jQuery.post(ajaxurl,{
        action:     'admin_send_reply',
        parent_id:  <?php echo intval($pid)?>,
        reply:      r
      }, res=>{
        res.success? location.reload() : alert('Error');
      });
    });
    </script>
    <?php

    echo '</div>';
}


  // 4a) show user dropdown on message list
add_action('restrict_manage_posts', function(){
    global $typenow;
    if($typenow !== 'message') return;
    wp_dropdown_users([
      'show_option_all' => 'All Users',
      'name'            => 'author',
      'who'             => 'all',
      'selected'        => isset($_GET['author']) ? intval($_GET['author']) : 0
    ]);
  }); 
  
  // 4b) apply it to the query
  add_action('pre_get_posts', function($q){
    if( is_admin() && $q->get('post_type')==='message' && !empty($_GET['author']) ){
      $q->set('author', intval($_GET['author']));
    }
  });
  




add_action('admin_menu', function(){
    add_submenu_page(
      'edit.php?post_type=message',
      'Conversations',
      'Conversations',
      'edit_messages',
      'message_conversations',
      'render_message_conversations'
    );
}, 20);

// 3) — badge the “Messages” admin menu with total unread
// helper: get last-read timestamp for this admin & user
function get_last_read( $admin_id, $user_id ) {
    $ts = get_user_meta( $admin_id, 'last_read_messages_for_' . $user_id, true );
    return $ts ? $ts : '1970-01-01 00:00:00';
}

// 3a) Admin-menu badge: total unread across all users
add_action('admin_menu', function(){
    global $menu;
    $admin    = get_current_user_id();
    $wpdb     = $GLOBALS['wpdb'];

    // get all authors of top-level messages
    $authors = $wpdb->get_col("
      SELECT DISTINCT post_author
        FROM {$wpdb->posts}
       WHERE post_type='message'
         AND post_parent=0
    ");

    $total = 0;
    foreach( $authors as $aid ) {
      $last = get_last_read( $admin, $aid );
      $cnt  = count( get_posts([
         'post_type'      => 'message',
         'post_parent'    => 0,
         'author'         => $aid,
         'date_query'     => [['after' => $last, 'inclusive'=>false]],
         'fields'         => 'ids',
         'posts_per_page' => -1,
      ]) );
      $total += $cnt;
    }

    if( $total ){
      foreach( $menu as &$m ){
        if( isset($m[2]) && $m[2] === 'edit.php?post_type=message' ){
          $m[0] .= " <span class='awaiting-mod'>($total)</span>";
          break;
        }
      }
    }
}, 999);




// ── DEBUG 1: show total unread top-level messages ──
add_action('admin_notices', function(){
    if ( ! current_user_can('manage_options') ) return;
    $unread = get_posts([
      'post_type'      => 'message',
      'post_parent'    => 0,
      'meta_key'       => 'unread',
      'meta_value'     => '1',
      'fields'         => 'ids',
      'posts_per_page' => -1,
    ]);
    $count = count( $unread );
    echo '<div class="notice notice-info"><p><strong>DEBUG:</strong> Unread top-level messages = '. $count .'</p></div>';
});

// ── DEBUG 2: show the rows we’ll list as “Conversations” ──
add_action('admin_notices', function(){
    if ( ! current_user_can('manage_options') ) return;
    global $wpdb;
    $rows = $wpdb->get_results("
      SELECT p.post_author AS user_id,
             SUM( CASE WHEN pm.meta_key='unread' THEN 1 ELSE 0 END ) AS unread_count
        FROM {$wpdb->posts} p
   LEFT JOIN {$wpdb->postmeta} pm
          ON pm.post_id = p.ID
       WHERE p.post_type='message'
         AND p.post_parent=0
       GROUP BY p.post_author
       ORDER BY unread_count DESC
    ");
    echo '<div class="notice notice-warning"><p><strong>DEBUG:</strong> Conversation rows:</p><pre>';
    foreach($rows as $r){
        echo " user_id={$r->user_id}  unread_count={$r->unread_count}\n";
    }
    echo '</pre></div>';
});

// DEBUG: show us what WP thinks your "message" CPT flags are
add_action('admin_notices', function(){
    if ( ! current_user_can('manage_options') ) return;
    $pt = get_post_type_object('message');
    if ( ! $pt ) {
        echo '<div class="notice notice-error"><p><strong>Debug:</strong> CPT "message" is not registered at all.</p></div>';
    } else {
        echo '<div class="notice notice-info"><p><strong>Debug:</strong> '
            . 'show_ui=' . ($pt->show_ui ? 'true' : 'false') . ', '
            . 'show_in_menu=' . ($pt->show_in_menu ? 'true' : 'false') . ', '
            . 'show_in_admin_bar=' . ($pt->show_in_admin_bar ? 'true' : 'false') 
            . '</p></div>';
    }
});


add_action('admin_init', function(){
    $role = get_role('administrator');
    foreach([
      'edit_message','read_message','delete_message',
      'edit_messages','edit_others_messages',
      'publish_messages','read_private_messages'
    ] as $cap){
      $role->add_cap($cap);
    }
  });





add_action('init', function(){
  $c = new POCSCD_CustomPostType();
  $c->set_post_type_slug('style');
  $c->set_post_type_name(['Style','Styles']);
  $c->register_post_type();
});




// 1a) Register Project CPT
add_action('init', function(){
    $labels = [
      'name'               => 'Projects',
      'singular_name'      => 'Project',
      'add_new_item'       => 'Add New Project',
      'edit_item'          => 'Edit Project',
      'view_item'          => 'View Project',
      'all_items'          => 'All Projects',
      'menu_name'          => 'Projects',
    ];
    $args = [
      'labels'             => $labels,
      'public'             => false,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'capability_type'    => 'post',
      'supports'           => ['title','editor','author','custom-fields'],
      'has_archive'        => false,
      'map_meta_cap'       => true,
    ];
    register_post_type('project',$args);
});

// 1b) Register each custom status
add_action('init', function(){
  $statuses = [
    'reviewing'       => 'Reviewing your order',
    'more_info'       => 'Back to you for more info',
    'editing'         => 'Editing',
    'sample_complete' => 'Sample video completed',
    'waiting_review'  => 'Waiting for your review',
    'completed'       => 'Completed',
  ];
  foreach($statuses as $slug => $label){
    register_post_status( $slug, [
      'label'                     => $label,
      'public'                    => true,
      'show_in_admin_all_list'    => true,
      'show_in_admin_status_list' => true,
      'label_count'               => _n_noop("$label <span class='count'>(%s)</span>", "$label <span class='count'>(%s)</span>"),
    ]);
  }
});



// add status dropdown into the “Publish” box on Project CPT
add_action('add_meta_boxes', function(){
    add_meta_box('project_status','Project Status','render_project_status_metabox','project','side', 'high');
  });
  function render_project_status_metabox( $post ){
    $current = $post->post_status;
    $all     = ['reviewing','more_info','editing','sample_complete','waiting_review','completed'];
    wp_nonce_field('save_project_status','project_status_nonce');
    echo '<select name="project_status">';
    foreach($all as $s){
      $o = get_post_status_object($s);
      printf(
        '<option value="%1$s"%2$s>%3$s</option>',
        esc_attr($s),
        selected($s,$current,false),
        esc_html($o->label)
      );
    }
    echo '</select>';
  }
  /**
 * Handles updating the project status when a project post is saved.
 * Prevents infinite loops by unhooking and re-hooking the action.
 */
function amiri_theme_update_project_status( $post_id ) {
    // If this is just a revision, don't send the email.
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }

    // Check if our nonce is set.
    if ( ! isset( $_POST['project_status_nonce'] ) ) {
        return;
    }
    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['project_status_nonce'], 'save_project_status' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'project' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    } else {
        // Not a 'project' post type, so bail.
        return;
    }

    // Check if project_status is set in the submitted data.
    if ( isset( $_POST['project_status'] ) ) {
        $new_status = sanitize_text_field( $_POST['project_status'] );
        $current_status = get_post_status( $post_id );

        // Only update if the status is actually different and is a valid status.
        // This also helps prevent loops if something else tries to update the post with the same status.
        if ( $new_status !== $current_status && get_post_status_object( $new_status ) ) {

            // Unhook this function so it doesn't loop infinitely
            remove_action( 'save_post_project', 'amiri_theme_update_project_status', 10, 1 );

            // Update the post, which might call save_post again (but our function is unhooked)
            wp_update_post( array(
                'ID'            => $post_id,
                'post_status'   => $new_status
            ) );

            // Re-hook this function
            add_action( 'save_post_project', 'amiri_theme_update_project_status', 10, 1 );
        }
    }
}
add_action( 'save_post_project', 'amiri_theme_update_project_status', 10, 1 );

  
  // --- INVOICE FUNCTIONALITY ---

// 4a) Register/Update Invoice CPT
add_action('init', function(){
    $labels = [
        'name'               => __('Invoices', 'sina-amiri'),
        'singular_name'      => __('Invoice', 'sina-amiri'),
        'add_new_item'       => __('Add New Invoice', 'sina-amiri'),
        'edit_item'          => __('Edit Invoice', 'sina-amiri'),
        'view_item'          => __('View Invoice', 'sina-amiri'),
        'all_items'          => __('All Invoices', 'sina-amiri'),
        'menu_name'          => __('Invoices', 'sina-amiri'),
    ];
    register_post_type('invoice', [
      'labels'         => $labels,
      'public'         => false, // Keep false if only viewable via dashboard/admin
      'show_ui'        => true,
      'show_in_menu'   => true, // Or 'edit.php?post_type=project' to nest under Projects
      'supports'       => ['title', 'editor', 'author', 'custom-fields'], // Added 'editor'
      'map_meta_cap'   => true,
      'menu_icon'      => 'dashicons-media-text',
    ]);
});

// 4b) When viewing a Project in admin, add “Create Invoice” button/meta box
add_action('add_meta_boxes_project', function(){
    add_meta_box('project_invoice_metabox', __('Invoice Actions', 'sina-amiri'), 'render_project_invoice_metabox', 'project', 'side', 'high');
});

function render_project_invoice_metabox($post){
    // Check if an invoice already exists for this project
    $existing_invoices = get_posts([
        'post_type'   => 'invoice',
        'meta_key'    => '_project_id',
        'meta_value'  => $post->ID,
        'post_status' => 'any', // Check for any status
        'numberposts' => 1
    ]);

    if($existing_invoices){
        $invoice = $existing_invoices[0];
        echo '<p>'.__('Invoice already created:', 'sina-amiri').' <a href="'.get_edit_post_link($invoice->ID).'">#'.$invoice->ID.' - '.$invoice->post_title.'</a></p>';
        echo '<p><strong>'.__('Status:', 'sina-amiri').'</strong> '. (get_post_meta($invoice->ID, '_paid', true) ? __('Paid', 'sina-amiri') : __('Unpaid', 'sina-amiri')) .'</p>';
        echo '<p><strong>'.__('Amount:', 'sina-amiri').'</strong> $'. esc_html(get_post_meta($invoice->ID, '_amount', true)) .'</p>';
    } else {
        wp_nonce_field('create_invoice_for_project_'.$post->ID, 'invoice_nonce');
        echo '<p><label for="invoice_amount">'.__('Invoice Amount:', 'sina-amiri').'</label><br>';
        echo '<input name="invoice_amount" id="invoice_amount" type="number" step="0.01" required style="width:100%;"></p>';
        echo '<button type="submit" name="create_invoice_submit" value="1" class="button button-primary">'.__('Create Invoice', 'sina-amiri').'</button>';
        echo '<p class="description">'.__('Clicking "Create Invoice" will also save/update the project.', 'sina-amiri').'</p>';
    }
}

// 4c) Handle Invoice Creation from Project Edit Screen (during project save/update)
add_action('save_post_project', function($post_id){
    // Check if our "Create Invoice" button was clicked and nonce is valid
    if (empty($_POST['create_invoice_submit']) || !isset($_POST['invoice_nonce']) || !wp_verify_nonce($_POST['invoice_nonce'], 'create_invoice_for_project_'.$post_id)) {
        return;
    }

    // Don't create if autosaving, or if it's a revision
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;

    // Check if an invoice already exists to prevent duplicates
    $existing_invoices = get_posts([
        'post_type'   => 'invoice',
        'meta_key'    => '_project_id',
        'meta_value'  => $post_id,
        'post_status' => 'any',
        'numberposts' => 1
    ]);
    if ($existing_invoices) {
        // Optionally, add an admin notice that an invoice already exists
        return;
    }

    $project_post = get_post($post_id);
    $project_author_id = $project_post->post_author;
    $project_title = $project_post->post_title;
    $client_info = get_userdata($project_author_id);
    $client_name = $client_info ? $client_info->display_name : __('N/A', 'sina-amiri');
    $client_email = $client_info ? $client_info->user_email : __('N/A', 'sina-amiri');

    $invoice_amount = isset($_POST['invoice_amount']) ? floatval(sanitize_text_field($_POST['invoice_amount'])) : 0;

    if ($invoice_amount <= 0) {
        // Optionally, set an admin notice that amount is required
        return;
    }

    $invoice_id = wp_insert_post([
        'post_type'   => 'invoice',
        'post_title'  => sprintf(__('Invoice for %s', 'sina-amiri'), $project_title),
        'post_status' => 'publish', // Or 'draft' if you want admins to review first
        'post_author' => $project_author_id, // Assign to the client for dashboard visibility
        'post_content'=> sprintf(__('Invoice details for project: %s.', 'sina-amiri'), $project_title), // Default content
    ]);

    if ($invoice_id && !is_wp_error($invoice_id)) {
        update_post_meta($invoice_id, '_project_id', $post_id);
        update_post_meta($invoice_id, '_amount', $invoice_amount);
        update_post_meta($invoice_id, '_paid', 0); // 0 for unpaid, 1 for paid
        update_post_meta($invoice_id, '_invoice_date', current_time('mysql'));
        // You can add a due date, e.g., 30 days from now:
        // update_post_meta($invoice_id, '_due_date', date('Y-m-d H:i:s', strtotime('+30 days', current_time('timestamp'))));
        update_post_meta($invoice_id, '_client_name', $client_name);
        update_post_meta($invoice_id, '_client_email', $client_email);

        // Optional: Add an admin notice for success
        add_action('admin_notices', function() use ($invoice_id) {
            echo '<div class="notice notice-success is-dismissible"><p>'.sprintf(__('Invoice #%d created successfully. <a href="%s">Edit Invoice</a>', 'sina-amiri'), $invoice_id, get_edit_post_link($invoice_id)).'</p></div>';
        });
    } else {
        // Optional: Add an admin notice for failure
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error is-dismissible"><p>'.__('Failed to create invoice.', 'sina-amiri').'</p></div>';
        });
    }
}, 10, 1); // Priority 10, 1 argument ($post_id)

// 4d) Add Meta Box to Invoice CPT for detailed information
add_action('add_meta_boxes_invoice', function() {
    add_meta_box(
        'invoice_details_metabox',
        __('Invoice Details', 'sina-amiri'),
        'render_invoice_details_metabox',
        'invoice',
        'normal', // 'normal' for main column, 'side' for sidebar
        'high'
    );
});

function render_invoice_details_metabox($post) {
    wp_nonce_field('save_invoice_details_nonce_'.$post->ID, 'invoice_details_nonce');

    $project_id    = get_post_meta($post->ID, '_project_id', true);
    $amount        = get_post_meta($post->ID, '_amount', true);
    $is_paid       = get_post_meta($post->ID, '_paid', true);
    $invoice_date  = get_post_meta($post->ID, '_invoice_date', true);
    $due_date      = get_post_meta($post->ID, '_due_date', true);
    $client_name   = get_post_meta($post->ID, '_client_name', true);
    $client_email  = get_post_meta($post->ID, '_client_email', true);

    ?>
    <table class="form-table">
        <tbody>
            <tr>
                <th><label for="invoice_project_id"><?php _e('Related Project:', 'sina-amiri'); ?></label></th>
                <td>
                    <?php if ($project_id && get_post($project_id)): ?>
                        <a href="<?php echo get_edit_post_link($project_id); ?>"><?php echo get_the_title($project_id); ?> (ID: <?php echo $project_id; ?>)</a>
                        <input type="hidden" name="invoice_project_id" value="<?php echo esc_attr($project_id); ?>">
                    <?php else: ?>
                        <input type="number" id="invoice_project_id" name="invoice_project_id" value="<?php echo esc_attr($project_id); ?>" placeholder="<?php _e('Enter Project ID if unlinked', 'sina-amiri'); ?>">
                        <p class="description"><?php _e('Manually link to a project if needed.', 'sina-amiri'); ?></p>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th><label for="invoice_client_name"><?php _e('Client Name:', 'sina-amiri'); ?></label></th>
                <td><input type="text" id="invoice_client_name" name="invoice_client_name" value="<?php echo esc_attr($client_name); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="invoice_client_email"><?php _e('Client Email:', 'sina-amiri'); ?></label></th>
                <td><input type="email" id="invoice_client_email" name="invoice_client_email" value="<?php echo esc_attr($client_email); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="invoice_amount_field"><?php _e('Amount ($):', 'sina-amiri'); ?></label></th>
                <td><input type="number" step="0.01" id="invoice_amount_field" name="invoice_amount_field" value="<?php echo esc_attr($amount); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="invoice_date"><?php _e('Invoice Date:', 'sina-amiri'); ?></label></th>
                <td><input type="datetime-local" id="invoice_date" name="invoice_date" value="<?php echo esc_attr($invoice_date ? date('Y-m-d\TH:i:s', strtotime($invoice_date)) : ''); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="invoice_due_date"><?php _e('Due Date:', 'sina-amiri'); ?></label></th>
                <td><input type="datetime-local" id="invoice_due_date" name="invoice_due_date" value="<?php echo esc_attr($due_date ? date('Y-m-d\TH:i:s', strtotime($due_date)) : ''); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="invoice_status"><?php _e('Status:', 'sina-amiri'); ?></label></th>
                <td>
                    <select id="invoice_status" name="invoice_status">
                        <option value="0" <?php selected($is_paid, 0); ?>><?php _e('Unpaid', 'sina-amiri'); ?></option>
                        <option value="1" <?php selected($is_paid, 1); ?>><?php _e('Paid', 'sina-amiri'); ?></option>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <p><strong><?php _e('Invoice Items/Details:', 'sina-amiri'); ?></strong></p>
    <p><?php _e('Use the main content editor above to add line items, descriptions, terms, or any other information for this invoice.', 'sina-amiri'); ?></p>
    <?php
}

// 4e) Save Invoice Meta Details
add_action('save_post_invoice', function($post_id){
    if (!isset($_POST['invoice_details_nonce']) || !wp_verify_nonce($_POST['invoice_details_nonce'], 'save_invoice_details_nonce_'.$post_id)) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Sanitize and save each field
    if (isset($_POST['invoice_project_id'])) {
        update_post_meta($post_id, '_project_id', intval($_POST['invoice_project_id']));
    }
    if (isset($_POST['invoice_client_name'])) {
        update_post_meta($post_id, '_client_name', sanitize_text_field($_POST['invoice_client_name']));
    }
    if (isset($_POST['invoice_client_email'])) {
        update_post_meta($post_id, '_client_email', sanitize_email($_POST['invoice_client_email']));
    }
    if (isset($_POST['invoice_amount_field'])) {
        update_post_meta($post_id, '_amount', floatval(sanitize_text_field($_POST['invoice_amount_field'])));
    }
    if (isset($_POST['invoice_date'])) {
        update_post_meta($post_id, '_invoice_date', sanitize_text_field($_POST['invoice_date']));
    }
    if (isset($_POST['invoice_due_date'])) {
        update_post_meta($post_id, '_due_date', sanitize_text_field($_POST['invoice_due_date']));
    }
    if (isset($_POST['invoice_status'])) {
        update_post_meta($post_id, '_paid', intval($_POST['invoice_status']));
    }
});

  





  add_action( 'template_redirect', 'amiri_handle_frontend_project_submission' );
function amiri_handle_frontend_project_submission() {

    // Only run on our Dashboard page
    if ( ! is_page( 'client-dashboard' ) ) {
        return;
    }

    // Bail if our form submit button isn't present
    if ( empty( $_POST['create_project'] ) ) {
        return;
    }

    // Verify nonce
    if ( empty( $_POST['create_project_nonce'] )
      || ! wp_verify_nonce( $_POST['create_project_nonce'], 'create_project' )
    ) {
        wp_die( 'Security check failed.' );
    }

    // Collect & sanitize inputs
    $current_user = wp_get_current_user();
    $user_id      = $current_user->ID;
    $plan         = sanitize_text_field( $_POST['plan'] );
    $description  = sanitize_textarea_field( $_POST['description'] );
    $duration     = sanitize_text_field( $_POST['duration'] );

    // Create the Project post
    $title = sprintf( '%s – %s', $plan, current_time( 'Y-m-d H:i' ) );
    $pid   = wp_insert_post([
      'post_type'   => 'project',
      'post_title'  => $title,
      'post_status' => 'reviewing',     // initial custom status slug
      'post_author' => $user_id,
    ]);

    if ( is_wp_error( $pid ) || ! $pid ) {
        error_log( 'Project creation failed: ' . print_r( $pid, true ) );
        wp_die( 'Could not create project. Check debug log.' );
    }

    // Save meta fields
    update_post_meta( $pid, 'plan',        $plan );
    update_post_meta( $pid, 'description', $description );
    update_post_meta( $pid, 'duration',    $duration );

    // Ensure upload & image functions are available
    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
    }
    if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }

    // Process each uploaded file
    if ( ! empty( $_FILES['project_files'] ) ) {
        foreach ( $_FILES['project_files']['name'] as $i => $name ) {
            if ( empty( $name ) ) {
                continue;
            }
            $file = [
              'name'     => $_FILES['project_files']['name'][ $i ],
              'type'     => $_FILES['project_files']['type'][ $i ],
              'tmp_name' => $_FILES['project_files']['tmp_name'][ $i ],
              'error'    => $_FILES['project_files']['error'][ $i ],
              'size'     => $_FILES['project_files']['size'][ $i ],
            ];
            $over = wp_handle_upload( $file, [ 'test_form' => false ] );
            if ( empty( $over['error'] ) ) {
                // Insert attachment
                $att_id = wp_insert_attachment([
                  'post_mime_type' => $over['type'],
                  'post_title'     => sanitize_file_name( $over['file'] ),
                  'post_parent'    => $pid,
                  'post_status'    => 'inherit',
                ], $over['file'] );
                // Generate & save metadata
                $meta = wp_generate_attachment_metadata( $att_id, $over['file'] );
                wp_update_attachment_metadata( $att_id, $meta );
            }
        }
    }

    // Redirect to “Your Projects” (stats) screen
    wp_safe_redirect( add_query_arg( 'screen', 'stats', get_permalink() ) );
    exit;
}



// — 1a) Add a “Project Files” metabox to the Project CPT
add_action('add_meta_boxes', function(){
    add_meta_box(
      'project_files',
      'Project Files',
      'render_project_files_metabox',
      'project',
      'normal',
      'default'
    );
});
function render_project_files_metabox( $post ){
    $files = get_children([
      'post_parent' => $post->ID,
      'post_type'   => 'attachment',
      'orderby'     => 'date',
      'order'       => 'ASC',
    ]);
    if ( $files ) {
      echo '<ul>';
      foreach( $files as $f ){
        $url = wp_get_attachment_url( $f->ID );
        echo '<li><a href="'.esc_url($url).'" target="_blank">'.esc_html($f->post_title).'</a></li>';
      }
      echo '</ul>';
    } else {
      echo '<p><em>No files uploaded for this project.</em></p>';
    }
}



// 1) Add a "Conversations" submenu under Projects
add_action('admin_menu', function(){
    add_submenu_page(
        'edit.php?post_type=project',      // parent = Projects menu
        'Project Conversations',           // page title
        'Conversations',                   // menu title
        'edit_posts',                   // required capability
        'project_conversations',           // menu slug
        'render_project_conversations'     // callback
    );
}, 20);

add_action('admin_init', function(){
    $role = get_role('administrator');
    if ( $role && ! $role->has_cap('edit_projects') ) {
        $role->add_cap('edit_projects');
    }
});

// 2) Badge the “Projects” menu with per-project unread counts
add_action('admin_menu', function(){
    global $menu, $wpdb;
    $admin_id = get_current_user_id();

    // build a map [ project_id => unread_count ]
    $projects = $wpdb->get_col("
      SELECT DISTINCT pm.meta_value+0 AS project_id
        FROM {$wpdb->postmeta} pm
       WHERE pm.meta_key = 'project_id'
    ");

    $total_unread = 0;
    $per_project = [];
    foreach( $projects as $pid ) {
        // find top-level messages for this project newer than last read
        $last_read = get_user_meta( $admin_id, 'last_read_project_' . $pid, true ) ?: '1970-01-01 00:00:00';
        $cnt = $wpdb->get_var( $wpdb->prepare("
            SELECT COUNT(*)
              FROM {$wpdb->posts} p
         LEFT JOIN {$wpdb->postmeta} pm
                ON pm.post_id = p.ID AND pm.meta_key = 'project_id'
             WHERE p.post_type   = 'message'
               AND p.post_parent = 0
               AND pm.meta_value = %d
               AND p.post_date  > %s
        ", $pid, $last_read ) );
        if( $cnt ) {
            $per_project[ $pid ] = intval($cnt);
            $total_unread += $cnt;
        }
    }

    // attach total bubble to Projects menu
    if( $total_unread ){
        foreach( $menu as &$item ){
            if( isset($item[2]) && $item[2] === 'edit.php?post_type=project' ){
                $item[0] .= " <span class='awaiting-mod'>($total_unread)</span>";
                break;
            }
        }
    }

    // store per-project counts for rendering in our submenu page
    set_transient( 'proj_unread_counts', $per_project, HOUR_IN_SECONDS );
}, 999);


// 3) Render the “Project Conversations” page
function render_project_conversations(){
    if( ! current_user_can('edit_projects') ) {
        wp_die('Insufficient permissions');
    }

    $per_project = get_transient('proj_unread_counts') ?: [];

    echo '<div class="wrap"><h1>Project Conversations</h1>';
    echo '<table class="widefat fixed">';
    echo '<thead><tr><th>Project</th><th>Unread Messages</th><th>View Chat</th></tr></thead><tbody>';

    foreach( get_posts([
        'post_type'   => 'project',
        'post_status' => 'any',
        'numberposts' => -1
    ]) as $proj ){
        $pid   = $proj->ID;
        $title = get_the_title($pid);
        $un    = isset($per_project[$pid]) ? intval($per_project[$pid]) : 0;
        $bubble= $un ? "<span class='awaiting-mod'>($un)</span>" : '';
        $url   = add_query_arg([
            'page'       => 'project_conversations',
            'project_id' => $pid
        ], admin_url('edit.php?post_type=project'));

        echo "<tr>
                <td><strong>{$title}</strong> (#{$pid})</td>
                <td style='text-align:center;'>{$bubble}</td>
                <td><a class='button' href='" . esc_url($url) . "'>View Chat</a></td>
              </tr>";
    }

    echo '</tbody></table>';

    // If a project is selected, show its chat:
    if( ! empty($_GET['project_id']) ){
        $proj_id = intval($_GET['project_id']);
        echo '<h2>Chat for Project: ' . esc_html(get_the_title($proj_id)) . '</h2>';
        render_single_project_chat( $proj_id );
    }

    echo '</div>';
}


// helper: render the chat bubbles & reply box for one project
function render_single_project_chat( $project_id ){
    // fetch all top-level messages for this project
    $threads = get_posts([
      'post_type'   => 'message',
      'post_parent' => 0,
      'meta_key'    => 'project_id',
      'meta_value'  => $project_id,
      'orderby'     => 'date',
      'order'       => 'ASC',
      'numberposts' => -1,
    ]);
    $ids = wp_list_pluck( $threads, 'ID' );
    // fetch all replies
    $replies = $ids
      ? get_posts([ 'post_type'=>'message','post_parent__in'=>$ids,'orderby'=>'date','order'=>'ASC','numberposts'=>-1 ])
      : [];

    $all = array_merge( $threads, $replies );
    usort( $all, function($a,$b){ return strcmp($a->post_date, $b->post_date); } );

    // mark these as read now
    delete_transient('proj_unread_counts');
    update_user_meta( get_current_user_id(), 'last_read_project_' . $project_id, current_time('mysql') );

    echo '<div style="max-width:700px;margin:1em 0;padding:1em;background:#f9f9f9;border:1px solid #ddd;">';
    foreach( $all as $m ){
        $project_author_id = (int) get_post_field('post_author', $project_id); // Client who owns the project
        $message_author_id = (int) $m->post_author; // Author of the current message

        if ($message_author_id == $project_author_id) {
            $client_info = get_userdata($project_author_id);
            $label = $client_info ? esc_html($client_info->display_name) : 'Client'; // Label with Client's name or "Client"
            $bg = '#eef'; // Client's messages background
        } else {
            // Assumed to be an admin reply
            $admin_info = get_userdata($message_author_id);
            $label = $admin_info ? esc_html($admin_info->display_name) : 'Admin'; // Label with Admin's name or "Admin"
            $bg = '#fee'; // Admin's messages background
        }

        printf(
          '<div style="background:%s;padding:8px;border-radius:4px;margin-bottom:8px;">
             <strong>%s:</strong> %s<br><small>%s</small>
           </div>',
          esc_attr($bg), // Now $bg is defined
          esc_html($label),
          esc_html($m->post_content),
          esc_html($m->post_date)
        );
    }
    echo '</div>';

    // reply form
    $last_id = end($all)->ID ?? 0;
    ?>
    <h3>Send Reply</h3>
    <textarea id="project-admin-reply" rows="4" style="width:100%;max-width:700px;"></textarea><br>
    <button class="button button-primary" id="send-project-reply">Send</button>

    <script>
    jQuery('#send-project-reply').on('click', function(){
      var reply = jQuery('#project-admin-reply').val().trim();
      if(! reply){ alert('Type a reply.'); return; }
      jQuery.post( ajaxurl, {
        action:      'admin_send_reply',
        parent_id:   <?php echo intval($last_id)?>,
        reply:       reply
      }, function(res){
        if(res.success) location.reload();
        else alert('Error sending reply');
      });
    });
    </script>
    <?php
}



function amiri_custom_login_redirect( $redirect_to, $request, $user ) {
    //is there a user to check?
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        //check for admins
        if ( in_array( 'administrator', $user->roles ) ) {
            // redirect them to the default place
            return admin_url();
        } else {
            // redirect them to the client dashboard page
            // Replace 'client-dashboard' with the actual slug of your client dashboard page if it's different
            $dashboard_page = get_page_by_path('client-dashboard');
            if ($dashboard_page) {
                return get_permalink($dashboard_page->ID);
            }
            // Fallback to home url if dashboard page doesn't exist
            return home_url();
        }
    } else {
        return $redirect_to;
    }
}
add_filter( 'login_redirect', 'amiri_custom_login_redirect', 10, 3 );