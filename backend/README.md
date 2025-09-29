# Ever Peaceful Backend Architecture

```markdown
# Ever Peaceful - Backend Architecture

## Overview
Backend system for a mental health platform built with Go, GraphQL, and PostgreSQL.

## Project Structure
```
EverPeaceful-backend/
├── cmd/
│   ├── server/
│   │   └── main.go                 # Application entry point
│   └── migrate/
│       └── main.go                 # Database migration runner
├── internal/
│   ├── config/
│   │   └── config.go               # Configuration management
│   ├── database/
│   │   ├── connection.go           # DB connection setup
│   │   └── migrations/
│   │       ├── 001_init.sql        # Initial schema
│   │       └── 002_seed_data.sql   # Seed data
│   ├── graph/
│   │   ├── schema.graphql          # GraphQL schema definition
│   │   ├── generated.go            # Generated GraphQL code
│   │   └── resolvers/
│   │       ├── resolvers.go        # Main resolver struct
│   │       ├── query.go            # Query resolvers
│   │       ├── mutation.go         # Mutation resolvers
│   │       └── types.go            # Custom type resolvers
│   ├── models/
│   │   ├── user.go                 # User model
│   │   ├── therapist.go            # Therapist model
│   │   ├── appointment.go          # Appointment model
│   │   ├── session.go              # Therapy session model
│   │   ├── assessment.go           # Assessment model
│   │   ├── resource.go             # Resource model
│   │   └── note.go                 # Session notes model
│   ├── services/
│   │   ├── auth/
│   │   │   ├── service.go          # Authentication service
│   │   │   └── middleware.go       # Auth middleware
│   │   ├── user/
│   │   │   └── service.go          # User management
│   │   ├── therapist/
│   │   │   └── service.go          # Therapist management
│   │   ├── appointment/
│   │   │   └── service.go          # Booking system
│   │   ├── session/
│   │   │   └── service.go          # Session management
│   │   ├── assessment/
│   │   │   └── service.go          # Assessment tools
│   │   ├── matching/
│   │   │   └── service.go          # Therapist matching algorithm
│   │   └── notification/
│   │       └── service.go          # Email/SMS notifications
│   ├── repository/
│   │   ├── user_repo.go            # User data access
│   │   ├── therapist_repo.go       # Therapist data access
│   │   ├── appointment_repo.go     # Appointment data access
│   │   ├── session_repo.go         # Session data access
│   │   ├── assessment_repo.go      # Assessment data access
│   │   └── resource_repo.go        # Resource data access
│   ├── utils/
│   │   ├── validation.go           # Input validation
│   │   ├── pagination.go           # Pagination helpers
│   │   ├── file_upload.go          # File upload handling
│   │   └── security.go             # Security utilities
│   └── middleware/
│       ├── auth.go                 # Authentication middleware
│       ├── cors.go                 # CORS handling
│       ├── logging.go              # Request logging
│       └── recovery.go             # Error recovery
├── pkg/
│   ├── email/
│   │   └── client.go               # Email service client
│   ├── sms/
│   │   └── client.go               # SMS service client
│   └── storage/
│       ├── local.go                # Local file storage
│       └── s3.go                   # AWS S3 storage
├── api/
│   └── graphql/
│       └── handler.go              # GraphQL HTTP handler
├── scripts/
│   ├── deploy.sh                   # Deployment script
│   └── test_db.sh                  # Test database setup
├── docker/
│   ├── Dockerfile
│   └── docker-compose.yml
├── .env.example                    # Environment variables template
├── go.mod
├── go.sum
└── README.md
```

## Component Descriptions

### `/cmd` - Application Entry Points
- **`server/main.go`**: Initializes configuration, database, and starts HTTP server
- **`migrate/main.go`**: Handles database migrations and seeding

### `/internal/config` - Configuration Management
- Loads environment variables
- Database configuration
- JWT secrets
- Third-party API keys
- File upload settings

### `/internal/database` - Database Layer
- **PostgreSQL connection pooling**
- Migration management using `golang-migrate`
- Database seeding for initial data

### `/internal/graph` - GraphQL Layer
- **Schema definition** in GraphQL SDL
- **Generated code** using gqlgen
- **Resolvers** handling GraphQL operations
- **Query resolvers**: Data fetching operations
- **Mutation resolvers**: Data modification operations

### `/internal/models` - Data Models
```go
// Example Therapist model
type Therapist struct {
    ID            string         `json:"id" db:"id"`
    UserID        string         `json:"user_id" db:"user_id"`
    Specialties   pq.StringArray `json:"specialties" db:"specialties"`
    Languages     pq.StringArray `json:"languages" db:"languages"`
    Education     string         `json:"education" db:"education"`
    Experience    int            `json:"experience" db:"experience"`
    Rating        float64        `json:"rating" db:"rating"`
    Availability  bool           `json:"availability" db:"availability"`
    CreatedAt     time.Time      `json:"created_at" db:"created_at"`
    UpdatedAt     time.Time      `json:"updated_at" db:"updated_at"`
}
```

### `/internal/services` - Business Logic
- **Auth Service**: JWT token management, password hashing
- **User Service**: User profile management
- **Therapist Service**: Therapist directory, profile management
- **Appointment Service**: Booking system, scheduling
- **Session Service**: Therapy session management, notes
- **Assessment Service**: Quiz and assessment tools
- **Matching Service**: Therapist-client matching algorithm
- **Notification Service**: Email/SMS notifications

### `/internal/repository` - Data Access Layer
- **Database abstractions** using sqlc or manual queries
- **CRUD operations** for all entities
- **Complex queries** for search and filtering

### `/internal/middleware` - HTTP Middleware
- **Authentication**: JWT validation
- **CORS**: Cross-origin resource sharing
- **Logging**: Request/response logging
- **Recovery**: Panic recovery

## Database Schema (Key Tables)

### Core Tables
- `users` - Base user information
- `therapists` - Therapist profiles and credentials
- `clients` - Client profiles
- `appointments` - Booking information
- `sessions` - Therapy session records
- `session_notes` - Therapist session documentation
- `assessments` - Quiz and assessment data
- `resources` - Mental health resources (PDFs, videos)
- `emergency_contacts` - Crisis support information

## State Management

### Where State Lives:
1. **Database State** (PostgreSQL):
   - User accounts and profiles
   - Therapist data and availability
   - Appointments and sessions
   - Assessment results
   - Session notes and progress

2. **Application State** (In-Memory):
   - JWT token blacklist (optional, for logout)
   - Rate limiting counters
   - WebSocket connections for real-time features

3. **File Storage**:
   - Uploaded resources (PDFs, videos, images)
   - User documents
   - Therapist credentials

## Service Connections

### Dependency Flow:
```
HTTP Request → Middleware → GraphQL Handler → Resolvers → Services → Repository → Database
```

### Service Interactions:
- **Authentication Service** used by all other services for authorization
- **Therapist Service** depends on User Service for profile data
- **Appointment Service** coordinates with Therapist and Notification services
- **Matching Service** uses Therapist and Assessment services for recommendations

## Key Features Implementation

### 1. Therapist-Client Matching
```go
type MatchingService struct {
    therapistRepo repository.TherapistRepository
    assessmentRepo repository.AssessmentRepository
}

func (s *MatchingService) FindTherapists(criteria MatchingCriteria) ([]Therapist, error) {
    // Implement matching algorithm based on:
    // - Specialization
    // - Language preference
    // - Cultural background
    // - Availability
    // - Client assessment results
}
```

### 2. Session Management
```go
type SessionService struct {
    sessionRepo    repository.SessionRepository
    noteRepo       repository.NoteRepository
    timerService   *TimerService
}

func (s *SessionService) StartSession(sessionID string) error {
    // Start session timer
    // Initialize session notes
    // Update therapist availability
}
```

### 3. Assessment System
```go
type AssessmentService struct {
    assessmentRepo repository.AssessmentRepository
    scoringService *ScoringService
}

func (s *AssessmentService) SubmitAssessment(answers AssessmentAnswers) (*AssessmentResult, error) {
    // Calculate scores based on evidence-based methodologies
    // Generate personalized recommendations
    // Store results for progress tracking
}
```

## Security & Compliance

### HIPAA Compliance:
- **Data Encryption** at rest and in transit
- **Access Logging** for audit trails
- **Role-Based Access Control** (RBAC)
- **Secure File Storage** for session notes
- **Regular Security Audits**

### Authentication Flow:
```
Client Login → JWT Token Generation → Token Validation → Role-Based Access
```

## Deployment & Infrastructure

### Environment Configuration:
```yaml
database:
  host: ${DB_HOST}
  port: ${DB_PORT}
  name: ${DB_NAME}
  user: ${DB_USER}
  password: ${DB_PASSWORD}

server:
  port: ${SERVER_PORT}
  environment: ${ENVIRONMENT}

jwt:
  secret: ${JWT_SECRET}
  expiration: ${JWT_EXPIRATION}
```

This architecture provides a scalable, maintainable backend system that supports almost all the features required while maintaining security and performance standards.
```


