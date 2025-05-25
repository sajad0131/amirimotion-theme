<?php get_header(); ?>

<div class="global-background" style="
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: -1;
    pointer-events: none;
">
    <svg xmlns="http://www.w3.org/2000/svg" width="400" height="400" viewBox="0 0 800 800" style="
        width: 100%;
        height: 100%;
        z-index: -100;
        position: absolute;
    ">
        <rect fill="#e0e0e000" width="800" height="800"></rect>
        <g fill="none" stroke="#404" stroke-width="1">
            <path d="M769 229L1037 260.9M927 880L731 737 520 660 309 538 40 599 295 764 126.5 879.5 40 599-197 493 102 382-31 229 126.5 79.5-69-63"></path>
            <path d="M-31 229L237 261 390 382 603 493 308.5 537.5 101.5 381.5M370 905L295 764"></path>
            <path d="M520 660L578 842 731 737 840 599 603 493 520 660 295 764 309 538 390 382 539 269 769 229 577.5 41.5 370 105 295 -36 126.5 79.5 237 261 102 382 40 599 -69 737 127 880"></path>
            <path d="M520-140L578.5 42.5 731-63M603 493L539 269 237 261 370 105M902 382L539 269M390 382L102 382"></path>
            <path d="M-222 42L126.5 79.5 370 105 539 269 577.5 41.5 927 80 769 229 902 382 603 493 731 737M295-36L577.5 41.5M578 842L295 764M40-201L127 80M102 382L-261 269"></path>
        </g>
        <g fill="#505">
            <circle cx="769" cy="229" r="12"></circle>
            <circle cx="539" cy="269" r="12"></circle>
            <circle cx="603" cy="493" r="12"></circle>
            <circle cx="731" cy="737" r="12"></circle>
            <circle cx="520" cy="660" r="12"></circle>
            <circle cx="309" cy="538" r="12"></circle>
            <circle cx="295" cy="764" r="12"></circle>
            <circle cx="40" cy="599" r="12"></circle>
            <circle cx="102" cy="382" r="12"></circle>
            <circle cx="127" cy="80" r="12"></circle>
            <circle cx="370" cy="105" r="12"></circle>
            <circle cx="578" cy="42" r="12"></circle>
            <circle cx="237" cy="261" r="12"></circle>
            <circle cx="390" cy="382" r="12"></circle>
        </g>
    </svg>
</div>



<!-- YouTube Video Modal (Same as front-page) -->
<div class="video-modal-overlay">
    <div class="video-modal">
        <button class="close-modal">X</button>
        <div id="youtube-player"></div> <!-- YouTube Player Container -->
        <div id="google-drive-player" class="google-drive-container"></div> <!-- Google Drive Player Container -->
        <div class="video-description"></div>

        <button class="copy-link-button">
            <img src="<?php echo get_template_directory_uri(); ?>/images/copy-icon.png" alt="Copy Link">
            Copy Link
        </button>

    </div>
</div>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-container">
        <div class="hero-content">
            <h1 class="hero-title">Professional Video Editing and Motion Graphics Services</h1>
            <h2 class="hero-subtitle">Video editing services for small businesses</h1>
            <div class="hero-divider"></div>
            <h2 class="hero-slogan">hire video editor now</h2>
            <h2 class="hero-slogan">Video Editing and Motion Graphics for Marketing Videos</h2>

        </div>
        <div class="hero-media">
            <div class="card-media video-container"
                data-youtube-id="YOUR_HERO_VIDEO_ID"
                data-description="Hero section showcase reel">
                <img src="<?php echo get_template_directory_uri(); ?>/images/hero-poster.jpg"
                    class="hero-poster"
                    alt="Hero video poster">
                <div class="media-overlay"></div>
                <div class="video-controls">
                    <button class="play-pause">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/play-icon.png"
                            class="play-icon"
                            alt="Play video">
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Portfolio Section -->
<section class="portfolio-showcase">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Creative Domains</h2>
            <p class="section-subtitle">Exploring visual storytelling through motion</p>
        </div>

        <div class="portfolio-grid-modern">
            <?php
            $main_categories = get_terms(array(
                'taxonomy' => 'portfolio_category',
                'parent' => 0,
                'hide_empty' => false
            ));

            foreach ($main_categories as $category) :
                $googleDriveId = get_term_meta($category->term_id, 'drive_video_id', true);
                $youtubeId = get_term_meta($category->term_id, 'youtube_video_id', true);
                $thumbnail_url = get_term_meta($category->term_id, 'category_thumbnail', true);
                $video_description = get_term_meta($category->term_id, 'video_description', true);
                $project_count = $category->count;
            ?>
                <article class="portfolio-card"
                    style="background: linear-gradient(311deg, #ffffff, #00002c);
    padding-bottom: 20px;"
                    data-youtube-id="<?php echo esc_attr($youtubeId); ?>"
                    data-google-drive-id="<?php echo esc_attr($googleDriveId); ?>"
                    data-description="<?php echo esc_attr($video_description); ?>">
                    <div class="card-inner">
                        <div class="card-media">
                            <img src="<?php echo esc_url($thumbnail_url); ?>"
                                class="category-poster"
                                alt="<?php echo esc_attr($category->name); ?>">
                            <div class="media-overlay"></div>
                            <div class="video-controls">
                                <button class="play-pause">
                                    <img src="<?php echo get_template_directory_uri(); ?>/images/play-icon.png"
                                        class="play-img"
                                        alt="Play video">
                                </button>
                            </div>
                            <div class="project-count">
                                <span><?php echo esc_html($project_count); ?></span>
                                <small>Projects</small>
                            </div>
                        </div>
                        <div class="card-content">

                            <div class="card-actions">
                                <div
                                    class="cta-modern" style="font-weight:900;">
                                    <?php echo esc_html($category->name); ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-actions">
                        <a class="learn-more" rel="canonical" href="<?php echo esc_url(get_term_link($category)); ?>"
                            class="cta-modern">
                            <span class="circle" aria-hidden="true">
                                <span class="icon arrow"></span>
                            </span>
                            <span class="button-text">Explore collection</span>
                        </a>
                    </div>

                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>


<section class="pricing-section">
    <div class="container" style="display:contents;">
        <!-- Video Editing Plans -->
        <div class="pricing-type-group" data-plan-type="video-editing">
            <h3 class="plan-type-title">Video Editing Packages</h3>
            <div class="pricing-grid">
                <?php 
                $video_editing_plans = get_posts(array(
                    'post_type' => 'pricing_plan',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'plan_type',
                            'field' => 'slug',
                            'terms' => 'video-editing'
                        )
                    )
                ));

                foreach ($video_editing_plans as $plan) : 
                    $price_durations = get_post_meta($plan->ID, '_price_durations', true);
                    $features = get_post_meta($plan->ID, '_features', true);
                    $button_text = get_post_meta($plan->ID, '_button_text', true);
                    $youtube_example = get_post_meta($plan->ID, '_youtube_example_id', true);
                    $drive_example = get_post_meta($plan->ID, '_drive_example_id', true);
                ?>
                <div class="pricing-card" 
                     data-durations="<?php echo esc_attr(wp_json_encode($price_durations)); ?>"
                     data-youtube-example-id="<?php echo esc_attr($youtube_example); ?>"
                     data-drive-example-id="<?php echo esc_attr($drive_example); ?>">
                    <h2><?php echo esc_html($plan->post_title); ?></h2>
                    <?php if ($youtube_example || $drive_example) : ?>
                        <button class="example-video-button">
                            View Sample
                        </button>
                    <?php endif; ?>
                    <ul class="plan-features">
                        <h3>Benefits</h3>
                        <?php foreach (explode("\n", $features) as $feature) : ?>
                            <?php if (!empty(trim($feature))) : ?>
                            <li><?php echo esc_html($feature); ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                    <div class="price-duration-list">
                        <h3>Price</h3>
                        <?php foreach ($price_durations as $pd) : ?>
                            <div class="price-duration-item">
                                <span class="duration"><?php echo esc_html($pd['duration']); ?></span>
                                <span class="price"><?php echo esc_html($pd['price']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    

                    

                    <button class="cta-modern open-form" 
                            data-plan="<?php echo esc_attr($plan->post_title); ?>">
                        <?php echo esc_html($button_text); ?>
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Motion Graphics Plans -->
        <div class="pricing-type-group" data-plan-type="motion-graphics">
            <h3 class="plan-type-title">Motion Graphics Packages</h3>
            <div class="pricing-grid">
                <?php 
                $motion_graphics_plans = get_posts(array(
                    'post_type' => 'pricing_plan',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'plan_type',
                            'field' => 'slug',
                            'terms' => 'motion-graphics'
                        )
                    )
                ));

                foreach ($motion_graphics_plans as $plan) : 
                    $price_durations = get_post_meta($plan->ID, '_price_durations', true);
                    $features = get_post_meta($plan->ID, '_features', true);
                    $button_text = get_post_meta($plan->ID, '_button_text', true);
                    $youtube_example = get_post_meta($plan->ID, '_youtube_example_id', true);
                    $drive_example = get_post_meta($plan->ID, '_drive_example_id', true);
                ?>
                <div class="pricing-card" 
                     data-durations="<?php echo esc_attr(wp_json_encode($price_durations)); ?>"
                     data-youtube-example-id="<?php echo esc_attr($youtube_example); ?>"
                     data-drive-example-id="<?php echo esc_attr($drive_example); ?>">
                    <h2><?php echo esc_html($plan->post_title); ?></h2>
                    <?php if ($youtube_example || $drive_example) : ?>
                        <button class="example-video-button">
                            View Examples
                        </button>
                    <?php endif; ?>

                    <ul class="plan-features">
                        <h3>Benefits</h3>
                        <?php foreach (explode("\n", $features) as $feature) : ?>
                            <?php if (!empty(trim($feature))) : ?>
                            <li><?php echo esc_html($feature); ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                    <h3>Price</h3>
                    <div class="price-duration-list">
                        
                        <?php foreach ($price_durations as $pd) : ?>
                            <div class="price-duration-item">
                                <span class="duration"><?php echo esc_html($pd['duration']); ?></span>
                                <span class="price"><?php echo esc_html($pd['price']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    

                    

                    <button class="cta-modern open-form" 
                            data-plan="<?php echo esc_attr($plan->post_title); ?>">
                        <?php echo esc_html($button_text); ?>
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>



<!-- Contact Form Modal -->
<div class="form-modal-overlay">
    <div class="form-modal">
        <span class="modal-close" style="color:#00002c">X</span>
        <h3 style="color:#00002c"><?php _e('Contact Us', 'sina-amiri'); ?></h3>
        <form id="contact-form" class="contact-form">
            <input type="hidden" name="action" value="submit_contact_form">
            <?php wp_nonce_field('sina_amiri_form_nonce', 'form_nonce'); ?>
            <input type="hidden" name="plan" id="selected-plan">
            <input type="hidden" name="price" id="selected-price">
            <input type="hidden" name="plan_type" id="selected-plan-type" value="">
            <input type="text" name="name" placeholder="<?php _e('Your Name', 'sina-amiri'); ?>" required>
            <input type="email" name="email" placeholder="<?php _e('Your Email', 'sina-amiri'); ?>" required>
            <input type="tel" name="phone" placeholder="<?php _e('Phone Number', 'sina-amiri'); ?>" required>
            
            <select name="duration" id="duration-select" required>
                <option value="">Select Duration</option>
                <!-- Options will be populated dynamically -->
            </select>
            
            <textarea name="message" placeholder="<?php _e('Project Details', 'sina-amiri'); ?>" rows="4" required></textarea>
            
            <button type="submit" class="cta-modern">
                <?php _e('Send Request', 'sina-amiri'); ?>
            </button>
            <div class="loading-spinner" style="display: none;">
    <div class="spinner"></div>
    <p>Sending...</p>
</div>
        </form>
    </div>
</div>



<!-- Testimonials Section -->
<section class="testimonials">
    <div class="container">
        <h2 class="section-title">Client Testimonials</h2>
        <div class="testimonial-slider">
            <div class="slider-controls">
                <button class="slider-prev">‹</button>
                <div class="slider-dots">
                    <?php
                    $testimonials = get_posts(array(
                        'post_type' => 'testimonial',
                        'posts_per_page' => -1
                    ));
                    $dot_count = 0;
                    foreach ($testimonials as $testimonial) :
                    ?>
                        <div class="dot <?php echo $dot_count === 0 ? 'active' : ''; ?>"></div>
                    <?php
                        $dot_count++;
                    endforeach;
                    ?>
                </div>
                <button class="slider-next">›</button>
            </div>
            <?php
            $slide_count = 0;
            foreach ($testimonials as $testimonial) :
                $thumbnail = get_the_post_thumbnail_url($testimonial->ID, 'thumbnail');
                if (!$thumbnail) {
                    $thumbnail = 'https://via.placeholder.com/120';
                }
            ?>
                <div class="testimonial-slide <?php echo $slide_count === 0 ? 'active' : ''; ?>">
                    <div class="testimonial-image" style="background-image: url('<?php echo esc_url($thumbnail); ?>');"></div>
                    <div class="testimonial-content">
                        <div class="stars-container">
                            <?php for ($i = 0; $i < 5; $i++) : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" fill="#FFD700" />
                                </svg>
                            <?php endfor; ?>
                        </div>
                        <blockquote>
                            <?php echo wpautop($testimonial->post_content); ?>
                            <cite>- <?php echo esc_html($testimonial->post_title); ?></cite>
                        </blockquote>
                    </div>
                </div>
            <?php
                $slide_count++;
            endforeach;
            ?>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about-modern">
    <div class="container">
        <div class="about-grid">
            <div class="profile-media">
                <div class="profile-image">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/profile-placeholder.jpg" alt="Sina Amiri" class="main-profile">
                    <div class="experience-badge">
                        <span>6+</span>
                        <small>Years Experience</small>
                    </div>
                </div>
                <div class="motion-graphic">
                    <div class="shape-circle"></div>
                    <div class="shape-triangle"></div>
                    <div class="shape-wave"></div>
                </div>
            </div>
            <div class="about-content">
                <h2 class="section-title"><span class="highlight">Amiri Motion:</span> Video Editing & Motion Graphics Services</h2>
                <div class="about-text">
                    <p class="lead">About Amiri Motion</p>
                    <p>At Amiri Motion, we specialize in professional video editing and motion graphics services that bring your vision to life. With expert storytelling and cutting-edge design, we create captivating visuals for businesses, creators, and brands. Elevate your content with our top-tier video editing and motion graphic solutions, crafted to leave a lasting impact.
</p>
                    <div class="signature">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/signature.png" alt="Signature">
                    </div>
                    <div class="agency-info">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
  <!-- Envelope Body -->
  <path fill="currentColor" d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/>
</svg>
                        <div class="agency-details">
                            <p class="agency-title">Contact us at</p>
                            <a href="mailto:info@amirimotion.com" target="_blank" class="agency-link">
                                info@AmiriMotion.com
                                <svg class="arrow" viewBox="0 0 24 24">
                                    <path d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>