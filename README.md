# DIB Productions – Full Stack E-Commerce

> **Student:** Mohamad Dib Hamidie | **ID:** 20242022173
> **Course:** Web Technologies & Full Stack Development
> **Stack:** Laravel 11 · React 18 · Tailwind CSS · Vite · SQLite/MySQL · Stripe

---

## Project Overview

DIB Productions is a fully functional e-commerce web application built with Laravel (back-end) and React (front-end components), following the 13-week course curriculum. The shop sells premium electronics, audio equipment, clothing, and accessories.



## Tech Stack

| Layer       | Technology                              |
|-------------|-----------------------------------------|
| Backend     | PHP 8.2, Laravel 11, Laravel Breeze     |
| Frontend    | Blade, React 18, Tailwind CSS 3         |
| Bundler     | Vite + @vitejs/plugin-react             |
| Database    | SQLite (dev) / MySQL (production)       |
| Auth        | Laravel Breeze (session) + Sanctum (API)|
| Payment     | Stripe (sandbox)                        |
| Email       | Laravel Notifications (Mailable)        |

---

## Installation & Setup

```bash
# 1. Clone repository
git clone https://github.com/YOUR_USERNAME/dib-productions.git
cd dib-productions

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies
npm install

# 4. Environment setup
cp .env.example .env
php artisan key:generate

# 5. Database setup (SQLite by default)
touch database/database.sqlite
php artisan migrate --seed

# 6. Storage symlink
php artisan storage:link

# 7. Build frontend assets
npm run build
# OR for development with hot reload:
npm run dev

# 8. Start the server
php artisan serve
```

The app will be available at `http://localhost:8000`

### Default Credentials

| Role     | Email                        | Password   |
|----------|------------------------------|------------|
| Admin    | admin@dibproductions.com     | password   |
| Customer | customer@test.com            | password   |

---

## Project Structure

```
dib-productions/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ProductController.php       # Shop browsing + search API
│   │   │   ├── CartController.php          # Session cart + API endpoints
│   │   │   ├── CheckoutController.php      # Multi-step checkout + Stripe
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── ProductController.php   # Full CRUD
│   │   │   │   └── OrderController.php     # Order management
│   │   │   └── Api/
│   │   │       └── ProductController.php   # REST API (Sanctum)
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Order.php
│   │   └── OrderItem.php
│   └── Notifications/
│       └── OrderConfirmation.php
├── database/
│   ├── migrations/                         # 4 migration files
│   └── seeders/
│       └── DatabaseSeeder.php              # 2 users, 4 categories, 10 products
├── resources/
│   ├── views/
│   │   ├── layouts/app.blade.php           # Main layout
│   │   ├── home.blade.php
│   │   ├── products/{index,show,_card}.blade.php
│   │   ├── cart/index.blade.php
│   │   ├── checkout/{index,payment,success}.blade.php
│   │   └── admin/{dashboard, products/, orders/}
│   ├── js/
│   │   ├── app.jsx                         # React entry point
│   │   └── components/
│   │       ├── ProductFilter.jsx           # Live search + filter (Week 10)
│   │       ├── CartPreview.jsx             # Slide-out cart drawer (Week 10)
│   │       ├── CartIcon.jsx
│   │       └── StripeCheckout.jsx          # Stripe payment form (Week 9)
│   └── css/app.css
├── routes/
│   ├── web.php                             # All web routes
│   └── api.php                             # REST API routes
├── vite.config.js
├── tailwind.config.js
└── composer.json
```

---

## Feature Checklist (by Week)

| Week | Milestone | Status |
|------|-----------|--------|
| 1 | Dev environment setup (Laravel, Node, Git) | ✅ |
| 2 | HTML/CSS – Homepage & product listing | ✅ |
| 3 | JavaScript – Dynamic search & filter | ✅ |
| 4 | Laravel scaffolding, DB connection, .env | ✅ |
| 5 | MVC – Product listing & detail pages | ✅ |
| 6 | Eloquent ORM – Product, Category, Order models + relationships | ✅ |
| 7 | Auth – Laravel Breeze, roles (customer/admin) | ✅ |
| 8 | E-Commerce – Product catalog, session cart, database cart | ✅ |
| 9 | Checkout – Multi-step, Stripe sandbox, Order model, Mailable | ✅ |
| 10 | React – ProductFilter + CartPreview as React components | ✅ |
| 11 | Admin Panel – Full CRUD for Products, Categories, Orders | ✅ |
| 12 | REST API – Sanctum auth, `/api/products` endpoint | ✅ |
| 13 | Deployment ready – Seeder, storage link, production build | ✅ |

---

## Key Routes

```
GET  /                          Homepage (featured products)
GET  /products                  Product listing (filter/search)
GET  /products/{product}        Product detail
GET  /search                    AJAX search API for React
GET  /cart                      Cart page
POST /cart/add                  Add to cart
GET  /checkout                  Checkout step 1 (shipping)
POST /checkout/payment          Checkout step 2 (payment)
POST /checkout/store            Place order
GET  /checkout/success/{order}  Order confirmation

# Admin (requires admin role)
GET  /admin                     Dashboard
/admin/products                 CRUD
/admin/orders                   Order management

# REST API
GET  /api/products              Paginated product list
GET  /api/products/{id}         Single product
GET  /api/user                  Auth user (Sanctum token)
```

---

## Design

- **Color scheme:** Black `#0A0A0A` / Red `#DC2626`
- **Typography:** Bebas Neue (display) + DM Sans (body)
- **Theme:** Dark luxury e-commerce — bold, minimal, professional

---

*DIB Productions © 2024 – Mohamad Dib Hamidie (ID: 20242022173)*
