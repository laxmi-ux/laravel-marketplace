# Laravel Marketplace

A multi-category marketplace built with **Laravel 12, PHP, MySQL, Blade, and Tailwind CSS**. The platform connects buyers and sellers for properties, land and plots, building materials, products, services, and projects.

The application includes dedicated Buyer, Seller, and Admin modules for managing listings, requirements, enquiries, quotations, orders, payments, transactions, reviews, and notifications.

## Table of Contents

- [Project Overview](#project-overview)
- [Technology Stack](#technology-stack)
- [Features](#features)
- [Screenshots](#screenshots)
- [Order and Transaction Workflows](#order-and-transaction-workflows)
- [Project Structure](#project-structure)
- [Installation and Setup](#installation-and-setup)
- [Running the Application](#running-the-application)
- [Useful Commands](#useful-commands)
- [Security Notes](#security-notes)
- [Future Enhancements](#future-enhancements)
- [Developer](#developer)

## Project Overview

Laravel Marketplace is designed to support multiple business categories in one platform. Buyers can discover listings, contact sellers, submit requirements, request quotations, and manage orders or property transactions. Sellers can manage their business profiles, publish listings, respond to enquiries, and handle orders and transactions. Administrators manage users, listings, categories, payments, reviews, and application settings.

## Technology Stack

| Technology | Purpose |
|---|---|
| Laravel 12 | Backend framework |
| PHP 8.2+ | Server-side programming |
| MySQL | Database |
| Blade | Dynamic templates |
| HTML5, CSS3, JavaScript | Frontend development |
| Tailwind CSS | Responsive styling |
| Vite | Frontend asset bundling |
| Laravel Breeze | Authentication scaffolding |
| Composer | PHP dependencies |
| Node.js and npm | Frontend dependencies |
| Git and GitHub | Version control |

## Features

### 1. Buyer Module

- Buyer registration and login.
- Buyer dashboard with activity summaries.
- Browse properties, land, products, services, and projects.
- Search and filter marketplace listings.
- View listing details and seller information.
- Save favourite listings.
- Compare listings.
- Submit general requirements.
- Send enquiries to sellers.
- Communicate with sellers through enquiry messaging.
- Request quotations through RFQs.
- Review quotations and offers.
- Manage orders and property transactions.
- Track order and transaction status.
- View payment information.
- Submit reviews and ratings after eligible completed orders.
- Receive notifications about marketplace activity.

### 2. Seller Module

- Seller registration and authentication.
- Seller dashboard with listing and activity summaries.
- Manage personal and business profile information.
- Create, view, edit, and manage listings.
- Manage properties, land, products, services, and projects.
- Upload listing images and supporting documents.
- View buyer enquiries and requirements.
- Respond to buyer enquiries.
- Receive and respond to RFQs.
- Prepare quotations and offers.
- Manage customers.
- Accept or reject orders.
- Process product orders.
- Manage service and project fulfilment.
- Handle property and land negotiations.
- Track transactions and relevant payment statuses.
- Receive notifications about orders, enquiries, quotations, and reviews.

### 3. Admin Module

- Admin authentication and dashboard.
- Manage users and sellers.
- Review and manage marketplace listings.
- Manage categories and subcategories.
- Manage marketplace settings.
- Monitor orders and payments.
- Manage property and land transactions.
- Review and approve or reject reviews and ratings.
- Monitor buyer requirements and RFQs.
- Manage application notifications.
- View marketplace activity and operational information.

### 4. Listing Management

The marketplace supports multiple listing categories:

- **Properties:** Residential and commercial property listings.
- **Land & Plots:** Land listings and related transaction details.
- **Products:** Building materials and other listed products.
- **Services:** Professional and business services.
- **Projects:** Project-related listings and service engagements.

Listings are connected to their respective categories and subcategories and can display relevant details to buyers.

### 5. Requirements, Enquiries and Quotations

The marketplace supports the following workflow:

1. A buyer submits a general requirement or sends an enquiry about a listing.
2. The requirement or enquiry is associated with the appropriate buyer and, where applicable, seller and listing.
3. Administrators can review general requirements and accept or reject them.
4. An accepted general requirement can generate an RFQ.
5. Sellers can respond to RFQs and submit quotations.
6. Buyers can review seller responses and offers.
7. The application tracks relevant statuses and sends notifications for supported actions.

### 6. Orders and Payments

The order management module supports order creation, buyer and seller order views, status updates, and payment verification.

- Display order details and order numbers.
- Associate orders with buyers, sellers, listings, quotations, and requirements where applicable.
- Track order statuses.
- Allow sellers to accept or reject pending orders.
- Verify payment status before eligible processing or completion actions.
- Support product order processing, shipping, and delivery.
- Support service and project completion workflows.
- Notify relevant users about supported order updates.

**Important:** An order, a payment, and a transaction are different records and should be managed separately.

- **Order:** Represents a purchase or fulfilment process.
- **Payment:** Records payment-related information and verification status.
- **Transaction:** Represents the negotiation and completion process for property and land deals.

A verified payment does not automatically mean that an order or transaction is completed. Each process follows its own status rules.

### 7. Property and Land Transactions

Properties and land use a dedicated transaction workflow rather than the standard product delivery process.

- Track property and land transactions separately.
- Display buyer, seller, listing, and transaction details.
- Allow sellers to accept pending transactions.
- Start negotiations after confirmation.
- Track documentation.
- Complete the transaction after the required steps.
- Notify relevant users about supported transaction updates.
- Support reviews and ratings after eligible completion.

### 8. Reviews, Ratings and Notifications

**Reviews and ratings**
- Buyers can submit ratings and written reviews for eligible completed orders.
- Duplicate review submissions are restricted by application validation.
- Administrators can approve or reject reviews.
- Approved reviews can be displayed with relevant listings.

**Notifications**
- Database-based notifications for supported marketplace activities.
- Buyer and seller notifications.
- Order and transaction status updates.
- Requirement, enquiry, RFQ, and quotation notifications where configured.
- Review-related notifications where configured.
- Admin notifications for supported administrative events.

 Application Screenshots

 Home Page
<img width="1365" height="632" alt="image" src="https://github.com/user-attachments/assets/1ce8367d-f336-442e-a29a-54b3be18cd36" />

Login Page
<img width="1365" height="630" alt="image" src="https://github.com/user-attachments/assets/ff95f595-0ab0-4211-86c6-7eb77cb072e0" />

Shop by Category
<img width="1365" height="582" alt="image" src="https://github.com/user-attachments/assets/28cf7976-04c7-46bd-8624-6fd4088549ab" />

Featured Listings
<img width="1365" height="634" alt="image" src="https://github.com/user-attachments/assets/dc8cf513-48f3-4bba-9880-de7da49d591a" />

Verified Sellers
<img width="1365" height="626" alt="image" src="https://github.com/user-attachments/assets/777bbb66-d454-4bdc-bffc-2ec8aa5f81f4" />
<img width="1365" height="562" alt="image" src="https://github.com/user-attachments/assets/167919f1-f59c-47bb-8eb1-95a118a84270" />
<img width="1365" height="634" alt="image" src="https://github.com/user-attachments/assets/cd32f4bb-6386-4466-bc9d-56dc833eff0c" />

Buyer Dashboard
<img width="1365" height="637" alt="image" src="https://github.com/user-attachments/assets/f5e2a8da-c444-4b9c-a8d6-b4ad999d32a2" />

Seller Dashboard
<img width="1365" height="631" alt="image" src="https://github.com/user-attachments/assets/6a742ead-ab86-4d9d-8b2e-d5e7e5160c36" />

Admin Dashboard
<img width="1365" height="635" alt="image" src="https://github.com/user-attachments/assets/6bec526d-959d-452e-80bc-5e1b029f3c96" />

Marketplace Listings
<img width="1365" height="629" alt="image" src="https://github.com/user-attachments/assets/32035cf1-2851-4094-ab8f-b4eb43a1e827" />
<img width="1365" height="450" alt="image" src="https://github.com/user-attachments/assets/25d53ec7-402a-4354-9a04-073654e4edc4" />

Order Management
Buyer side 
<img width="1365" height="634" alt="image" src="https://github.com/user-attachments/assets/bebe96a7-84bd-498e-8cfc-4761ebfdc47e" />
<img width="1365" height="647" alt="image" src="https://github.com/user-attachments/assets/b7b3262e-0eea-42e9-859f-e909cad396d8" />
<img width="848" height="424" alt="image" src="https://github.com/user-attachments/assets/f4f5d46e-6003-49e2-ae02-ebd72e931271" />

Seller side
<img width="1365" height="626" alt="image" src="https://github.com/user-attachments/assets/45a1c446-ec41-4d4d-b924-acf0d892b67c" />
<img width="1365" height="632" alt="image" src="https://github.com/user-attachments/assets/476869a4-a788-4b93-8cf6-913be697cb54" />
<img width="1365" height="626" alt="image" src="https://github.com/user-attachments/assets/e84d5242-58de-42e5-82f5-b85ca3b7a928" />
<img width="1364" height="642" alt="image" src="https://github.com/user-attachments/assets/12600d44-422c-4203-bd66-050295c974a0" />

Admin side
<img width="1365" height="641" alt="image" src="https://github.com/user-attachments/assets/6e1ed52d-e79c-4e64-85b8-3cb25f53f472" />
<img width="1365" height="637" alt="image" src="https://github.com/user-attachments/assets/76bc6d6d-1f52-4d11-b7e1-9ae4dfa51637" />
<img width="1365" height="634" alt="image" src="https://github.com/user-attachments/assets/89fd708b-0dc1-4053-b7c5-98a695f8c1dd" />
<img width="1365" height="633" alt="image" src="https://github.com/user-attachments/assets/2a9cfc6a-209e-4cae-b471-5f6eefc73e97" />
<img width="1365" height="639" alt="image" src="https://github.com/user-attachments/assets/3b1283c3-3f4c-44aa-8dc1-438709a4c1e4" />

Send Requirement Process 
form 
<img width="1365" height="560" alt="image" src="https://github.com/user-attachments/assets/5f247dde-db5f-495b-95e1-ce77b1ab4891" />
<img width="1365" height="567" alt="image" src="https://github.com/user-attachments/assets/5580f6af-976f-44ab-b490-1bf864615df3" />
<img width="1365" height="572" alt="image" src="https://github.com/user-attachments/assets/160179f8-f14c-4ead-bba0-76741c887d15" />
requirement send to admin 

Admin side 
accept it 
<img width="1365" height="639" alt="image" src="https://github.com/user-attachments/assets/e8882edd-9dbd-4a4b-882c-fe93b2a6e3be" />
auto-generate RFQ 
<img width="1363" height="630" alt="image" src="https://github.com/user-attachments/assets/3cd7acfa-41f8-4a46-8b81-dcb9609ad93e" />
according to requirement sent to seller

Seller side 
<img width="1365" height="619" alt="image" src="https://github.com/user-attachments/assets/f8f73f62-bd66-4823-9583-57f1829c6764" />
seller send quotations or respond to it
<img width="1365" height="636" alt="image" src="https://github.com/user-attachments/assets/44954bc4-c24e-476b-9f66-8775f637edce" />
then buyer will respond or accept quotation and create order 


## Order and Transaction Workflows

### Product Orders

`Pending → Confirmed → Processing → Shipped → Delivered → Completed`

- Sellers can accept or reject pending orders.
- Confirmed product orders can proceed to processing when the applicable payment verification requirements are met.
- Processed products can be shipped and marked delivered.
- Eligible buyers can complete the order after delivery and verified payment.

### Service and Project Orders

`Pending → Confirmed → Processing → Completed`

Service and project orders do not need the product-specific shipped and delivered stages.

### Property and Land Transactions

`Pending → Confirmed → Negotiation → Documentation → Completed`

Property and land transactions follow a negotiation and documentation workflow instead of product delivery.

### Payment Verification

Payment verification is handled separately from order and transaction status changes. The application should verify the applicable payment record before allowing protected actions. A pending or unverified payment should not be treated as a successful payment.

## Project Structure

The application follows Laravel's standard project organization, with additional modules for the marketplace.

```text
marketplace/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/
│   │       ├── Seller/
│   │       └── ...
│   ├── Models/
│   └── ...
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── admin/
│       ├── buyer/
│       ├── seller/
│       ├── layouts/
│       └── components/
├── routes/
│   └── web.php
├── uploads/
├── vendor/
├── node_modules/
├── artisan
├── composer.json
├── package.json
├── vite.config.js
├── .env.example
└── README.md
```

The structure above is representative. Your project may contain additional controllers, models, helpers, views, middleware, and configuration files.

## Installation and Setup

### Prerequisites

Install the following before setting up the project:

- PHP 8.2 or later.
- Composer.
- MySQL and a local database server such as XAMPP.
- Node.js and npm.
- Git.

### 1. Clone the Repository

```powershell
git clone https://github.com/laxmi-ux/laravel-marketplace.git
cd laravel-marketplace
```

### 2. Install PHP Dependencies

```powershell
composer install
```

### 3. Install Frontend Dependencies

```powershell
npm install
```

### 4. Configure the Environment

Create the environment file in PowerShell:

```powershell
Copy-Item .env.example .env
```

Generate the application key:

```powershell
php artisan key:generate
```

Update `.env` with your local application and database settings. For example:

```dotenv
APP_NAME="Laravel Marketplace"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marketplace
DB_USERNAME=root
DB_PASSWORD=
```

Adjust the database username and password to match your local MySQL configuration. Do not overwrite an existing working `.env` file without backing it up.

### 5. Create the Database

Open phpMyAdmin or your MySQL client and create a database named:

```text
marketplace
```

Make sure the database credentials in `.env` match your local configuration.

### 6. Run Database Migrations

```powershell
php artisan migrate
```

If your project requires seeders for categories, roles, or other initial data, run the appropriate seeder only after confirming which seeders are available in your project.

### 7. Build Frontend Assets

```powershell
npm run build
```

This generates the production frontend assets used by Laravel.

## Running the Application

From the project root, start the PHP development server using the command configured for this project:

```powershell
php -S 127.0.0.1:8000
```

Open the application in your browser:

**http://127.0.0.1:8000**

Keep the terminal running while using the application.

**Note about the custom root setup:** This project uses a root-level `index.php` and `.htaccess` arrangement instead of the standard Laravel web root in `public`. Ensure the root `index.php` correctly bootstraps Laravel and routes requests as intended. The PHP built-in server does not process `.htaccess` rules, so rewrite behavior must be handled by the server/router configuration when needed. This setup is for local development; production deployments should use a properly configured web server with secure document-root and rewrite rules.

## Useful Commands

| Command | Purpose |
|---|---|
| `composer install` | Install PHP dependencies |
| `npm install` | Install frontend dependencies |
| `php artisan key:generate` | Generate the application key |
| `php artisan migrate` | Run database migrations |
| `php artisan route:list` | List registered application routes |
| `php artisan optimize:clear` | Clear Laravel caches |
| `npm run build` | Build frontend assets |
| `npm run dev` | Start the Vite development server |
| `php -S 127.0.0.1:8000` | Start the configured local PHP server |

If you change environment settings or encounter stale Laravel caches, you can run:

```powershell
php artisan optimize:clear
```

## Security Notes

- Never commit `.env`, database passwords, API keys, or other secrets.
- Keep `APP_DEBUG=true` only in local development; disable debug output in production.
- Validate and authorize buyer, seller, and admin actions on the server.
- Verify payment records before allowing protected order or transaction actions.
- Validate uploaded file types and sizes.
- Ensure users can access only the orders, transactions, enquiries, and listings they are authorized to view.
- Configure HTTPS and secure session settings before production deployment.
- Keep PHP, Laravel, and dependencies updated.

## Future Enhancements

Potential improvements include:

- Integration with a payment gateway.
- Automated email and SMS notifications.
- Advanced search and location filters.
- Improved reporting and marketplace analytics.
- Enhanced payment reconciliation and refund handling.
- Automated tests for order, payment, transaction, and review workflows.
- Production deployment and monitoring.

## Developer

**Laxmi Pal**

- GitHub: https://github.com/laxmi-ux
- Portfolio: https://laxmirpal1.netlify.app/
- Project Repository: https://github.com/laxmi-ux/laravel-marketplace

## License

This project is developed as a Laravel Marketplace application. Add or update the repository's `LICENSE` file if you intend to distribute it under a specific open-source license.

