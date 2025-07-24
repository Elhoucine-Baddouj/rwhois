# RWHOIS Dashboard

A simple web dashboard to manage multiple RWHOIS (Referral WHOIS) servers remotely. The application allows you to control servers, manage organizations, and users from a single interface.

## Features

- Remote control of RWHOIS servers (start, stop, view logs, update)
- Manage organizations (clients, companies)
- Manage users (roles: admin, manager, observer)
- Dashboard with server and organization statistics

## Technologies

- PHP 7.4+
- MySQL
- Bootstrap 4 & AdminLTE 3 (UI)
- jQuery (AJAX)
- Bash scripts for remote server control (via SSH)

## Project Structure

```
project-root/
├── index.php
├── app/
│   └── Controllers/
│       ├── BaseController.php
│       ├── DashboardController.php
│       ├── ServerController.php
│       ├── OrganizationController.php
│       └── UserController.php
├── views/
│   ├── dashboard/
│   ├── servers/
│   ├── organizations/
│   └── users/
├── scripts/
│   └── rwhois-remote-control.sh
├── database/
│   └── schema.sql
└── README.md
```

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache, Nginx, or PHP built-in server)
- SSH access to remote VPS servers

### Steps
1. Clone the repository:
   ```bash
   git clone <repo-url>
   cd project-root
   ```
2. Import the database schema:
   ```bash
   mysql -u root -p < database/schema.sql
   ```
3. Configure your web server to point to the project directory.
4. Configure SSH keys for passwordless access to your VPS servers.
5. Access the dashboard in your browser (e.g., http://localhost:8000).

## Database Schema

See `database/schema.sql` for the current structure (servers, users, organizations).

## Remote Server Control

The script `scripts/rwhois-remote-control.sh` is used to control RWHOIS servers remotely via SSH (start, stop, logs, update).

## License

MIT License. 