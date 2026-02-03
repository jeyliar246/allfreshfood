# Deployment Guide for AllFreshFood

## ⚠️ Important: Netlify Limitation

**Netlify does NOT support PHP**, which Laravel requires. Netlify is designed for static sites and JAMstack applications. Your Laravel application needs a PHP runtime environment.

## Recommended Deployment Options

### 1. **Laravel Forge + DigitalOcean/Linode** (Recommended)
- Best for: Production applications
- Setup: Automated Laravel deployment
- Cost: ~$12-20/month
- Guide: https://forge.laravel.com

### 2. **Railway**
- Best for: Easy deployment with database
- Setup: Connect GitHub repo, auto-deploys
- Cost: Pay-as-you-go
- Guide: https://railway.app

### 3. **Vercel** (with PHP support)
- Best for: Modern deployment with edge functions
- Setup: Connect repo, configure PHP
- Cost: Free tier available
- Guide: https://vercel.com/docs

### 4. **Heroku**
- Best for: Quick deployment
- Setup: Git push to deploy
- Cost: Free tier discontinued, paid plans available
- Guide: https://devcenter.heroku.com/articles/getting-started-with-php

### 5. **DigitalOcean App Platform**
- Best for: Managed Laravel hosting
- Setup: Connect GitHub, auto-detects Laravel
- Cost: ~$12/month
- Guide: https://docs.digitalocean.com/products/app-platform

### 6. **Traditional Hosting** (cPanel, etc.)
- Best for: Budget-friendly, full control
- Setup: Upload files via FTP/SFTP
- Cost: $5-10/month
- Providers: Hostinger, SiteGround, A2 Hosting

## If You Must Use Netlify

If you only want to deploy the **frontend assets** (not the full Laravel app), you can:

1. Build the assets: `npm run build`
2. The assets will be in `public/build/`
3. Configure Netlify to publish from `public/build/`

However, this will only serve static files - no backend functionality will work.

## Deployment Checklist

Before deploying, ensure:

- [ ] `.env` file is configured with production settings
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] Database credentials are set
- [ ] `APP_URL` is set to your production domain
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Run `npm run build` to build frontend assets
- [ ] Storage link: `php artisan storage:link`
- [ ] Run migrations: `php artisan migrate --force`

## Environment Variables to Set

```
APP_NAME=AllFreshFood
APP_ENV=production
APP_KEY=base64:... (generate with: php artisan key:generate)
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=your-database
DB_USERNAME=your-username
DB_PASSWORD=your-password

# Stripe (if using)
STRIPE_KEY=your-stripe-key
STRIPE_SECRET=your-stripe-secret
STRIPE_WEBHOOK_SECRET=your-webhook-secret

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Quick Deploy Commands

```bash
# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link
```

