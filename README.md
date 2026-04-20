# Testimonials - Contensio Plugin

Collect testimonials via a public submission form or add them manually in the admin. Review and approve each one before it goes live. Display approved testimonials anywhere on your site as a responsive grid or an animated carousel.

---

## Features

- **Public submission form** - embeddable form with name, role, company, optional star rating, and message
- **Moderation queue** - all submitted testimonials start as `pending`; admins approve or reject each one
- **Manual entry** - admins can add testimonials directly without going through the form
- **Status tabs** - filter by Pending / Approved / Rejected in the admin list
- **Grid display** - responsive 1, 2, or 3-column grid of testimonial cards
- **Carousel display** - Alpine.js animated carousel with prev/next arrows, dot indicators, and optional auto-play
- **Star ratings** - optional 1–5 star rating per testimonial; displayed as filled stars
- **Initials avatar** - auto-generated coloured avatar circle when no photo URL is provided
- **Avatar URL** - optionally link a portrait photo per testimonial

---

## How it works

1. A visitor fills in the submission form and clicks Submit.
2. A `pending` testimonial is created and appears in the admin queue.
3. An admin reviews it in **Tools → Testimonials** and clicks **Approve** or **Reject**.
4. Approved testimonials appear in the grid/carousel widgets.

Admins can also create testimonials directly from the admin form - useful for importing existing social proof. Manually created testimonials default to `Approved`.

---

## Installation

### Via admin panel

Go to **Plugins** in your Contensio admin, find **Testimonials**, and click **Install**.

### Via Composer

```bash
composer require contensio/plugin-testimonials
```

The plugin is auto-discovered. Go to **Plugins** in the admin and enable it. The migration runs automatically on first enable.

---

## Embedding

### Submission form

```blade
@include('testimonials::partials.submit-form')
```

Shows a success message after submission. Errors display inline.

### Grid

```blade
@include('testimonials::partials.testimonials-grid')
```

Optional parameters:

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `limit` | int | 12 | Maximum number of testimonials to show |
| `cols` | int | 3 | Number of columns (1, 2, or 3) |
| `showRating` | bool | true | Whether to display star ratings |

```blade
@include('testimonials::partials.testimonials-grid', ['limit' => 6, 'cols' => 2])
```

### Carousel

```blade
@include('testimonials::partials.testimonials-carousel')
```

Optional parameters:

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `limit` | int | 10 | Maximum number of testimonials to rotate |
| `autoplay` | bool | true | Auto-advance slides |
| `interval` | int | 5000 | Auto-advance interval in milliseconds |
| `showRating` | bool | true | Whether to display star ratings |

```blade
@include('testimonials::partials.testimonials-carousel', ['autoplay' => false])
```

**Requirements for the carousel to work:**

- Alpine.js must be loaded on the page (included in all Contensio default themes).
- A `<meta name="csrf-token">` tag must be present in the page `<head>` (included in all Contensio default themes).

---

## Admin

### Testimonials list (`/account/testimonials`)

Lists all testimonials with status filter tabs. Each row shows the person's name, a preview of the testimonial text, the star rating, status badge, and submission date.

**Actions per row:**
- **Approve** (✓) - sets status to `approved`
- **Reject** (✗) - sets status to `rejected`
- **Edit** - open the edit form
- **Delete** - permanently delete

### Create / edit form

| Field | Description |
|-------|-------------|
| **Name** | Testimonial author's name (required) |
| **Role / Title** | Job title or role (optional) |
| **Company** | Organisation name (optional) |
| **Avatar URL** | Portrait photo URL (optional; initials shown if blank) |
| **Message** | The testimonial body (required, up to 2,000 characters) |
| **Rating** | 1–5 star rating (optional) |
| **Status** | Pending / Approved / Rejected |

---

## Routes

| Method | URL | Description |
|--------|-----|-------------|
| `GET` | `/account/testimonials` | Admin testimonials list |
| `GET` | `/account/testimonials/create` | Add testimonial form |
| `POST` | `/account/testimonials` | Save new testimonial |
| `GET` | `/account/testimonials/{id}/edit` | Edit testimonial |
| `PUT` | `/account/testimonials/{id}` | Update testimonial |
| `POST` | `/account/testimonials/{id}/approve` | Approve testimonial |
| `POST` | `/account/testimonials/{id}/reject` | Reject testimonial |
| `DELETE` | `/account/testimonials/{id}` | Delete testimonial |
| `POST` | `/testimonials/submit` | Public submission form handler |

---

## Database

Creates one table: `testimonials`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `name` | varchar(150) | Author name |
| `role` | varchar(150) | Role or title (nullable) |
| `company` | varchar(150) | Company or organisation (nullable) |
| `avatar_url` | varchar(500) | Portrait photo URL (nullable) |
| `content` | text | Testimonial body |
| `rating` | tinyint | Star rating 1–5 (nullable) |
| `status` | enum | `pending`, `approved`, `rejected` |
| `source` | varchar(50) | Origin: `form` or `manual` |
| `ip_address` | varchar(45) | Submitter IP (nullable) |
| `created_at` / `updated_at` | timestamp | |

---

## Requirements

- PHP 8.2+
- Contensio 2.0+
- Alpine.js (carousel only; included in all Contensio default themes)

---

## License

AGPL-3.0-or-later - see [LICENSE](LICENSE).
