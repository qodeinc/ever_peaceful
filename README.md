# Ever Peaceful - Mental Health Platform

A comprehensive mental health platform designed to serve psychiatric students, service providers (therapists), and administrators.

## Overview

Ever Peaceful is a full-stack web application that facilitates mental health services through:

- **User Management**: Registration, authentication, and profile management for users, therapists, and admins
- **Therapy Sessions**: Booking, scheduling, video conferencing, and session notes
- **Assessments**: Multiple psychological assessments (PHQ-9, GAD-7, CAGE, AUDIT-C, personality tests, etc.)
- **Messaging**: Real-time chat between users and therapists
- **Calendar Management**: Appointment scheduling with Calendly integration
- **Progress Tracking**: Mood tracking, journaling, and progress analytics
- **Resource Library**: Educational materials, therapy resources, and PDFs
- **Billing & Payments**: Session payments and revenue tracking
- **Analytics & Reporting**: Platform-wide analytics, user reports, and therapist performance
- **Alert System**: Risk assessment alerts and urgent notifications
- **Professional Development**: Training materials for therapists

## Technology Stack

### Frontend
- Vue.js 3.5+ (Composition API)
- TypeScript
- Vite 7+
- Pinia (State Management)
- Vue Router 4+
- Tailwind CSS 4+
- Axios
- Socket.io Client

### Backend
- Laravel 11+ (PHP 8.2+)
- PostgreSQL 15+
- Redis 7+
- Laravel Sanctum (API Authentication)
- Laravel Queue (Redis driver)
- Laravel Echo + Socket.io (Real-time)

### Infrastructure
- Docker + Docker Compose
- Nginx
- PostgreSQL
- Redis

## Project Structure

```
ever_peaceful/
├── frontend/          # Vue.js frontend application
├── backend/           # Laravel backend application
├── docker/            # Docker configuration files
├── docs/              # Documentation
├── architecture.md    # Architecture documentation
├── tasks.md           # Development task plan
└── README.md          # This file
```

## Getting Started

### Prerequisites
- Docker & Docker Compose
- Node.js 20+ (for local frontend development)
- PHP 8.2+ (for local backend development)
- Composer
- PostgreSQL 15+ (if not using Docker)

### Development Setup

1. Clone the repository
2. Set up Docker containers (see docker-compose.yml)
3. Install backend dependencies: `cd backend && composer install`
4. Install frontend dependencies: `cd frontend && npm install`
5. Configure environment variables (see .env.example files)
6. Run database migrations: `cd backend && php artisan migrate`
7. Start development servers

## Documentation

- [Architecture Documentation](./architecture.md)
- [Development Task Plan](./tasks.md)

## License

MIT License - See [LICENSE](./LICENSE) file for details

## Status

🚧 Under Active Development

