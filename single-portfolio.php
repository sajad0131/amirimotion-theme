<?php get_header(); ?>

<article class="portfolio-single">
    <div class="container">

        <?php while(have_posts()) : the_post();
            $project_video = get_post_meta(get_the_ID(), '_portfolio_video', true);
        ?>
            <div class="video-container">
                <video class="project-video" controls playsinline>
                    <source src="<?php echo esc_url($project_video); ?>" type="video/mp4">
                </video>
            </div>
            <h1 class="project-title"><?php the_title(); ?></h1>
            <div class="project-content">
                <?php the_content(); ?>
            </div>
        <?php endwhile; ?>
    </div>
</article>

<?php get_footer(); ?>