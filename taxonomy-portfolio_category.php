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



<main class="portfolio-category">
    <div class="container" style="max-width: none; margin: 2rem;">
        <?php
        $current_term = get_queried_object();

        // Get all child terms (subcategories) of the current term
        $child_terms = get_terms(array(
            'taxonomy'   => 'portfolio_category',
            'parent'     => $current_term->term_id,
            'hide_empty' => false,
            'orderby'    => 'term_order', // Optional: if you have custom ordering
        ));

        if (!empty($child_terms)) :
            foreach ($child_terms as $subcategory) :
                // Get metadata for the subcategory
                $youtubeId           = get_term_meta($subcategory->term_id, 'youtube_video_id', true);
                $googleDriveId       = get_term_meta($subcategory->term_id, 'drive_video_id', true);
                $thumbnail_url       = get_term_meta($subcategory->term_id, 'category_thumbnail', true);
                $category_description = get_term_meta($subcategory->term_id, 'video_description', true);

                // Query for portfolio items in this subcategory
                $portfolio_items = new WP_Query(array(
                    'post_type'      => 'portfolio',
                    'posts_per_page' => 6,
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'portfolio_category',
                            'field'    => 'term_id',
                            'terms'    => $subcategory->term_id,
                        ),
                    ),
                ));
        ?>
                <div class="subcategory-section" style="margin-bottom: 4rem;">
                    <h1 class="section-title" style="text-align: center;"><?php echo esc_attr($subcategory->name); ?></h1>

                    <div class="category-showreel-and-grid">
                        <!-- Category Showreel -->
                        <div class="portfolio-card big-card" 
                            data-youtube-id="<?php echo esc_attr($youtubeId); ?>"
                            data-google-drive-id="<?php echo esc_attr($googleDriveId); ?>"
                            data-description="<?php echo esc_attr($category_description); ?>">
                            <div class="card-inner">
                                <div class="card-media">
                                    <img src="<?php echo esc_url($thumbnail_url); ?>"
                                        class="category-poster"
                                        alt="<?php echo esc_attr($subcategory->name); ?>">
                                    <div class="media-overlay"></div>
                                    <div class="video-controls">
                                        <button class="play-pause">
                                            <img src="<?php echo get_template_directory_uri(); ?>/images/play-icon.png"
                                                class="play-img"
                                                alt="Play video">
                                        </button>
                                    </div>
                                </div>
                                <div class="card-content">

                                    <div class="card-title">
                                        <?php echo wp_kses_post($category_description); ?>
                                    </div>
                                    <div class="card-actions">
                                        <div class="cta-modern"> <!-- Add your actual link here -->
                                            <h3><?php echo esc_html($subcategory->name); ?></h3>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Portfolio Items Grid -->
                        <?php if ($portfolio_items->have_posts()) : ?>
                            <div class="portfolio-grid">
                                <?php while ($portfolio_items->have_posts()) : $portfolio_items->the_post(); ?>
                                    <?php
                                    $youtubeId       = get_post_meta(get_the_ID(), '_sina_amiri_youtube_video_id', true);
                                    $googleDriveId   = get_post_meta(get_the_ID(), '_sina_amiri_google_drive_video_id', true);
                                    $thumbnail_url   = get_the_post_thumbnail_url(get_the_ID(), 'full');
                                    if (!$thumbnail_url) {
                                        $thumbnail_url = get_template_directory_uri() . '/images/default-thumbnail.jpg'; // Provide a default image if none
                                    }
                                    $video_description = get_the_excerpt();
                                    $hasVideo = !empty($youtubeId) || !empty($googleDriveId);
                                    ?>
                                    <div class="portfolio-card little-card"
                                        <?php if ($hasVideo) : ?>
                                        data-youtube-id="<?php echo esc_attr($youtubeId); ?>"
                                        data-google-drive-id="<?php echo esc_attr($googleDriveId); ?>"
                                        data-description="<?php echo esc_attr($video_description); ?>"
                                        <?php endif; ?>>
                                        <div class="card-inner">
                                            <div class="card-media">
                                                <img class="category-poster" src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title(); ?>">
                                                <?php if ($hasVideo) : ?>
                                                    <div class="media-overlay"></div>
                                                    <div class="video-controls">
                                                        <button class="play-pause">
                                                            <img src="<?php echo get_template_directory_uri(); ?>/images/play-icon.png"
                                                                class="play-img"
                                                                alt="Play video">
                                                        </button>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="card-content">
                                                <h3 class="card-title"><?php echo esc_html($video_description); ?></h3>
                                                <div class="card-actions">
                                                    <div href="#"
                                                        class="cta-modern">
                                                        <?php the_title(); ?>
                                                        
                                                    </div>
                                                </div>
                                            </div>




                                        </div>
                                    </div>
                                <?php endwhile;
                                wp_reset_postdata(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
        <?php
            endforeach;
        else :
        // If there are no child terms, display the current term's showreel and items
        // Similar code as above but for $current_term
        endif;
        ?>
    </div>
</main>

<!-- Include the Video Modal -->
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

<?php get_footer(); ?>