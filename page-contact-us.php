<?php
/**
 * Template Name: Contact Us Page
 * Description: Displays the Contact Us form and handles success messages.
 * (Make sure the page slug is “contact-us”)
 */
get_header();
?>
  
  
  <section class="hero">
    <div class="container">
      <div class="hero-content">
        <h1>Request Your Free Video Sample</h1>
        <p>Experience the power of Amiri Motion’s video editing & motion graphics — no commitment required.</p>
      </div>
      <div class="hero-form">
        <?php
        if ( isset( $_GET['contact'] ) && $_GET['contact'] === 'success' ) {
            echo '<p class="cu-success">Thank you for your message!</p>';
          }
  
          // Render the form via shortcode:
          echo do_shortcode( '[cu_contact_form]' );
          ?>
      </div>
    </div>
</section>

  <main>
    <div class="primary-bg">
      <div class="container">
        <section>
          <div class="section-title"><h2>What We Offer</h2></div>
          <ul class="grid grid-4">
            <li class="card"><h3>Professional Video Editing Services</h3><p>From seamless transitions to perfect color grading, our experts ensure your story shines.</p></li>
            <li class="card"><h3>Custom Motion Graphics</h3><p>Stand out with bespoke animations designed just for your brand.</p></li>
            <li class="card"><h3>Affordable Video Services</h3><p>Get top‑tier results without the premium price tag—ask about our flexible packages.</p></li>
            <li class="card"><h3>Freelance Video Editor</h3><p>Need a quick turnaround or ongoing support? Our seasoned freelance video editor is ready to jump in.</p></li>
          </ul>
        </section>

        <section>
          <div class="section-title"><h2>Why We Offer Free Video Samples</h2></div>
          <ul class="grid grid-3">
            <li class="card"><h3>Risk-Free Exploration</h3><p>Try our quality-first approach without obligation.</p></li>
            <li class="card"><h3>Customized for You</h3><p>We create a sample tailored to your brand and goals.</p></li>
            <li class="card"><h3>Expert Insights</h3><p>Receive professional recommendations alongside your sample.</p></li>
          </ul>
        </section>

        <section>
          <div class="section-title"><h2>What You’ll Receive</h2></div>
          <ul class="grid grid-4">
            <li class="card"><h3>Custom Short Video</h3><p>A personalized sample video showcasing our capabilities.</p></li>
            <li class="card"><h3>Brand Analysis</h3><p>In-depth review of your brand’s visual potential.</p></li>
            <li class="card"><h3>Pro Recommendations</h3><p>Actionable tips to optimize your motion graphics strategy.</p></li>
            <li class="card"><h3>Workflow Insights</h3><p>See our creative process from concept to delivery.</p></li>
          </ul>
        </section>

        <section>
          <div class="section-title"><h2>Who Can Benefit?</h2></div>
          <ul class="grid grid-4">
            <li class="card"><h3>Entrepreneurs</h3><p>Make a splash in your industry with high-impact visuals.</p></li>
            <li class="card"><h3>Marketing Teams</h3><p>Create engaging content for campaigns and social.</p></li>
            <li class="card"><h3>Agencies</h3><p>Offer cutting-edge video solutions to your clients.</p></li>
            <li class="card"><h3>Business Owners</h3><p>Revitalize your brand image with professional motion graphics.</p></li>
          </ul>
        </section>

        <section>
          <div class="section-title"><h2>How It Works</h2></div>
          <ul class="grid grid-3">
            <li class="card"><h3>1. Submit Form</h3><p>Share your details and project brief.</p></li>
            <li class="card"><h3>2. We Create Sample</h3><p>Our team crafts a custom video sample for you.</p></li>
            <li class="card"><h3>3. Review & Decide</h3><p>Evaluate your sample and choose next steps.</p></li>
          </ul>
        </section>
      </div>
    </div>

    <div class="secondary-bg">
      <div class="container">
        <section>
          <div class="section-title"><h2>Ready to Transform Your Vision into Stunning Video?</h2></div>
          <p class="section-description">At AmiriMotion, we make it easy to hire video editor talent, access industry‑leading video editing services, and partner with a full‑service motion graphics agency. Whether you need polished cuts, eye‑catching animations, or complete post‑production support, our team delivers motion graphics services tailored to your goals—and your budget.</p>
        </section>

        <section>
          <div class="section-title"><h2>Get Your Custom Quote Now</h2></div>
          <p class="section-description">Tell us about your project—whether you’re looking for video editing quotes, custom motion graphics, or a full suite of production services. Simply complete the form above, and we’ll send you a free sample video to show you the quality of our works.</p>
        </section>

        <section class="faqs">
          <h2>FAQs</h2>
          <div class="faq-item">
            <details>
              <summary>What’s included in the free sample video?</summary>
              <p>We tailor a short video aligned with your brand and goals, using your assets or ours.</p>
            </details>
          </div>
          <div class="faq-item">
            <details>
              <summary>How long until I receive it?</summary>
              <p>Usually within 72 working hours after your request.</p>
            </details>
          </div>
          <div class="faq-item">
            <details>
              <summary>Am I obligated to purchase?</summary>
              <p>No, it’s completely commitment-free.</p>
            </details>
          </div>
        </section>
      </div>
    </div>
  </main>

  <?php get_footer(); ?>