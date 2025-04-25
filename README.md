
# TourBeat Landing Page + Custom Admin Panel

> A multilingual, mobile-friendly landing page for a travel-related service with a fully custom-built admin panel and media manager.

---

## 🌐 Overview

This project consists of two main parts:

- **Frontend landing page** based on a modified [TEMPLATED Hielo](https://templated.co/hielo) template.
- **Custom backend admin panel** for managing content, languages, payment settings, and uploaded media.

Built with:
- PHP 7.x
- Bootstrap Icons
- Vanilla JS
- LiqPay integration (test credentials)
- Flat file localization system

---

## 📁 Project Structure

```
.
├── index.php               # Entry point of the landing page
├── config.php              # Configuration file (currency, lang, payment, DB, etc.)
├── lang_engine.php         # Core localization engine
├── lang_api.php            # API endpoint for language switcher
├── localisation/           # Contains text files with key-value language pairs
├── tokens/                 # Directory for payment tokens
├── create_visits_db.php    # DB schema generator (visitor tracking)
├── adminpanel/             # Full custom admin panel (see below)
├── assets/                 # CSS, images, and icons
```

---

## ⚙️ Setup Instructions

### Requirements

- PHP 7.2 or higher
- Apache/Nginx with mod_rewrite enabled
- MySQL (optional for visit tracking)
- HTTPS (required for LiqPay)

### Installation

1. **Clone the repo or upload to your server:**

   ```bash
   git clone https://github.com/YOUR_USERNAME/tourbeat.git
   ```

2. **Configure the system:**

   Edit `config.php`:

   ```php
   $name = 'TourBeat';
   $default_lang = 'en';
   $site_url = 'https://tourbeat.com.ua/';
   $public_key = 'YOUR_PUBLIC_KEY';
   $private_key = 'YOUR_PRIVATE_KEY';
   ```

3. **Enable rewrite rules** (`.htaccess` must be supported):

   ```apache
   Options +FollowSymlinks
   RewriteEngine on
   ```

4. **Localization Files:**

   Place `.txt` files in the `localisation/` folder. Each file represents one language (e.g., `en.txt`, `ua.txt`), structured as key-value pairs.

5. **Media Uploads:**

   All uploaded images are stored and managed via `adminpanel/media_manager.php`.

6. **(Optional) Initialize Visitor DB Table:**

   Visit `/create_visits_db.php` to create a MySQL table if you intend to track visits.

---

## 🔐 Admin Panel

Access: `/adminpanel/`

### Login

- No built-in authentication by default (suggestion: use `.htpasswd` or PHP-based auth).
- Logout button: `/adminpanel/logout.php`

### Admin Pages

| File                          | Description |
|------------------------------|-------------|
| `dashboard.php`              | Main admin dashboard |
| `admin.php`                  | Internal setup options |
| `general.php`                | Site-wide settings (site name, favicon, etc.) |
| `media_manager.php`          | Upload, preview, rename and describe photos |
| `create_lang.php`            | Create and edit language files |
| `manage.php`                 | Language management interface |
| `delete_lang.php`            | Language deletion |
| `set_default.php`            | Set default language |
| `payment.php`                | LiqPay integration & configuration |

---

## 🌍 Language Engine

The localization system uses `.txt` files in the `localisation/` directory. Example:

```
title = Welcome to TourBeat!
description = Discover your next adventure with us.
```

Switching language:
- Done via JS call to `lang_api.php?lang=ua`
- Language stored in `$_SESSION['lang']`

Default fallback: language from `config.php`.

---

## 💳 Payments (LiqPay)

Integrated with test credentials by default:

```php
$public_key = 'sandbox_...';
$private_key = 'sandbox_...';
```

Change to your real keys in `config.php`.

After payment, users are redirected to a Telegram group.

---

## 📸 Media Manager

- Located at `/adminpanel/media_manager.php`
- Features:
  - Scan `/images/` folder
  - Rename files
  - Add description (stored in `media_data.json`)
  - View full-size preview
  - Pagination (6 per page)

---

## 📦 Database (optional)

For visit tracking (`create_visits_db.php`) you can set up a MySQL table.

**DB Settings:**

In `config.php`:

```php
$db_user = 'YOUR_USER';
$db_password = 'YOUR_PASS';
$host = 'localhost';
```

You can enhance this with admin logging, analytics, or booking info.

---

## 🛠 Extending

Suggestions for further development:
- Add authentication to the admin panel
- Use MySQL for language storage instead of flat files
- Add SEO metadata editor in admin
- Extend media manager with video support

---

# 🌐 PHP Language Detection & Localization Script

This PHP script handles automatic and manual language detection for a multilingual web application. It supports country-based language auto-selection, custom language files, logging of user access by IP and selected language, and language switch UI elements with labels and tooltips.

## 📂 File Structure

```
project-root/
├── localisation/           # Directory for language files (*.txt)
│   ├── en.txt
│   ├── ua.txt
│   └── ...
├── logs/
│   └── log.txt             # Log of visitor IPs and selected languages
├── config.php              # Contains $default_lang and $service_mode
├── assets/
│   └── maintace.html       # Maintenance mode page
└── lang.php                # This script
```

## ✅ Features

- 🌍 Detects user IP and gets country-based default language.
- 🌐 Loads localization from `.txt` files using `key=value` format.
- 🇺🇳 Displays language switcher with self-labels (e.g., `eng`, `укр`, `日本`).
- ✍️ Logs IP and selected language in `logs/log.txt`.
- 🛠 Supports maintenance mode.

## 🔧 Configuration

Edit `config.php`:
```php
<?php
$default_lang = 'en';     // Default fallback language
$service_mode = false;    // Enable maintenance mode (true/false)
```

## 📥 Language File Format

Each language file should be placed in `/localisation` and follow this format:
```
welcome=Welcome to our website!
contact=Contact Us
```

Example: `localisation/en.txt`

## 🌐 Language Switching

The `getLangsWithFlags()` function dynamically lists available languages based on files in `/localisation`, using predefined names and labels.

## 🚦 IP to Country Detection

This script uses the [geojs.io](https://get.geojs.io) API to detect the user's country from their IP address. Based on the country code, it sets the default language (e.g., `ua` for Ukraine).

## 🔒 Logging

Visitor access is logged with a timestamp, IP, and selected language in `logs/log.txt`. If the file exceeds 10MB, it's reset automatically.

## 🧪 Maintenance Mode

If `$service_mode` is `true`, all requests are redirected to `assets/maintace.html`.

## 🚀 Usage

Include `lang.php` at the start of your script to enable language detection:

```php
require_once 'lang.php';

echo $texts['welcome']; // Example usage of loaded translations
```

## 💡 Tip

To switch languages manually, add `?lang=XX` to the URL, where `XX` is the language code (`en`, `ua`, `fr`, etc.).

---

Let me know if you'd like a version in Ukrainian too?


## 📄 License

This project contains HTML/CSS from TEMPLATED (CC BY 3.0). All PHP code is custom and you are free to modify/distribute under the MIT license.

---

## 📬 Contact

For support or customization requests, contact the original developer or clone & modify for your needs.
