# Online Food Blog

## Project Scenario Summary

**Online Food Blog** is a web-based application that helps people discover restaurants, explore menu items, and share opinions about food. The site presents venues with location, area, background, and goals, plus detailed dishes with descriptions and prices—so visitors can learn about places to eat and members can post reviews and longer food stories.

The system works like a small food content platform with two registered roles plus a public (guest) experience:

**Admin** — Controls the platform: manages restaurants and menu items (full CRUD), uploads food images, adds new admin or member accounts, removes member profiles, and moderates reviews on menu items and all Food Experience posts and comments.

**Member** — Registered user who can browse and search restaurants, filter by location/area/price, post and delete their own reviews on food items and restaurants, publish descriptive posts on the **Food Experience** page, and comment on others’ experiences.

**Visitor (non-registered)** — Sees a simplified home page with encouragement to register or log in; can view restaurants, menu items, and Food Experience posts in read-only mode but cannot post reviews or comments.

**Typical workflow:** Admin adds restaurants and menu items → visitors and members browse listings → a member posts a review or Food Experience story → admin can remove inappropriate content or member accounts if needed.

This project was built as **Web Technologies — Project 03**, following a shared database schema and PHP MVC structure with security, validation, and AJAX features required by the assignment. Production uses Supabase PostgreSQL.

---

## Technologies & Topics Used

The project combines front-end, back-end, database, and security practices taught in web technologies courses.

### Front-End

| Topic | How it is used in this project |
|-------|--------------------------------|
| **HTML5** | Semantic page structure, forms, tables, navigation, restaurant/menu cards, admin panels, Food Experience blog layout |
| **CSS3** | Layout (Flexbox, CSS Grid), custom properties (`:root` variables), responsive design (`@media`), food-themed UI (cards, badges, alerts, hero section, stats grid) |
| **JavaScript** | Client-side form validation (register, profile, admin forms), AJAX (`fetch`) for live search/filter, reviews, comments, and admin actions without full page reload |

### Back-End

| Topic | How it is used in this project |
|-------|--------------------------------|
| **PHP** | Server-side logic, session management, routing, controllers, models, views |
| **MVC pattern** | Separation into `controllers/`, `models/`, `views/`, `config/` |
| **PDO (PostgreSQL)** | Database connection with prepared statements (SQL injection prevention) |
| **Sessions & cookies** | Login state, roles (`admin` / `member`), “Remember Me” (30 days), CSRF tokens |
| **File upload** | Profile pictures and menu item images with server-side MIME (JPEG/PNG) and size checks (max 2 MB) |
| **Password security** | `password_hash()` on register; `password_verify()` on login |

### Database

| Topic | How it is used in this project |
|-------|--------------------------------|
| **PostgreSQL** | Supabase database; see `DEPLOYMENT_GUIDE.md` |
| **Tables** | `users`, `restaurants`, `menu_items`, `reviews`, `restaurant_reviews`, `food_experience_posts`, `food_experience_comments` |
| **Keys & integrity** | Foreign keys, `ON DELETE CASCADE` where required, unique email on users |

### Other Web Topics

| Topic | How it is used in this project |
|-------|--------------------------------|
| **AJAX / JSON** | API-style endpoints return JSON for search, reviews, restaurant reviews, comments, admin moderation |
| **XSS prevention** | `htmlspecialchars()` (via `Security::e()`) when displaying user content |
| **CSRF protection** | Hidden token on forms; verified on POST requests |
| **Responsive UI** | Mobile-friendly navigation toggle and responsive grids |
| **Apache (XAMPP)** | Local hosting; `index.php` as front controller with `?route=` URLs (no `.htaccess` required) |

---

## Accounts

`database.pgsql.sql` installs no accounts. Public registration creates members only. After registering your own account, promote it in the Supabase SQL Editor as described in `DEPLOYMENT_GUIDE.md`. A logged-in admin can then create additional admins or members.

---

## How to Run the Project

Follow `DEPLOYMENT_GUIDE.md` for local PostgreSQL, Render and Vercel setup.

**URLs:** Pages use `index.php?route=/path` (e.g. `index.php?route=/login`). Navigation links are generated automatically; you do not need `.htaccess`.

---

## Main Modules (Assignment Tasks)

| Task | Module | Main features |
|------|--------|---------------|
| **Task 1** | Auth & profile | Register (admin/member), login, remember me, profile update, visitor home, browse restaurants & menu items |
| **Task 2** | Admin content | Restaurant & menu item CRUD, image upload, admin dashboard stats |
| **Task 3** | Member browse | AJAX search/filter (location, area, price), post/delete reviews on food items and restaurants |
| **Task 4** | Food Experience | Descriptive blog posts, comments (AJAX), admin removal of members, reviews, posts, and comments |

---

## Project Folder Overview

```
config/         → App settings, database, routes
controllers/    → Page logic and JSON APIs (ApiController, AdminApiController)
models/         → Database queries (PDO)
views/          → HTML/PHP templates (admin, auth, layouts, partials)
includes/       → Bootstrap, Auth, Security helpers
public/css/     → Stylesheets (base, components, responsive)
public/js/      → Validation and AJAX scripts
public/uploads/ → Local profile and menu images (ignored by Git)
database.pgsql.sql → Supabase schema
index.php       → Application entry point
.gitignore      → Excludes local images and environment secrets
```

---

## Security Features (Summary)

- Prepared statements for all database queries  
- Hashed passwords (never stored as plain text)  
- CSRF tokens on form submissions  
- Escaped output to reduce XSS risk  
- Role-based access (`admin` / `member`; visitors unauthenticated)  
- Validated file uploads (type and size)  
- Secure remember-me token stored as HMAC hash  

---

See `DEPLOYMENT_GUIDE.md` for the current database and deployment steps.
