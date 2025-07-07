🧾 Reporting Dashboard – Laravel + Livewire

A secure and responsive Laravel + Livewire-powered Reporting Dashboard that supports:

- Role-based user access (admin/user)
- Interactive transaction table with filters, sorting, CSV export
- Analytics dashboard with Chart.js (bar, pie, line)
- User management (toggle active status, impersonation)
- Dockerized setup for local development
- Feature and unit tests with PestPHP

🚀 Features
Transactions Table
- Filter by search text, date range, and categories
- Sortable columns (Date, Amount, etc.)
- Pagination and per-page selection
- Export to CSV

Analytics Dashboard
- Bar chart: Monthly spending trends (last 12 months)
- Pie chart: Spending breakdown by category
- Line chart: Daily transaction volume (last 30 days)
- Interactive date/category filters
- Responsive and tooltip-enabled charts

Admin User Management
- Admin-only route: /admin/users
- View all users with pagination and search
- Toggle user active/inactive status
- Impersonate users to debug from their perspective
- Stop impersonation easily

🚀 Setup Instructions

1. Clone the repo

        git clone https://github.com/degee147/livewire-reporting-dashboard.git
        cd reporting-dashboard


2. Setup directory permissions

        mkdir -p storage/framework/{cache,sessions,views}
        mkdir -p bootstrap/cache
        chmod -R 775 storage bootstrap/cache
        chown -R www-data:www-data storage bootstrap/cache


3. Install dependencies

        composer install
        npm install && npm run dev

4. Configure environment

        cp .env.example .env
        php artisan key:generate

5. Setup database

        php artisan migrate --seed

6. Run the app

        composer run dev

👤 Admin Account

Default admin credentials after seeding:

    Email: admin@example.com
    Password: password


🛠 Design Decisions

- Livewire was chosen for real-time reactivity without writing custom JS.
- Alpine.js enhances dropdowns and UI interactivity (like multiselects).
- Tailwind CSS ensures quick, responsive design with utility classes.
- Chart.js used via Blade components for clean reusable chart rendering.
- Role middleware ensures only Admins access sensitive routes.
- Impersonation is implemented securely via session tracking and loginUsingId.



🧪 Testing

> Optional: If using PestPHP

    composer require pestphp/pest --dev
    php artisan test

> 🧪 Unit Testing (PestPHP)

To run tests:

    php artisan test

To create a test:

    php artisan pest:test Feature/ExampleTest

🌱 Database Seeding
Run the following to seed with dummy users, categories, and transactions:

    php artisan migrate:fresh --seed

Seeders included:

- Admin user
- Sample regular users
- Transaction categories
- Random transactions over past year

🐳 Docker Setup
Build and run:

    cp .env.example .env
    docker-compose up -d --build

Access:

- Local App: http://localhost:8000
- Live Demo: https://report-dashboard.kenwaribo.com
- DB: 3306 (MySQL root:root)

Run inside container:

    docker exec -it livewire-app php artisan migrate --seed

Update .env to match your environment.

📂 Repo
GitHub: https://github.com/degee147/livewire-reporting-dashboard

--- ✅ Built with Laravel 12, Livewire 3, Tailwind CSS 3, Alpine.js, Chart.js, and Vite.
