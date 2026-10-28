<?php
require_once __DIR__.'/../../config/config.php';
// All public pages render this footer once. The marquee sits ABOVE it.
require __DIR__.'/marquee.php';
?>
<footer class="acp-shared-footer" id="acpFooter">
 <div class="foot-grid">
  <div class="acp-footer-intro"><a class="brand" href="index.php" aria-label="ACP Photography homepage"><img src="assets/reference/acp-horizontal.png" alt="ACP Photography" class="acp-brand-logo"></a><p>Photography, films and visual stories made with care.</p></div>
  <div><h4>Explore</h4><a href="about.php">About</a><a href="gallery.php">Gallery</a><a href="films.php">Films</a><a href="services.php">Services</a></div>
  <div><h4>Plan your shoot</h4><a href="pricing.php">Pricing</a><a href="contact.php">Contact</a></div>
  <div><h4>Connect</h4><a href="https://www.instagram.com/acp_photography_02/" target="_blank" rel="noopener noreferrer external">Instagram ↗</a></div>
 </div>
 <div class="copyright">© <?=date('Y')?> ACP Photography. All rights reserved.</div>
</footer>
