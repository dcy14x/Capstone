# 📋 M-633 E-Commerce & POS System — Overall Overview

> **Purpose:** Quick-read reference for the Capstone Defense Panel evaluation.
> **System Name:** M-633 E-Commerce & Point-of-Sale (POS) System

---

## 🎯 What Is This System?

A **full-stack web application** that digitizes and synchronizes both:
- 🛒 **Online retail operations** (E-Commerce storefront)
- 🖥️ **Physical store purchases** (Point-of-Sale terminal)

Inventory is shared and updated in **real-time** across both channels.

---

## 🏗️ System Architecture at a Glance

```
[ Customer / Supplier / Admin Browser ]
              │
              ▼
   ┌─────────────────────────┐
   │  DigitalOcean App       │
   │  Platform (PaaS/Docker) │
   │  Apache Web Server      │
   │  PHP 8.x MVC Backend    │
   └────────────┬────────────┘
                │  SSL (Port 25060)
                ▼
   ┌─────────────────────────┐
   │  DigitalOcean Managed   │
   │  MySQL 8 Database       │
   │  Cluster (Dedicated)    │
   └─────────────────────────┘
```

---

## 🛠️ Technology Stack

| Layer          | Technology                           |
|----------------|--------------------------------------|
| **Backend**    | PHP 8.x — Object-Oriented MVC        |
| **Frontend**   | HTML5, CSS3, Vanilla JavaScript      |
| **Database**   | MySQL 8 (InnoDB Engine, 12+ tables)  |
| **Web Server** | Apache (via DigitalOcean App Platform)|
| **Cloud Host** | DigitalOcean App Platform (PaaS)     |
| **DB Host**    | DigitalOcean Managed Database Cluster|
| **Security**   | BCRYPT hashing, SSL, Env Variables   |
| **CI/CD**      | GitHub `main` branch → Auto-deploy   |

---

## 🔐 Security Architecture

| Feature                  | How It Works                                                                 |
|--------------------------|------------------------------------------------------------------------------|
| **Password Security**    | PHP `password_hash()` with BCRYPT — mathematically irreversible              |
| **Session Auth (RBAC)**  | `$_SESSION['role']` checked on every protected page server-side             |
| **No Hardcoded Secrets** | `getenv('DB_PASSWORD')` — credentials injected from DigitalOcean at runtime |
| **SSL Database Link**    | PHP connects to DB over Port `25060` with encrypted SSL certificate          |

---

## 👥 Role-Based Access Control (RBAC)

| Role          | Value | Access & Permissions                                                             |
|---------------|-------|----------------------------------------------------------------------------------|
| **Customer**  | `0`   | Browse products, manage cart, checkout (GCash/COD), track orders                 |
| **Admin**     | `1`   | Full control: inventory, order fulfillment, payment verification, POS, audit logs|
| **Supplier**  | `2`   | View restock requests, fulfill deliveries, message store management              |

> ⚡ Even if a user manually types `/admin/dashboard.php` in the URL bar, the server checks their session role and **immediately redirects** them if unauthorized.

---

## 🗄️ Database Schema Overview (12+ Tables)

### 👤 Authentication & Profiles
| Table       | Purpose                                                     |
|-------------|-------------------------------------------------------------|
| `users`     | Central auth hub — email (unique), BCRYPT password, address |
| `suppliers` | Linked to `users` by `user_id`, stores corporate contact info|

### 📦 Inventory Control
| Table                      | Purpose                                                        |
|----------------------------|----------------------------------------------------------------|
| `products`                 | Master product catalog — pricing tiers, quantity tracking      |
| `inventory_reconciliation` | Records every restock event with supplier & cost details       |
| `stock_history`            | Full audit trail of all stock changes over time                |

### 💳 Transaction Engine (Dual-Front)
| Table                   | Channel  | Purpose                                              |
|-------------------------|----------|------------------------------------------------------|
| `orders`                | Online   | Shopping cart lifecycle, payment status, fulfillment |
| `customers_info`        | Online   | Delivery details tied to each order                  |
| `pos_transactions`      | Physical | Instant in-store sale records                        |
| `pos_transaction_items` | Physical | Line items per POS sale, change amount calculation   |

---

## ☁️ Cloud Infrastructure Highlights

### DigitalOcean App Platform
- Runs PHP app inside a **managed Docker container**
- Scales automatically with traffic
- Rebuilds and redeploys on every **GitHub `main` push** (CI/CD)

### DigitalOcean Managed Database
- Fully **decoupled** from the web server (not on the same hard drive)
- Accessed securely over **Port 25060** with SSL
- Handles MySQL 8 strict mode: `sql_require_primary_key` enforced

### Key Deployment Challenges Solved
1. **Linux Case-Sensitivity** — All SQL table names normalized to lowercase via regex scan
2. **Primary Key Enforcement** — Custom `setup_database.php` script to create tables correctly in the cloud
3. **Foreign Key Conflicts** — Used `SET FOREIGN_KEY_CHECKS = 0` during deployment to avoid lock issues

---

## 🔄 Core Business Logic — Omnichannel Inventory Sync

```
Online Checkout ──────┐
                       ├──► products.quantity DECREMENTS (real-time)
POS Terminal Sale ─────┘
```

Every transaction — whether online or physical — updates the **single shared stock value** in the `products` table. This is the core innovation of the system.

---

## 📊 Order Lifecycle (Online)

```
Cart → Checkout → Pending → Processing → Shipped → Delivered
                   │
                   └── Payment: GCash (manual verify) or COD
```

---

## 🏪 POS Transaction Flow (Physical)

```
Scan/Select Items → Calculate Total → Receive Cash → Compute Change → Record Sale
                                                                         │
                                                              Inventory auto-decrements
```

---

## 💡 Key Talking Points for the Panel

| Question Likely Asked                       | One-Line Answer                                                                  |
|---------------------------------------------|----------------------------------------------------------------------------------|
| Why DigitalOcean over cPanel?               | Enterprise containerization, isolated DB cluster, CI/CD, environment security    |
| How did you secure DB credentials?          | `getenv()` — no hardcoded passwords, injected from server memory at runtime      |
| How do you prevent unauthorized URL access? | PHP checks `$_SESSION['role']` server-side on every protected page               |
| What makes this "cloud-native"?             | Decoupled DB, ephemeral containers, SSL, auto-scaling, GitHub CI/CD              |
| How does real-time inventory work?          | Single `products.quantity` field, decremented by both online and POS transactions|

---

## 📁 Project Folder Structure

```
Capstone/
├── admin/          → Admin panel pages (dashboard, inventory, orders, POS)
├── api/            → JSON API endpoints
├── auth/           → Login, register, OTP pages
├── config/         → Database connection (uses environment variables)
├── controller/     → PHP controllers (MVC — all business logic lives here)
├── customer/       → Customer-facing storefront pages
├── includes/       → Shared components (navbar, session, header, footer)
├── maintenance/    → Supplier portal pages
├── sendphpmailer/  → Email/OTP sending via PHPMailer
├── setup_tools/    → Cloud DB setup & diagnostic scripts
└── index.php       → Application entry point
```

---

*Good luck at the defense tomorrow! 🎓*
