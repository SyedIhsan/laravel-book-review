# Laravel Book Review

> **This repository is for learning purposes only.** It is a small practice project I built while learning Laravel, not a production application.

A simple book review app where you can browse books, filter them by popularity or rating, read a book's reviews, and add your own review.

## What I Learned

### Eloquent Relationships
- **One to Many relationships** using `hasMany()` (a `Book` has many `Review`s) and `belongsTo()` (a `Review` belongs to a `Book`).
- **Querying related models**, e.g. `$book->reviews()->latest()->get()`.
- **Associating related models**, e.g. creating a review through the relationship with `$book->reviews()->create($data)`, and using `->for($book)` in factories.

### Database: Migrations, Factories & Seeders
- Writing migrations with **foreign keys** using `foreignId()->constrained()->cascadeOnDelete()`.
- Building **model factories** with fake data and **factory states** (`good()`, `average()`, `bad()`) to generate reviews with different rating ranges.
- **Seeding** the database with related records.
- Experimenting with queries in **`php artisan tinker`** and inspecting the generated SQL.

### Query Building
- **Local query scopes** (`scopeTitle`, `scopePopular`, `scopeHighestRated`, `scopeMinReviews`, `scopePopularLastMonth`, ...) to keep queries reusable and readable.
- **Aggregations on relations** with `withCount()` and `withAvg()` to get the number of reviews and average rating per book.
- Filtering aggregates with `having()` and date ranges with `where()` / `whereBetween()`.
- **Conditional queries** with `when()`, and choosing a query using PHP's `match` expression.
- **Pagination** with `paginate()` and rendering links with `$books->links()`.

### Controllers & Routing
- **Controllers and Resource Controllers** (`Route::resource()`), limiting routes with `->only()`.
- **Scoped (nested) resource controllers**: `Route::resource('books.reviews', ...)->scoped(...)`, producing URLs like `/books/{book}/reviews/create`.
- **Route model binding** (`Book $book` injected straight into controller methods).
- **Form validation** with `$request->validate()`, CSRF protection with `@csrf`, and showing errors with `@error` and `old()`.

### Caching
- **Cache and caching queries** with `cache()->remember()`, using keys built from the filter, search title, and page number.
- Caching paginated results as arrays and rebuilding them with `Model::hydrate()` and `LengthAwarePaginator`.
- **Invalidating the cache** with `cache()->forget()` inside **model events** (`created`, `updated`, `deleted`) registered in the model's `booted()` method, so a new review clears its book's cached reviews.

### Rate Limiting
- Defining a custom **rate limiter** with `RateLimiter::for('reviews', ...)` in `AppServiceProvider` (3 reviews per hour, per user or IP).
- Applying it to a single route action with the `throttle:reviews` middleware.

### Blade
- **Template inheritance** with `@extends`, `@section`, and `@yield`.
- **Blade components**: a class-based `<x-star-rating>` component that renders a book's average rating as stars.
- Blade directives such as `@forelse`, `@foreach`, `@for`, and `@php`, and helpers like `Str::plural()`.
- Styling with Tailwind CSS (via CDN) and `@apply`.

## Getting Started

Requirements: PHP 8.3+, Composer, and a database (SQLite, MySQL, etc.).

```bash
git clone https://github.com/SyedIhsan/laravel-book-review.git
cd laravel-book-review

composer install
cp .env.example .env
php artisan key:generate

# configure your database in .env, then:
php artisan migrate --seed

php artisan serve
```

Then open http://localhost:8000.

## Built With

- [Laravel](https://laravel.com) 13
- [Tailwind CSS](https://tailwindcss.com)
