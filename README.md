# ChronoNotes

ChronoNotes is a Laravel-based personal portfolio and notes manager with an admin dashboard, watch-inspired public theme, localization, and support for project images and note attachments.

## Features
- Category, project, note management with soft deletes and recycle bin.
- Laravel Breeze-style authentication with role-based authorization (admin/editor).
- Responsive admin dashboard built with Blade components and Tailwind CSS.
- Public site with global search, breadcrumbs, SEO-friendly slugs, and bilingual support (English/Turkish).
- File uploads for project images and note attachments (stored in `storage/app/public`).
- Contact form using Laravel's mail system.

## Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- npm
- A supported database (MySQL, PostgreSQL, SQLite, or SQL Server)

### Installation
1. Install PHP dependencies:
   ```bash
   composer install
   ```
2. Install and build front-end assets:
   ```bash
   npm install
   npm run build # or `npm run dev` during development
   ```
3. Copy the environment file and update settings:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Configure database and mail settings in `.env`.
5. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```
6. Create the storage symlink (required for serving uploads):
   ```bash
   php artisan storage:link
   ```
7. Start the development server:
   ```bash
   php artisan serve
   ```

### Default Accounts
Seeders create an admin user:
```
Email: admin@example.com
Password: password
```

### Testing
Run the feature and unit test suites:
```bash
php artisan test
```

### Deployment Notes
- Ensure the `storage` and `bootstrap/cache` directories are writable by the web server.
- Configure the queue and scheduler if using queued jobs or scheduled tasks.
- Set up a cron job for `php artisan schedule:run` if required.
- Remember to run `php artisan storage:link` on the deployment server.

## Version Control & GitHub
To publish the project to your own GitHub repository:
1. [Create a new empty repository on GitHub](https://github.com/new) and copy its SSH or HTTPS URL.
2. Add the remote in this project directory:
   ```bash
   git remote add origin <your-github-url>
   ```
3. Verify the remote and review pending commits:
   ```bash
   git remote -v
   git status
   ```
4. Push the existing history to GitHub:
   ```bash
   git push -u origin work
   ```
   Replace `work` with the branch name you are using if it differs.
5. Open a pull request (if collaborating) or continue committing locally and pushing updates with `git push`.

## License
Released under the MIT License.
