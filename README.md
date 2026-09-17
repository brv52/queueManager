# QueueManager

QueueManager is a PHP-based queue management web application designed for two audience types: companies managing service queues and end users joining and tracking their place in line. The application provides role-based access, queue creation, search, profile management, and real-time queue-state visibility through server-side database logic.

This project is built as a lightweight, session-driven web application using PHP, MySQL, and a simple MVC-inspired page routing structure. It is ideal for small business environments, appointment systems, or service counters that need a basic queue flow without introducing a full distributed event-bus or message broker.

## 1) Project Overview

### What the system does

- Allows companies to register, log in, create queues, and manage occupancy.
- Allows users to register, log in, search for queues, and join a queue.
- Tracks queue positions using a database-backed queue model rather than in-memory state.
- Shows queue utilization bars and user-specific position information.
- Includes role restrictions so user and company flows stay separated.

### Core user flows

- Company flow:
  - Register account
  - Sign in as company
  - Create a queue and set its capacity
  - View queue occupancy and update or delete queues
- User flow:
  - Register account
  - Sign in as user
  - Search by queue name
  - Join first available spot
  - View current position and leave the queue when needed

### Product positioning

QueueManager is a pragmatic, business-ready prototype for queue organization in physical service environments such as salons, clinics, help desks, or retail counters. It emphasizes maintainability, simple deployment, and clear role separation over high-concurrency distributed architecture.

---

## 2) Core Architecture & Technical Implementation

### High-level architecture

The application follows a straightforward server-rendered web architecture:

- Entry point: public/index.php
- Request routing: includes/router.php
- Authentication and authorization: includes/auth.php
- Core business logic: includes/functions.php and includes/queues_ops.php
- Page templates: templates/header.php and templates/footer.php
- Styling: assets/*.css
- Persistent state: MySQL database

### Request flow

1. Requests are routed through public/index.php.
2. The router resolves the page parameter and loads the relevant page under pages/.
3. Each page performs authorization checks and calls utility functions from includes/.
4. Business logic interacts with the database via PDO.
5. Page output is rendered using server-side PHP templates and CSS assets.

### Authentication and session model

The project uses PHP sessions for identity management.

- Session state stores user_id, username, role, and avatar.
- Role checks are enforced through functions such as requireLogin(), requireUser(), and requireCompany().
- Password hashing is handled with password_hash() and password_verify() for secure storage and validation.

### Database-driven queue model

Queue state is persisted in MySQL, not in a socket or in-memory process.

Relevant entities include:

- users
- companies
- queues
- queue_places
- user_queue

The queue_places table stores each slot of a queue, including:

- queue_id
- position
- occupied
- user_id

This allows the system to assign the next free spot, compute occupancy, and display each user’s position in a consistent way.

### Queue logic and business rules

The queue lifecycle is implemented with prepared SQL statements:

- addQueue() creates a queue and initializes queue_places rows.
- joinQueue() selects the first unoccupied position and updates that row.
- leaveQueue() clears the reservation and removes the queue membership record.
- showCompanyQueues() and showUserQueues() compute occupancy and show progress bars.
- searchQueues() searches queue names with a prefix match and returns candidate results.

### Security and reliability considerations

The current implementation includes several production-minded patterns:

- Parameterized queries using PDO prepared statements
- Password hashing with the default PHP password API
- Server-side redirect handling for auth failures and generic errors
- Role-based access control
- Validation for duplicate username/email entries and upload handling

### Concurrency and I/O model

This repository does not implement a non-blocking I/O event loop, socket server, or Docker orchestration layer. Instead, it is a classic synchronous PHP application:

- request comes in via HTTP
- PHP processes the request
- DB queries run synchronously
- response is returned to the browser

In other words, there is no Node.js-style event loop, no WebSocket server, and no Docker Compose orchestrator in the current codebase. The system is designed around request/response behavior and relational database persistence, which is appropriate for a small-to-medium queue application.

---

## 3) Prerequisites & Build Instructions

### Prerequisites

Before running the app, ensure the following are installed:

- PHP 8.1 or newer
- Composer
- MySQL 8.0 or compatible MariaDB
- Web server such as Apache or Nginx
- Optional: PHP built-in development server for local testing

### Dependency installation

From the project root:

```bash
composer install
```

This installs the PHP dependency defined in composer.json, including vlucas/phpdotenv.

### Database setup

The repository includes a MySQL dump at backup.sql. Import it into your local database:

```bash
mysql -u root -p < backup.sql
```

Then create a database configuration file at config/database.php. This file is intentionally not committed in the repository and is ignored via .gitignore.

Example:

```php
<?php
$host = 'localhost';
$db   = 'queue_manager_db';
$user = 'root';
$pass = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
```

If you prefer environment variables, you may also load them from a .env file, but the current codebase expects a concrete config/database.php file during runtime.

### No Makefile / CMake present

This repository does not currently include a Makefile or CMakeLists.txt. The project is configured as a lightweight PHP app rather than a compiled system. For local development, the normal flow is:

1. Install Composer dependencies
2. Configure the database
3. Start a PHP web server or Apache virtual host
4. Access the app through the browser

### Local run instructions

Using PHP's built-in server:

```bash
php -S localhost:8000 -t public
```

Then open:

```text
http://localhost:8000/index.php
```

If you are using Apache or Nginx, configure the document root to point to the public/ directory and ensure index.php is the entry file.

---

## 4) Usage Examples

### Example 1: Register a company

Navigate to the landing page and choose Register.

- Select role: Company
- Enter username, email, and password
- Submit the form

Once created, the company can log in and create queues.

### Example 2: Create a queue

After logging in as a company:

- Open the profile page
- Click Add Queue
- Enter the queue name and number of places
- Save the form

The system creates queue slots and populates occupancy values in the database.

### Example 3: Join a queue as a user

As a user:

- Log in with the user role
- Go to Search
- Enter the queue name or a prefix
- Select the queue
- Join the first available place

The application will assign the earliest available slot and render the user’s current position.

### Example 4: Leave the queue

From the profile page, a user can open a queue card and choose Leave Queue. The record is removed from queue_places and user_queue.

---

## Project Structure

```text
.
├── assets/                 # CSS and static UI assets
├── backup.sql              # MySQL database schema and seed data
├── composer.json           # PHP dependency manifest
├── composer.lock           # Composer lock file
├── error/                  # Error handling pages
├── includes/               # Auth, router, and queue logic
├── pages/                  # Page-level controllers/views
├── public/                 # Public entry point
├── templates/              # Shared header/footer layout
├── uploads/                # Profile avatar storage
├── .env.example            # Sample environment variables
├── .gitignore              # Sensitive files and generated artifacts
├── README.md               # Project documentation
└── vendor/                 # Composer-installed dependencies
```

---

## Notes for Recruiters / Evaluators

This project demonstrates:

- full-stack PHP development with a server-rendered architecture
- relational database design and SQL optimization patterns
- role-based access control and secure authentication flows
- practical business logic for queue assignment and occupancy tracking
- clean page structure and separation between routing, auth, and domain logic

It is a solid example of a business-oriented web application built with traditional server-side technologies, with an emphasis on maintainability and real-world workflow modeling rather than asynchronous backend infrastructure.

---

## License

This project does not currently declare a software license. If you plan to distribute or reuse it publicly, it is recommended to add a LICENSE file appropriate to your organization or project goals.
