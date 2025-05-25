<?php
/**
 * The main template file
 *
 * @package Sina Amiri
 */

get_header();
?>

<main id="primary" class="content-area">
    <div class="container">
        <?php if (have_posts()) : ?>
            
            <div class="page-content">

                <?php
                // Start the Loop
                while (have_posts()) :
                    the_post();
                    ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
                        <header class="entry-header">
                            <h2 class="entry-title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            <div class="entry-meta">
                                <?php
                                printf(
                                    esc_html__('Posted on %s by %s', 'sina-amiri'),
                                    '<time datetime="' . esc_attr(get_the_date('c')) . '">' . esc_html(get_the_date()) . '</time>',
                                    '<span class="author">' . esc_html(get_the_author()) . '</span>'
                                );
                                ?>
                            </div>
                        </header>

                        <div class="entry-content">
                            <?php
                            the_excerpt();
                            printf(
                                '<a href="%s" class="read-more">%s</a>',
                                esc_url(get_permalink()),
                                esc_html__('Continue reading', 'sina-amiri')
                            );
                            ?>
                        </div>
                    </article>

                <?php endwhile; ?>
            </div>

            <div class="pagination">
                <?php
                the_posts_pagination(array(
                    'mid_size'  => 2,
                    'prev_text' => __('&laquo; Previous', 'sina-amiri'),
                    'next_text' => __('Next &raquo;', 'sina-amiri'),
                ));
                ?>
            </div>

        <?php else : ?>
            <div class="no-results">
                <h2><?php esc_html_e('Nothing Found', 'sina-amiri'); ?></h2>
                <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'sina-amiri'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();