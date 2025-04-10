
# Laravel E-Commerce & Service Management Platform

A Laravel-based e-commerce platform with PayPal integration, product and order management, vendor support, user authentication, email notifications, and a dynamic front-end built using Blade templates for seamless shopping and service experiences.

---

## 🛠 Features

- Product listing and search
- Add to cart and checkout system
- PayPal payment integration
- Order and serial number email notifications
- User registration, login, and profile management
- Vendor product management
- Admin settings for SEO, social links, services, etc.
- Testimonial and review system
- Dynamic homepage with sliders and advertisements

---

## 📦 Tech Stack

- Laravel Framework
- PHP 7+
- MySQL / MariaDB
- Blade Templates
- Bootstrap, jQuery
- PayPal SDK (REST API)
- Laravel Mail (SMTP)

---

## 🚀 Installation

1. Clone the repository

git clone https://github.com/Kaklotar-Parth/Ecommerce 


2. Install Instructions

- Clone the repo.
- Run composer install
- Run php -r "file_exists('.env') || copy('.env.example', '.env');"
- Run php artisan key:generate --ansi
- Create a database named bizzmana_ecom (or you can change the name in .env) in your localhost
- Edit .env file
- Run php artisan migrate --seed


3. Configure environment

4. Edit `.env` file
- Set database credentials
- Configure mail settings
- Set PayPal credentials

5. Run migrations

6. Start the server


---

## 💳 PayPal Setup

Edit `config/paypal_payment.php` or set in `.env`:


---

## 📧 Email Configuration

To send emails for orders and serial numbers:

Set SMTP details in `.env`:



---

## 📁 Key Folders

| Path | Description |
|------|-------------|
| app/Models | Contains Eloquent models |
| app/Mail | Mail classes for sending emails |
| resources/views/ | Blade views (frontend) |
| routes/web.php | App routes |
| config/paypal_payment.php | PayPal configuration |
| public/ | Public entry point |

---

## 🧪 Testing

To run tests:






