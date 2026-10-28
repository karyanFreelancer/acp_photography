ACP Photography UI patch

- Header account dropdown replaces second-row account buttons.
- Floating Photoowl button on desktop, full-width bottom control on mobile.
- Profile bookings use responsive cards and clearly labelled pending statuses.
- Admin overview highlights pending enquiries.

IMPORTANT: Existing login and registration backend is retained. Admin access requires a user with users.role=admin AND a valid login session; admin_users may be a separate table used by other admin login flows. Do not change role for arbitrary users. Only promote a verified owner account, ideally via a protected administrative action.

For broken logos: unzip ALL branding archives into the same ACP-Photography directory; verify web/assets/reference/acp-horizontal.png exists and loads at http://localhost/ACP-Photography/web/assets/reference/acp-horizontal.png.

This patch has PHP syntax checks, but has not been tested against a live XAMPP database or browser.
