<?php
/**
 * Portfolio Grid Block Template
 */
$posts_per_page = get_field('number_of_items') ?: 6;
$portfolio_query = new WP_Query(array(
    'post_type' => 'portfolio',
    'posts_per_page' => $posts_per_page,
    'orderby' => 'date',
    'order' => 'DESC'
));
?>

<div class="portfolio-grid">
    <?php if ($portfolio_query->have_posts()) : ?>
        <?php while ($portfolio_query->have_posts()) : $portfolio_query->the_post(); ?>
            <article class="portfolio-tile">
                <a href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="tile-image">
                            <?php the_post_thumbnail('portfolio-thumbnail'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="tile-content">
                        <h3 class="tile-title"><?php the_title(); ?></h3>
                        <?php if ($category = get_field('project_category')) : ?>
                            <p class="tile-category"><?php echo esc_html($category); ?></p>
                        <?php endif; ?>
                    </div>
                </a>
            </article>
        <?php endwhile; wp_reset_postdata(); ?>
    <?php else : ?>
        <p><?php esc_html_e('No portfolio items found.', 'sina-amiri'); ?></p>
    <?php endif; ?>
</div>

<?php if (get_field('show_view_more_button')) : ?>
    <div class="portfolio-cta">
        <a href="<?php echo esc_url(get_post_type_archive_link('portfolio')); ?>" class="btn">
            <?php esc_html_e('View Full Portfolio', 'sina-amiri'); ?>
        </a>
    </div>
<?php endif; ?>