# Triply - Laravel Trip Planner

## Project Description
Triply is a centralized trip planning application that helps users plan, organize, and manage trips efficiently. It simplifies coordination, budgeting, scheduling, and bookings.

### Key Features
- **Budget Calculator & Cost Splitting**: Helps users track and split trip costs.
- **Calendar Reminder**: Shows upcoming trips and events.
- **Wishlist**: Bookmark trips or travel packages for later.
- **Search & Filter Trips**: Find trips based on location, date, or budget.
- **Curated Travel Packages**: Ready-made trip options for convenience.
- **Dashboard / My Trips**: View all booked trips in one place.
- **Booking Requests**: Confirm availability before paying.
- **Trip Itinerary**: Plan daily schedules and activities.
- **Payment Integration**: Support for M-Pesa and card payments via Stripe.
- **Ratings & Reviews**: Users can rate and review trips.

---

## Requirements

- **PHP**: >= 8.2
- **Composer**: Latest version
- **Node.js**: >= 18.x
- **npm**: Latest version
- **MySQL**: >= 8.0
- **Git**: For version control

---

## Database Schema

The application uses the following database tables:

- **users** - User accounts (customers and admins)
- **trips** - Available travel packages and destinations
- **bookings** - Trip bookings made by users
- **payments** - Payment records (M-Pesa & Stripe)
- **budget_splits** - Cost splitting among users
- **ratings** - Trip ratings and reviews
- **wishlists** - Saved trips for later
- **calendar_reminders** - Trip reminder notifications

For detailed schema, check the migration files in `database/migrations/`.

---

## Setup & Running Instructions

Follow these steps to get the Triply Laravel project running locally:

### Step 1: Clone the Repository
```bash
git clone <repo-link>
cd triply
```

### Step 2: Install PHP Dependencies
```bash
composer install
```

This will install all required PHP packages defined in `composer.json`, including:
- Laravel Framework 12.x
- Livewire 3.6
- Blade Heroicons & UI components

### Step 3: Install Node.js Dependencies
```bash
npm install
```

This will install frontend dependencies:
- Vite (build tool)
- Tailwind CSS 4.x
- Axios

### Step 4: Set Up Environment File
```bash
cp .env.example .env
php artisan key:generate
```

### Step 5: Configure Database

Create a local MySQL database:

```sql
CREATE DATABASE triply;
```

Update your `.env` file with database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=triply
DB_USERNAME=root
DB_PASSWORD=your_password
```

**Optional:** For Stripe and M-Pesa payment integration, add:
```env
STRIPE_KEY=your_stripe_key
STRIPE_SECRET=your_stripe_secret

MPESA_CONSUMER_KEY=your_mpesa_consumer_key
MPESA_CONSUMER_SECRET=your_mpesa_consumer_secret
MPESA_SHORTCODE=your_shortcode
MPESA_PASSKEY=your_passkey
```

### Step 6: Build Frontend Assets
```bash
npm run build
```

This compiles and optimizes your CSS/JS assets for production. Use `npm run dev` for development with hot-reloading.

### Step 7: Run Migrations and Seed Database
```bash
php artisan migrate --seed
```

This will:
- Create all database tables (users, trips, bookings, payments, ratings, wishlists, budget_splits, calendar_reminders)
- Populate the database with sample data using seeders

**Note:** The seeders will create sample trips, users, bookings, and other test data to help you get started quickly.

### Step 8: Start the Development Server
```bash
php artisan serve
```

The application will be available at: [http://127.0.0.1:8000](http://127.0.0.1:8000)

### Step 9: (Optional) Run Development Mode

For development with hot-reloading:
```bash
npm run dev
```

This starts Vite's development server and watches for file changes.

---

## Quick Setup Commands

If you're setting up the project for the first time, run these commands in sequence:

```bash
# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Build assets
npm run build

# Set up database (make sure DB is created first)
php artisan migrate --seed

# Start the server
php artisan serve
```

Then visit: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## Development Workflow

### Running in Development Mode

For active development, you can use the custom composer script that runs multiple services:

```bash
composer run dev
```

This concurrently runs:
- Laravel development server (`php artisan serve`)
- Queue worker (`php artisan queue:listen`)
- Laravel Pail logs (`php artisan pail`)
- Vite dev server with hot-reload (`npm run dev`)

### Making Database Changes

After modifying migrations:
```bash
php artisan migrate:fresh --seed  # ⚠️ WARNING: This drops all tables and data
```

Or for incremental changes:
```bash
php artisan migrate
```

### Running Tests
```bash
composer run test
# or
php artisan test
```

---

## Team Instructions

### Module Assignment
Each team member should work on their own branch for a specific module:

- **Authentication** (Login/Register/Logout)
- **Trips** (CRUD operations, search, filters)
- **Bookings** (Booking requests, confirmations)
- **Payments** (M-Pesa & Stripe integration)
- **Wishlist** (Save/remove trips)
- **Dashboard** (User trip overview)
- **Ratings & Reviews**
- **Budget Splitting**
- **Calendar Reminders**

### Git Workflow

1. **Pull latest changes** from `main` regularly:
   ```bash
   git checkout main
   git pull origin main
   ```

2. **Create your feature branch**:
   ```bash
   git checkout -b feature/your-module-name
   ```

3. **Make changes and commit** with descriptive messages:
   ```bash
   git add .
   git commit -m "Add trip search and filter functionality"
   ```

4. **Push to remote and create Pull Request**:
   ```bash
   git push origin feature/your-module-name
   ```

5. Create a Pull Request to merge into `main` (or `dev` branch if applicable)

### Local Development Setup

**Each team member must:**

1. Create their own local database:
   ```sql
   CREATE DATABASE triply;
   ```

2. Update `.env` with their own database credentials

3. Run migrations and seeders once:
   ```bash
   php artisan migrate --seed
   ```

4. Start the development server:
   ```bash
   php artisan serve
   ```

**Important Notes:**
- Do NOT commit `.env` file (it's already in `.gitignore`)
- Always test your changes locally before pushing
- Coordinate with team members to avoid merge conflicts
- Document any new environment variables needed in `.env.example`

---

## Contributors
- 
- 
- 
- 
- 
- 