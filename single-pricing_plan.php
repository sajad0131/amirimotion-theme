<?php

/**
 * Single Pricing Plan Template
 */
get_header();
?>

<main class="plan-single">
    <article <?php post_class('plan-container'); ?> itemscope itemtype="https://schema.org/Product">
        <div class="plan-header">
            <?php if (has_post_thumbnail()) : ?>
                <div class="plan-image" itemprop="image">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>

            <div class="plan-meta">
                <h1 class="plan-title" itemprop="name"><?php the_title(); ?></h1>
                <div class="plan-excerpt" itemprop="description">
                    <?php the_excerpt(); ?>
                </div>
            </div>
        </div>

        <div class="plan-content">
            <div class="plan-details" itemprop="description">
                <?php the_content(); ?>
            </div>

            <div class="plan-pricing">
                <h2><?php _e('Pricing Options', 'sina-amiri'); ?></h2>
                <?php
                $price_durations = get_post_meta(get_the_ID(), '_price_durations', true);
                if (!empty($price_durations)) :
                ?>
                    <ul class="price-list" id="price-list" >
                        <?php foreach ($price_durations as $pd) : ?>
                            <li class="price-item">
                                <span class="duration"><?php echo esc_html($pd['duration']); ?></span>
                                <span class="price"><?php echo esc_html($pd['price']); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <div class="plan-cta">
            <button class="cta-modern open-form"
                data-plan="<?php the_title_attribute(); ?>"
                data-durations="<?php echo esc_attr(wp_json_encode($price_durations)); ?>"
                data-plan-type="<?php
                                $terms = get_the_terms(get_the_ID(), 'plan_type');
                                echo $terms ? esc_attr($terms[0]->slug) : '';
                                ?>">
                <?php _e('Get This Plan', 'sina-amiri'); ?>
            </button>
        </div>
    </article>
</main>
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
<?php
// Include contact modal

//get_template_part('template-parts/contact-modal');
get_footer();
