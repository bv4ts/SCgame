# IAU Syrian Community — Wheel of Fortune Game

A simple interactive Wheel of Fortune web app with an admin panel for managing categories and questions.

## Requirements

- XAMPP (Apache + MySQL + PHP)
- A modern web browser

## Installation

### 1. Set up the database

1. Open the XAMPP Control Panel.
2. Start Apache and MySQL.
3. Open phpMyAdmin in your browser: `http://localhost/phpmyadmin`.
4. Use the SQL tab or the Import feature to run the SQL statements in `database.sql`.

### 2. Run the site

1. Make sure Apache is running in XAMPP.
2. Open your browser and go to: `http://localhost/iausyriancommunitygame` (adjust the path if you placed the project in a different folder).

## Pages

### Game (`index.php`)
- The main game page.
- Displays the wheel with categories.
- Click/tap the wheel to spin.
- When the wheel stops, a random question from the selected category is shown.

### Admin (`admin.php`)
- Administration panel to manage categories and questions.
- Add / edit / delete categories and choose category colors.
- Add and remove questions for each category.

### Links (`links.php`)
- A simple page to display QR codes (for the evaluation form and the Instagram account).

## Adding QR codes

1. Place QR code images in `assets/qr/`.
2. Use these filenames for the app to recognize them:
   - `eval.png` — evaluation form
   - `instagram.png` — Instagram account

## Project structure

```
iausyriancommunitygame/
├── index.php              # Game page
├── admin.php              # Admin panel
├── links.php              # Links / QR codes
├── database.sql           # Database schema & sample data
├── README.md              # Original README (Arabic)
├── README_EN.md           # This English README
├── api/
│   ├── db.php             # Database connection
│   ├── categories.php     # Categories API
│   ├── questions.php      # Questions API
│   └── spin.php           # Select random question
├── assets/
│   ├── css/
│   │   └── style.css      # Styles
│   ├── js/
│   │   ├── wheel.js       # Wheel logic
│   │   └── ui.js          # UI interactions
│   └── qr/                # QR code images
└── uploads/               # (optional)
```

## Notes

- Make sure Apache and MySQL are running before using the site.
- Database connection settings can be edited in `api/db.php`.
- The app supports Arabic (RTL) and includes sample data in `database.sql`.

## Support

If you have any problems or questions, please contact the project maintainer.

---
**IAU Syrian Community © 2025**
