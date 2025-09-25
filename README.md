# Laravel + Vue 3 + Inertia.js Starter Kit

A modern full-stack web application starter kit built with Laravel, Vue 3, and Inertia.js, featuring SSR support, authentication, user management, and PWA capabilities.

## Features

- **Backend**: Laravel 12 with PHP 8.4+
- **Frontend**: Vue 3 + TypeScript + Inertia.js
- **Styling**: Tailwind CSS v4 with reka-ui/shadcn-vue components
- **Authentication**: Laravel built-in with Inertia integration
- **Database**: SQLite (configurable)
- **PWA**: Progressive Web App support
- **SSR**: Server-Side Rendering enabled
- **Testing**: Pest framework for PHP tests
- **Code Quality**: Laravel Pint (PHP) + ESLint + Prettier (JS/Vue)

## Requirements

- PHP 8.4+
- Node.js 18+
- Composer
- SQLite (or your preferred database)

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd inertia-boilerplate
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   touch database/database.sqlite
   php artisan migrate
   ```

## Development

### Quick Start (Recommended)
Start the complete development environment with one command:
```bash
composer dev
```
This starts Laravel server, queue worker, logs, and Vite development server.

### With SSR Support
```bash
composer dev:ssr
```

### Individual Commands
If you prefer to run services separately:

**Backend (Laravel)**
```bash
php artisan serve
```

**Frontend (Vite)**
```bash
npm run dev
```

**Queue Worker**
```bash
php artisan queue:work
```

## Building for Production

### Standard Build
```bash
npm run build
```

### Build with SSR
```bash
npm run build:ssr
```

## Testing

### PHP Tests (Pest)
```bash
# Run all tests
composer test
# or
php artisan test

# Run specific test file
php artisan test tests/Feature/ExampleTest.php

# Run tests with filter
php artisan test --filter=testName
```

### Frontend Linting & Formatting
```bash
# Lint and fix JavaScript/Vue files
npm run lint

# Format code with Prettier
npm run format

# Check formatting
npm run format:check
```

### PHP Code Formatting
```bash
# Format PHP code with Laravel Pint
./vendor/bin/pint
```

## PWA Configuration

### Updating PWA Icons
To update the PWA icons and app assets, run:
```bash
./vendor/bin/sail npx pwa-assets-generator --preset minimal-2023 public/test/logo.png
```

This command generates all necessary PWA icons and assets from your logo file. Make sure to:
1. Place your logo file at `public/test/logo.png`
2. Ensure the logo is a square PNG with transparent background
3. Recommended minimum size: 512x512px

### PWA Configuration Files
- PWA settings can be configured via environment variables
- Main configuration is handled in the Laravel backend
- Service worker and manifest files are automatically generated

## Project Structure

### Backend (Laravel)
```
app/
├── Console/Commands/     # Artisan commands (auto-registered)
├── Http/Controllers/     # Controllers
├── Models/              # Eloquent models
└── ...

routes/
├── web.php              # Web routes
├── auth.php             # Authentication routes
└── settings.php         # Settings routes

config/inertia.php       # Inertia.js SSR configuration
bootstrap/app.php        # App configuration & middleware
```

### Frontend (Vue 3)
```
resources/js/
├── pages/               # Inertia pages
│   ├── auth/           # Authentication pages
│   ├── settings/       # Settings pages
│   ├── Dashboard.vue   # Dashboard page
│   └── Welcome.vue     # Welcome page
├── components/         # Reusable components
│   └── ui/            # UI component library
├── composables/        # Vue composables
└── lib/               # Utility libraries
```

## Key Commands Reference

### Development
- `composer dev` - Start full development environment
- `composer dev:ssr` - Start development with SSR
- `npm run dev` - Vite development server only
- `php artisan serve` - Laravel server only

### Building
- `npm run build` - Production build
- `npm run build:ssr` - Production build with SSR

### Testing & Quality
- `composer test` - Run PHP tests
- `npm run lint` - Lint JavaScript/Vue
- `npm run format` - Format code
- `./vendor/bin/pint` - Format PHP code

### Database
- `php artisan migrate` - Run migrations
- `php artisan migrate:fresh --seed` - Fresh migration with seeders

### PWA
- `./vendor/bin/sail npx pwa-assets-generator --preset minimal-2023 public/test/logo.png` - Update PWA icons

## Architecture Notes

### Component System
- Built with reka-ui (headless components) and shadcn-vue styling
- Component aliases configured for easy imports:
  - `@/components` → `resources/js/components`
  - `@/lib` → `resources/js/lib`
  - `@/composables` → `resources/js/composables`

### SSR Configuration
- SSR enabled by default in `config/inertia.php`
- SSR server runs on `http://127.0.0.1:13714`
- Build SSR bundle with `npm run build:ssr`

### Authentication
- Laravel's built-in authentication with Inertia integration
- Authentication pages located in `resources/js/pages/auth/`

### Styling
- Tailwind CSS v4 with modern import syntax
- Dark mode support via `useAppearance` composable
- Responsive design with mobile-first approach

## Troubleshooting

### Common Issues

**Vite Manifest Error**
If you see "Unable to locate file in Vite manifest":
```bash
npm run build
```
or restart development server:
```bash
npm run dev
```

**Frontend Changes Not Reflecting**
Make sure Vite development server is running:
```bash
npm run dev
# or
composer dev
```

**Database Connection Issues**
Ensure SQLite file exists:
```bash
touch database/database.sqlite
```

## Contributing

1. Follow existing code conventions
2. Run tests before submitting: `composer test`
3. Format code: `./vendor/bin/pint` and `npm run lint`
4. Write tests for new features

## License

[Your License Here]