<footer class="site-footer">
  <div class="footer-links">
    <a href="<?php echo esc_url(home_url('/about/')); ?>">About</a>
    <a href="<?php echo esc_url(home_url('/privacy-and-cookies/')); ?>">Privacy policy and cookies</a>
    <a href="<?php echo esc_url(home_url('/terms-of-use/')); ?>">Terms and conditions</a>
  </div>
  <div>© 2025 Spanish Nova – All rights reserved</div>
</footer>
<div class="sn-contact" data-sn-contact>
  <div class="sn-contact-options" id="sn-contact-options" hidden>
    <a class="sn-contact-link" href="<?php echo esc_url('https://wa.me/541136764555?text=Hi%21%20I%27m%20interested%20in%20Spanish%20lessons.'); ?>" target="_blank" rel="noopener noreferrer" aria-label="Contact us on WhatsApp">
      <span aria-hidden="true">☎</span> WhatsApp
    </a>
    <button class="sn-contact-link sn-contact-email-trigger" type="button" aria-controls="sn-contact-form-panel" aria-expanded="false">
      <span aria-hidden="true">✉</span> Email
    </button>
  </div>
  <section class="sn-contact-panel" id="sn-contact-form-panel" role="dialog" aria-label="Contact SpanishNova" hidden>
    <div class="sn-contact-panel-header">
      <h2>Contact SpanishNova</h2>
      <button class="sn-contact-panel-close" type="button" aria-label="Close contact form">×</button>
    </div>
    <p>Have a question? Send us a message.</p>
    <form class="sn-contact-form" data-endpoint="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
      <input type="hidden" name="action" value="spanishnova_contact">
      <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce('spanishnova_contact')); ?>">
      <div class="sn-contact-hp" aria-hidden="true">
        <label for="sn-contact-website">Website</label>
        <input id="sn-contact-website" type="text" name="website" tabindex="-1" autocomplete="off">
      </div>
      <label for="sn-contact-name">Name</label>
      <input id="sn-contact-name" name="name" autocomplete="name" required maxlength="120">
      <label for="sn-contact-email">Email</label>
      <input id="sn-contact-email" name="email" type="email" autocomplete="email" required maxlength="254">
      <label for="sn-contact-message">Message</label>
      <textarea id="sn-contact-message" name="message" required maxlength="3000" rows="4"></textarea>
      <p class="sn-contact-feedback" role="status" aria-live="polite"></p>
      <button class="sn-contact-submit" type="submit">Send message</button>
    </form>
  </section>
  <button class="sn-contact-toggle" type="button" aria-label="Open contact options" aria-expanded="false" aria-controls="sn-contact-options">
    <svg viewBox="0 0 24 24" width="25" height="25" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M21 11.5a8.5 8.5 0 0 1-8.5 8.5 9 9 0 0 1-4-.9L3 21l1.9-5.5a9 9 0 0 1-.9-4A8.5 8.5 0 0 1 12.5 3H13a8.5 8.5 0 0 1 8 8.5Z"/></svg>
  </button>
</div>
<?php wp_footer(); ?>
</body>
</html>