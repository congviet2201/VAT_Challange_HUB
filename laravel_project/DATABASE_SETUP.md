# Database Setup Guide - Using SQLite with Migrations & Seeders

## Overview
This project now uses **SQLite database** with **Laravel Migrations** and **Seeders** instead of a static SQL file. This approach provides:
- Version-controlled schema changes via migrations
- Easy database reset and seeding
- No external database server required
- Simplified development workflow

## Configuration

### Current Setup
- **Database Type**: SQLite
- **Database File**: `database/database.sqlite`
- **Configuration**: `.env` file with `DB_CONNECTION=sqlite`
- **Migrations**: `database/migrations/`
- **Seeders**: `database/seeders/`

## Quick Start Setup

### Option 1: Automatic Setup (Recommended)
Run the migration and seeding script:
```bash
php run_migration.php
```
This will:
1. Run all migrations to create tables
2. Seed the database with initial data (users, categories, challenges)

### Option 2: Manual Setup
```bash
# Run migrations only
php artisan migrate --force

# Or: Run migrations with seeders
php artisan migrate:fresh --seed --force
```

## Database Tables

The migrations create the following tables:
- `users` - User accounts with roles (admin, useradmin, user)
- `cache` - Laravel cache table
- `jobs` - Job queue table
- `categories` - Challenge categories (6 total)
- `challenges` - Challenges within categories (25+ total)
- `challenge_progress` - User's progress on challenges
- `groups` - User groups
- `notifications` - User notifications
- `group_user` - Group membership mapping
- `group_challenge` - Group challenge associations

## Seeding Data

### Default Users
The seeder creates the following user accounts:
- **Admin**: `admin@gmail.com` / `password`
- **UserAdmin** (Group Leaders):
  - `useradmin1@gmail.com` / `password`
  - `useradmin2@gmail.com` / `password`
- **Regular Users**:
  - `user1@gmail.com` / `password`
  - `user2@gmail.com` / `password`
  - `user3@gmail.com` / `password`

### Default Data
The seeder automatically creates:
- 6 Categories (Học tập, Sức khỏe, Phát triển bản thân, Kỹ năng, Thể thao, Thói quen tốt)
- 25+ Challenges across all categories

## Resetting the Database

To completely reset the database and reseed with default data:
```bash
php artisan migrate:fresh --seed --force
```

**Warning**: This will delete all existing data and recreate tables.

## Development Workflow

### When Modifying Schema
1. Create a new migration:
   ```bash
   php artisan make:migration create_new_table
   ```
2. Edit the migration file in `database/migrations/`
3. Run the migration:
   ```bash
   php artisan migrate
   ```

### When Adding New Seed Data
1. Edit `database/seeders/ChallengeHubSeeder.php`
2. Add your data to the arrays
3. Run seeding:
   ```bash
   php artisan db:seed
   ```

### When Testing
The testing environment uses an in-memory SQLite database (see `phpunit.xml`).

## File Locations

| File | Purpose |
|------|---------|
| `.env` | Environment configuration (DB_CONNECTION=sqlite) |
| `database/database.sqlite` | SQLite database file (created after first run) |
| `database/migrations/` | Schema migration files |
| `database/seeders/` | Data seeding files |
| `run_migration.php` | One-command setup script |

## Migrating from Old Database

If you previously had a MySQL database:
1. The old `database/challenge_hub.sql` file is no longer needed
2. All table structure is now defined in migrations
3. All seed data is now in seeders
4. Simply run `php run_migration.php` to set up the new SQLite database

## Troubleshooting

### Database file not found
The SQLite file will be created automatically when you run migrations.

### Permission errors
Ensure the `database/` directory is writable:
```bash
chmod -R 755 database/
```

### Data not seeding
Make sure `ChallengeHubSeeder` is called in `DatabaseSeeder::run()`

## Environment Configuration

The `.env` file has been configured with:
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

No MySQL credentials are needed since we're using SQLite.

---

For more information on Laravel migrations and seeders, visit: https://laravel.com/docs/migrations
