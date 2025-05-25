<?php
/* Template Name: Blog Page */
get_header();
?>
<style>
    /* -----------------------------------
   BLOG GRID
----------------------------------- */
    .portfolio-grid-modern {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 32px;
        margin: 40px 0;
    }

    /* -----------------------------------
   CARD BASE
----------------------------------- */
    .blog-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .blog-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
    }

    /* -----------------------------------
   MEDIA & OVERLAY
----------------------------------- */
    .blog-card__media {
        position: relative;
        overflow: hidden;
        /* crop overlay */
    }

    .blog-card__media img {
        display: block;
        width: 100%;
        height: auto;
    }

    .blog-card__overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.2);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .blog-card:hover .blog-card__overlay {
        opacity: 1;
    }

    /* -----------------------------------
   CONTENT
----------------------------------- */
    .blog-card__content {
        padding: 24px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .blog-card__meta {
        font-size: 0.875rem;
        color: #999;
        margin-bottom: 8px;
    }

    .blog-card__title {
        font-size: 1.25rem;
        margin: 0 0 12px;
        color: #00002c;
    }

    .blog-card__excerpt {
        color: #555;
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: auto;
    }

    /* -----------------------------------
   FOOTER & CTA
----------------------------------- */
    .blog-card__footer {
        padding: 16px 24px;
        border-top: 1px solid #eee;
    }

    .blog-card__cta {
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        font-weight: 600;
        color: #111;
        transition: color 0.2s ease;
    }

    .blog-card__cta:hover {
        color: #000;
    }

    .blog-card__cta-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #111;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 8px;
        transition: background 0.3s ease;
    }

    .blog-card__cta:hover .blog-card__cta-circle {
        background: #333;
    }

    .blog-card__cta-icon {
        display: block;
        border: solid #fff;
        border-width: 0 2px 2px 0;
        padding: 4px;
        transform: rotate(-45deg);
    }
    /* Make the link cover the entire card */
.blog-card {
  position: relative;
}
.blog-card__link {
  position: absolute;
  inset: 0;            /* top:0; right:0; bottom:0; left:0 */
  z-index: 3;
  /* ensure it doesn’t block your hover‐effects on inner elements */
  background: transparent;
  text-indent: -9999px; /* hide any link text if you put fallback text inside */
}
.blog-card__content,
.blog-card__media,
.blog-card__footer {
  position: relative;
  z-index: 2;         /* so your content appears above the invisible <a> */
}

/* -----------------------------------
   GLOBAL BACKGROUND SVG
----------------------------------- */
.global-background {
  /* full‑screen fixed SVG behind everything */
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  z-index: -1;
  pointer-events: none;
}
.global-background svg {
  width: 100%;
  height: 100%;
  object-fit: cover;
  mix-blend-mode: lighten;  /* soft blend with page bg */
  opacity: 0.3;              /* subtlety */
}

/* -----------------------------------
   PAGE CONTAINER
----------------------------------- */
.container {
  /* allow full-bleed backgrounds if ever needed */
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
}

/* -----------------------------------
   BLOG HERO / SECTION HEADER
----------------------------------- */
.blog-hero {
  padding: 120px 0 80px;
  background: #f8f9fa; /* very light gray */
  text-align: center;
  border-radius: 20px;
}
.blog-hero__title {
  font-size: 2.75rem;         /* large, bold main title */
  font-weight: 800;
  margin: 0;
  line-height: 1.1;
  color: #111;
}
.blog-hero__subtitle {
  font-size: 1rem;
  letter-spacing: 0.2em;
  font-weight: 600;
  margin: 16px 0;
  color: #555;
}
.blog-hero__lead {
  font-size: 1.125rem;
  line-height: 1.6;
  max-width: 800px;
  margin: 0 auto;
  color: #444;
}

/* -----------------------------------
   SECTION TITLES (below the hero)
----------------------------------- */
.section-header {
  padding: 60px 0 20px;
  text-align: center;
}
.section-header .section-title {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 8px;
  color: #111;
}
.section-header h2 {
  font-size: 1rem;
  font-weight: 600;
  color: #777;
  letter-spacing: 0.1em;
  margin-bottom: 24px;
}
.section-subtitle {
  font-size: 1rem;
  color: #555;
  margin: 0 auto 40px;
  max-width: 800px;
  line-height: 1.6;
}

/* -----------------------------------
   FOOTER (optional starter)
----------------------------------- */
.site-footer {
  background: #111;
  color: #eee;
  padding: 60px 0;
  font-size: 0.9rem;
  text-align: center;
}
.site-footer a {
  color: #fff;
  text-decoration: underline;
}

.blog-pagination {
  display: flex;
  justify-content: center;
  margin: 48px 0;
}
.blog-pagination .page-numbers {
  margin: 0 8px;
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  color: #555;
  text-decoration: none;
  transition: background 0.2s, border-color 0.2s;
}
.blog-pagination .page-numbers.current {
  background: #111;
  color: #fff;
  border-color: #111;
}
.blog-pagination .page-numbers:hover {
  background: #f0f0f0;
  border-color: #ccc;
}


</style>
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
<div class="container" style="max-width:none;">
<header class="blog-hero">
  <div class="blog-hero__inner container">
    <h1 class="blog-hero__title">Creative Video Editing &amp; Motion Graphics Insights</h1>
    <h2 class="blog-hero__subtitle">INSIGHTS &amp; INNOVATIONS</h2>
    <p class="blog-hero__lead">
      Dive into our curated collection of in‑depth articles, case studies, and video tutorials designed to elevate your brand with professional motion design services and creative animation strategies. Explore the latest video production trends, master effective motion graphics techniques, and discover how UI/UX animation best practices can transform your digital presence. Whether you're crafting brand storytelling with animation or leveling up your visual content, Amiri Motion is your go‑to hub for inspiration and industry expertise. Learn, create, and stay ahead!
    </p>
  </div>
</header>

    <div class="portfolio-grid-modern">
        <?php
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => 10 // Changed to 10 for better performance
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
        ?>
                <article <?php post_class('blog-card'); ?>>
                <a href="<?php the_permalink(); ?>" class="blog-card__link" aria-label="<?php the_title_attribute(); ?>"></a>
                    <div class="blog-card__media">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php
                            $thumb_id = get_post_thumbnail_id();
                            $alt      = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
                            ?>
                            <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php echo esc_attr($alt); ?>">
                        <?php else : ?>
                            <img src="<?php echo get_template_directory_uri(); ?>/images/default-image.jpg" alt="Default">
                        <?php endif; ?>
                        <div class="blog-card__overlay"></div>
                    </div>

                    <div class="blog-card__content">
                        <div class="blog-card__meta">
                            <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                        </div>

                        <h3 class="blog-card__title"><?php the_title(); ?></h3>
                        <div class="blog-card__excerpt"><?php the_excerpt(); ?></div>
                    </div>

                    
                </article>
        <?php
            endwhile;
            wp_reset_postdata();
        else :
            echo '<p>No posts found.</p>';
        endif;
        ?>
    </div>
    <div class="blog-pagination">
  <?php
    the_posts_pagination( array(
      'mid_size'           => 2,
      'prev_text'          => '&larr; Prev',
      'next_text'          => 'Next &rarr;',
      'screen_reader_text' => ' ',
    ) );
  ?>
</div>
</div>
<?php get_footer(); ?>