ACP PHOTOGRAPHY — PUBLIC WEBSITE UPDATE

Included pages:
- index.php (homepage with featured slider, marquee, portfolio, services, about, process, gallery, offers, reviews, social feed, client galleries, booking enquiry and FAQ)
- portfolio.php
- services.php
- about.php
- gallery.php
- offers.php
- reviews.php
- client-galleries.php
- _content-page.php (shared public page template)
- assets/css/style.css (responsive editorial design, slider, marquees, motion and page styles)
- assets/js/main.js (accessible slider controls, swipe support, GSAP scroll reveals, mobile menu)

INSTALLATION
1. Keep the web folder inside your existing ACP-Photography project so ../config/config.php remains available.
2. Replace the existing project web folder with this updated web folder (back up your old one first).
3. Ensure your existing database and config/config.php are intact.
4. Import database/migrations/2026_10_dashboard.sql from the full project if optional gallery/reviews/offers/social/client-gallery tables are missing.
5. Open http://localhost/ACP-Photography/web/ .

Missing optional content tables will now be logged and skipped by public page queries rather than causing the homepage to fail. Booking submissions still require the existing bookings table and its expected columns.

External GSAP/ScrollTrigger/Lenis scripts and Google Fonts require an internet connection. The main slider and responsive layout work without GSAP; reduced-motion preferences are respected.
