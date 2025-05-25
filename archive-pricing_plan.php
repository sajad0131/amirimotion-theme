<?php
/**
 * Archive template for the 'pricing_plan' CPT
 * Template Name: Pricing Plans Archive
 */



// --- 1. Define FAQs and SEO Meta/JSON-LD ----------------
$faqs = [
  ['How do I choose the right package for video editing services?', 'Choose based on project length, complexity, and revisions—you can always upgrade later.'],
  ['Can I switch packages mid-project?',        'Yes! We’ll prorate any change and keep your workflow moving.'],
  ['Do you offer subscriptions?',               'Absolutely—ask us about monthly retainer plans for ongoing needs.'],
];

// Inject meta & schema
add_action('wp_head', function() use ($faqs) {
  // **SEO Meta**
  echo '<title>Amiri Motion Pricing | Vid eo Editing & Motion Graphics Packages for video production</title>' . "\n";
  echo '<meta name="description" content="Transparent, tiered pricing for vid eo editing & motion graphics at Amiri Motion—Standard, Premium, Enhanced plans for every budget video production ." />' . "\n";
  echo '<meta name="keywords" content="video editing pricing, motion graphics pricing, Amiri Motion, pricing plans, vid eo, video production, video editing services" />' . "\n";
  echo '<link rel="canonical" href="' . esc_url( home_url('/pricing-plans/') ) . '" />' . "\n";

  // **Organization + OfferCatalog + AggregateOffer Schema**
  $plans = get_posts(['post_type'=>'pricing_plan','numberposts'=>-1]);
  $offers = [];
  $prices = [];
  foreach($plans as $p) {
    $pd = get_post_meta($p->ID,'_price_durations',true);
    if(!empty($pd)) {
      $price = floatval( $pd[0]['price'] );
      $prices[] = $price;
      $offers[] = [
        '@type'         => 'Offer',
        'name'          => $p->post_title,
        'price'         => $price,
        'priceCurrency' => 'USD',
        'url'           => get_permalink($p->ID),
      ];
    }
  }
  sort($prices);
  $agg = [
    '@type'        => 'AggregateOffer',
    'offerCount'   => count($offers),
    'lowPrice'     => $prices[0] ?? 0,
    'highPrice'    => end($prices) ?? 0,
    'priceCurrency'=> 'USD',
  ];
  $catalog = [
    '@type'           => 'OfferCatalog',
    'name'            => 'Amiri Motion Pricing Plans',
    'itemListElement' => $offers,
  ];
  $org = [
    '@context'=>'https://schema.org',
    '@graph'=> [
      array_merge(['@type'=>'Organization','name'=>'Amiri Motion','url'=>home_url(),'logo'=>'https://amirimotion.com/wp-content/uploads/logo.png'], []),
      $catalog,
      $agg
    ]
  ];
  echo '<script type="application/ld+json">' . wp_json_encode($org, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) . '</script>' . "\n";

  // **FAQPage Schema**
  $faqSchema = ['@context'=>'https://schema.org','@type'=>'FAQPage','mainEntity'=>[]];
  foreach($faqs as $f) {
    $faqSchema['mainEntity'][] = [
      '@type'=>'Question',
      'name'=> $f[0],
      'acceptedAnswer'=>['@type'=>'Answer','text'=> $f[1]],
    ];
  }
  echo '<script type="application/ld+json">' . wp_json_encode($faqSchema, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) . '</script>' . "\n";
});
get_header();
?>

<main class="pricing-archive">

  <header class="archive-header" style="text-align:center; padding:2rem 1rem;">
    <h1>Our Pricing Plans</h1>
    <p>Transparent, tiered vid eo editing &amp; video production &amp; motion graphics packages—perfect for small businesses, agencies, and creators.</p>
  </header>

  <?php
  // --- 2. Loop by Taxonomy Term ------------------------
  $order = ['video-editing','motion-graphics'];
  foreach( $order as $slug ) :
    $term = get_term_by('slug',$slug,'plan_type');
    if( ! $term ) continue;

    $q = new WP_Query([
      'post_type' => 'pricing_plan',
      'tax_query' => [[
        'taxonomy'=>'plan_type','field'=>'slug','terms'=>$slug
      ]],
      'posts_per_page'=>-1
    ]);

    if( $q->have_posts() ) : ?>

      <section class="pricing-type-group" data-plan-type="<?php echo esc_attr($slug); ?>">
        <h2 style="text-transform:capitalize; padding:1rem;"><?php echo esc_html($term->name); ?> Packages</h2>
        <div class="pricing-grid" style=" grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:1.5rem;">

          <?php while($q->have_posts()): $q->the_post(); 
            $pd       = get_post_meta(get_the_ID(),'_price_durations',true);
            $features = get_post_meta(get_the_ID(),'_features',true);
            $btnText  = get_post_meta(get_the_ID(),'_button_text',true);
          ?>
          <article class="pricing-card" data-durations="<?php echo esc_attr(wp_json_encode($pd)); ?>"
                     data-youtube-example-id="<?php echo esc_attr($youtube_example); ?>"
                     data-drive-example-id="<?php echo esc_attr($drive_example); ?>">
            <a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
            <ul class="plan-features">
              <strong>Benefits:</strong>
              <?php foreach(explode("\n",$features) as $feat) if(trim($feat)): ?>
                <li><?php echo esc_html($feat); ?></li>
              <?php endif; ?>
            </ul>
            <strong>Price:</strong>
            <div class="price-duration-list" style="margin:0.5rem 0;">
              <?php foreach($pd as $opt): ?>
                <div class="price-duration-item" 
                     data-duration="<?php echo esc_attr($opt['duration']); ?>" 
                     data-price="<?php echo esc_attr($opt['price']); ?>"
                     style="cursor:pointer; padding:0.5rem; border:1px solid #444; border-radius:4px; display:inline-block; margin:4px;">
                  <?php echo esc_html("{$opt['duration']} – {$opt['price']}"); ?>
                </div>
              <?php endforeach; ?>
            </div>
            <button class="cta-modern open-form" 
                    data-plan="<?php the_title(); ?>"
                    style="background:#10E5FF; color:#00002c; padding:0.75rem 1.5rem; border:none; border-radius:4px;">
              <?php echo esc_html($btnText ?: 'Choose Plan'); ?>
            </button>
          </article>
          <?php endwhile; wp_reset_postdata(); ?>

        </div>
      </section>

    <?php endif;
  endforeach;
  ?>

  <!-- 3. Custom & Enterprise -->
  <section class="pricing-custom" style="text-align:center; margin:3rem 0;">
    <h2>Need something custom?</h2>
    <p>Request a bespoke quote for enterprise volumes or ongoing monthly support.</p>
    <button class="cta-modern" onclick="location.href='https://amirimotion.com/index.php/contact-us'" 
            style="background:#10E5FF; color:#00002c; padding:0.75rem 1.5rem; border:none; border-radius:4px;">
      Contact Us for a Quote
    </button>
  </section>

  <!-- 4. FAQs -->
  <section class="pricing-faq" style="max-width:700px; margin:0 auto 3rem;">
    <h2>Frequently Asked Questions</h2>
    <?php foreach($faqs as $f): ?>
      <details style="margin:1rem 0; border:1px solid #444; border-radius:4px; padding:0.75rem;">
        <summary style="cursor:pointer; font-weight:600;"><?php echo esc_html($f[0]); ?></summary>
        <p style="margin-top:0.5rem;"><?php echo esc_html($f[1]); ?></p>
      </details>
    <?php endforeach; ?>
  </section>

  <!-- 5. Testimonial -->
  <section class="pricing-testimonial" style="text-align:center; margin:3rem 0;">
    <blockquote style="font-style:italic;">“AmiriMotion’s motion graphics gave our brand an instant polish—highly recommended!”</blockquote>
    <cite>— Bamboo Grove</cite>
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

  <!-- 6. Contact Modal (your existing partial) -->
  <?php locate_template('template-parts/modal-contact-form.php', true, false); ?>

</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const modal     = document.querySelector('.form-modal-overlay');
  const inpPlan   = document.querySelector('#selected-plan');
  const inpPrice  = document.querySelector('#selected-price');
  const inpType   = document.querySelector('#selected-plan-type');
  const selDur    = document.querySelector('#duration-select');

  // 1) Price-duration click → select price & duration
  document.querySelectorAll('.price-duration-item').forEach(el => {
    el.addEventListener('click', () => {
      // clear & highlight
      el.parentNode.querySelectorAll('.price-duration-item.active')
        .forEach(a => a.classList.remove('active'));
      el.classList.add('active');

      inpPrice.value = el.dataset.price;
      selDur.value    = el.dataset.duration;

      // Plan & Type
      const card     = el.closest('.pricing-card');
      inpPlan.value  = card.querySelector('h3').innerText;
      inpType.value  = card.closest('[data-plan-type]')?.dataset.planType || 'custom';
    });
  });

  // 2) “Choose Plan” & “Contact Us for a Quote”
  document.querySelectorAll('.open-form').forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();
      inpPlan.value  = btn.dataset.plan;
      // if opening from a plan card, pick up its type
      inpType.value  = btn.closest('[data-plan-type]')?.dataset.planType || 'custom';
      modal.style.display = 'flex';
    });
  });

  // 3) Close modal
  document.querySelector('.modal-close').addEventListener('click', () => {
    modal.style.display = 'none';
  });
});
</script>
<?php get_footer(); ?>
