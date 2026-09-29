# Dars Luxury Cars

Chauffeured luxury car rental website (PHP + MySQL).

## Pages
- `index.php` — Home
- `bentley-mulsanne.php` — 2018 Bentley Mulsanne fleet detail page
- `booking.php` — Booking request form (no payment)
- `contact.php` — Contact form
- `thank-you.php` — Confirmation page after a form submits
- `admin/` — Admin panel (see below)

## Setup

1. Create the database and import the schema:

   ```bash
   mysql -u root -p -e "CREATE DATABASE darsluxurycars CHARACTER SET utf8mb4;"
   mysql -u root -p darsluxurycars < sql/schema.sql
   ```

   If you already had this project's database from before the admin panel existed, run
   the migration instead of the full schema so you don't lose existing bookings:

   ```bash
   mysql -u root -p darsluxurycars < sql/migration_001_admin_and_status.sql
   ```

2. Edit [`includes/db.php`](includes/db.php) with your MySQL host, database name, username and password.

3. Create your admin login (run from the project root — this only works from the command line):

   ```bash
   php scripts/create-admin.php <username> <password>
   ```

   Run it again any time with the same username to reset that admin's password.

4. Run locally with PHP's built-in server from the project root:

   ```bash
   php -S localhost:8000
   ```

   Then open http://localhost:8000/index.php

5. Deploy to any standard PHP + MySQL host (Apache/Nginx + PHP 8, or shared hosting with cPanel).

## Admin Panel

Visit `/admin/login.php` to sign in. From there:

- **Dashboard** (`/admin/index.php`) — booking counts by status, upcoming approved pickups, most-requested service, and recent activity.
- **Bookings** (`/admin/bookings.php`) — filterable list of every booking request. Approve or decline directly from the list, or open a booking for full details.
- **Approving a booking** updates its status and automatically emails the customer a confirmation with their trip details. Declining just updates the status — no email is sent.
- **Messages** (`/admin/messages.php`) — read-only view of everything submitted through the Contact Us form.
- **Change Password** (`/admin/change-password.php`) — update your own login.

### Email delivery

Confirmation emails are sent with PHP's built-in `mail()` function — no third-party
service required, but a few things to know:

- The **From** address is set in [`includes/mail-config.php`](includes/mail-config.php) as
  `reservations@darsluxurycars.com`. Change this to an address on your actual hosting
  domain once you deploy. Sending "From" a Gmail address will usually get flagged as
  spoofed and land in spam, since your server isn't authorized to send on Gmail's behalf.
- `MAIL_REPLY_TO` is set to `connectwithdars@gmail.com` — customer replies to the
  confirmation email will land there.
- `mail()` requires the hosting server to have a working mail transfer agent (most
  shared hosting/cPanel accounts already do). On your own VPS or in local development,
  this may need additional setup, or you can swap in an SMTP library (e.g. PHPMailer)
  inside `includes/mailer.php` later without touching the rest of the admin panel.
- If an approval's email fails to send, the booking is still marked approved and the
  admin sees a warning telling them to follow up with the customer manually.

## Adding images

Every photo spot on the site is currently a dashed placeholder box labeled with the
recommended image size (e.g. "Add image — 1200 × 900px"). Drop your images into
`assets/images/` and replace each `<div class="img-placeholder">...</div>` block with
an `<img>` tag pointing at your file, for example:

```html
<img src="/assets/images/bentley-mulsanne-hero.jpg" alt="2018 Bentley Mulsanne">
```

Placeholder locations:
- `index.php` — hero image, featured fleet card
- `bentley-mulsanne.php` — gallery (3 images)
- `contact.php` — map/location image

## Data collected

- **Bookings** are stored in the `bookings` table (see `sql/schema.sql`).
- **Contact messages** are stored in the `contact_messages` table.

No payment information is collected or stored anywhere on the site.
