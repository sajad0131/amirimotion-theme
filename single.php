<?php get_header(); ?>
<div class="global-background" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -1; pointer-events: none;">
<svg xmlns="http://www.w3.org/2000/svg" width="400" height="400" viewBox="0 0 800 800" style="width: 100%; height: 100%; z-index: -100; position: absolute;">
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
<div class="container">
    <div class="post-container">

        <?php if (has_post_thumbnail()) : ?>
            
            <div class="post-image">
                <img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?> Featured Image">
            </div>
        <?php endif; ?>
        <?php if (function_exists('render_toc')) { render_toc(); } ?>

        <h1 class="blog-section-title"><?php the_title(); ?></h1>
        <div class="content-wrapper">
            <div class="post-content">
                <?php the_content(); 
                if (function_exists('render_toc')) { render_toc(); }

                ?>
            </div>
            <aside class="sidebar">
                <?php get_sidebar(); ?>
                <?php if (function_exists('render_toc')) { render_toc(); } ?>

            </aside>
        </div>
        <div class="comment-section">
            <?php comments_template(); ?> <!-- WordPress comment section -->
        </div>
    </div>
</div>
<?php get_footer(); ?>