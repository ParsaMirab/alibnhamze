# REQUIREMENTS.md

## Laravel 12 School Website Setup

This document outlines all dependencies and setup steps required for the school website project.

### Prerequisites

Before beginning, ensure you have the following installed:
- PHP 8.2+
- Composer
- Node.js 18+
- npm or yarn
- Git

### Installation Steps

#### 1. Clone the Repository
```bash
git clone <repository-url>
cd ali-bn-hamze
```

#### 2. Install PHP Dependencies
```bash
composer install
```

#### 3. Install Node.js Dependencies
```bash
npm install
```

#### 4. Environment Setup
```bash
cp .env.example .env
```
Then edit `.env` to configure your database connection:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

#### 5. Generate Application Key
```bash
php artisan key:generate
```

#### 6. Run Database Migrations
```bash
php artisan migrate
```

#### 7. Storage Link (if needed)
```bash
php artisan storage:link
```

#### 8. Asset Preparation
Create the required directory structure for assets:
```bash
mkdir -p public/images/hero public/images/news public/images/icons public/images/decorative public/fonts
```

Download and place the Vazirmatn font files in `public/fonts/`:
- `Vazirmatn-Regular.woff2`
- `Vazirmatn-Bold.woff2`

#### 9. Build Assets
```bash
npm run dev
```
For production:
```bash
npm run build
```

#### 10. Serve the Application
```bash
php artisan serve
```

### Development Commands

| Command | Description |
|---------|-------------|
| `npm run dev` | Start Vite development server with hot reload |
| `npm run build` | Build assets for production |
| `php artisan serve` | Start Laravel development server |
| `php artisan test` | Run PHPUnit tests |
| `php artisan migrate:fresh --seed` | Reset database and run seeders |

### Asset Structure

```
public/
├── images/
│   ├── hero/
│   │   └── shrine-banner.jpg          # Hero section background
│   ├── news/
│   │   ├── news-1.jpg                 # News card images
│   │   ├── news-2.jpg
│   │   ├── news-3.jpg
│   │   ├── news-4.jpg
│   ├── icons/                         # Icon assets (if needed)
│   └── decorative/
│       └── shrine-decoration.jpg      # Decorative elements
└── fonts/
    ├── Vazirmatn-Regular.woff2        # Persian font - regular weight
    └── Vazirmatn-Bold.woff2           # Persian font - bold weight
```

### Technology Stack

- **Backend**: Laravel 12
- **Frontend**: Blade Templates with Tailwind CSS 4.0
- **Build Tool**: Vite
- **Fonts**: Local Vazirmatn Persian font
- **CSS Framework**: Tailwind CSS (configured for RTL support)

### Tailwind Configuration

The project uses Tailwind CSS 4.0 with the following customizations:
- Primary color: `#2563eb` (blue-600)
- Secondary color: `#60a5fa` (blue-400)
- Font family: 'Vazirmatn' for all text
- RTL support enabled via `dir="rtl"` and `lang="fa"` attributes

### Browser Support

- Chrome latest
- Firefox latest
- Safari latest
- Edge latest
- Mobile browsers (responsive design)

### Additional Notes

1. All images should be optimized for web use
2. Placeholder images should be replaced with actual content before production
3. The Vazirmatn font files must be in WOFF2 format for optimal performance
4. Ensure proper file permissions on storage and bootstrap/cache directories:
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R $www-data:$www-data storage bootstrap/cache
   ```

### Troubleshooting

If you encounter font loading issues:
1. Verify the font files exist in `public/fonts/`
2. Check that the `@font-face` rules are present in `resources/css/app.css`
3. Clear browser cache or hard refresh (Ctrl+Shift+R)