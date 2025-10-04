# Ever Peaceful - Mental Health Platform Architecture

## 🏗️ **System Overview**

**Ever Peaceful** is a comprehensive mental health platform connecting users with licensed therapists, featuring real-time communication, session management, progress tracking, and administrative oversight.

### **Tech Stack**
- **Frontend**: Vue 3 + TypeScript + TailwindCSS + Pinia
- **Backend**: Go + GraphQL + PostgreSQL
- **Real-time**: WebSockets (Gorilla WebSocket)
- **Authentication**: JWT + OAuth (Google, Microsoft)
- **File Storage**: AWS S3 / Local Storage
- **Caching**: Redis
- **Message Queue**: Redis Pub/Sub
- **Monitoring**: Prometheus + Grafana

---

## 📁 **Project Structure**

```
ever_peaceful/
├── frontend/                          # Vue 3 Frontend
│   ├── src/
│   │   ├── components/                # Reusable UI components
│   │   │   ├── layout/               # Layout components
│   │   │   ├── forms/                # Form components
│   │   │   ├── ui/                   # Base UI components
│   │   │   └── icons/                # Icon components
│   │   ├── views/                    # Page components
│   │   │   ├── auth/                 # Authentication pages
│   │   │   ├── dashboard/            # Dashboard pages
│   │   │   ├── user/                 # User-specific pages
│   │   │   ├── therapist/            # Therapist-specific pages
│   │   │   └── admin/                # Admin pages
│   │   ├── stores/                   # Pinia state management
│   │   │   ├── auth.ts              # Authentication state
│   │   │   ├── user.ts              # User state
│   │   │   ├── therapist.ts         # Therapist state
│   │   │   ├── session.ts           # Session state
│   │   │   ├── chat.ts              # Chat state
│   │   │   └── notification.ts      # Notification state
│   │   ├── composables/              # Vue composables
│   │   │   ├── useAuth.ts           # Authentication logic
│   │   │   ├── useGraphQL.ts        # GraphQL client
│   │   │   ├── useWebSocket.ts      # WebSocket connection
│   │   │   └── useNotifications.ts  # Notification handling
│   │   ├── services/                 # API services
│   │   │   ├── graphql/             # GraphQL queries/mutations
│   │   │   ├── websocket.ts         # WebSocket service
│   │   │   └── storage.ts           # Local storage service
│   │   ├── types/                    # TypeScript type definitions
│   │   │   ├── auth.ts              # Auth types
│   │   │   ├── user.ts              # User types
│   │   │   ├── session.ts           # Session types
│   │   │   └── common.ts            # Common types
│   │   ├── router/                   # Vue Router configuration
│   │   ├── assets/                   # Static assets
│   │   └── main.ts                   # Application entry point
│   ├── package.json
│   └── vite.config.ts
│
├── backend/                          # Go Backend
│   ├── cmd/                          # Application entry points
│   │   └── server/
│   │       └── main.go              # Main server entry point
│   ├── internal/                     # Private application code
│   │   ├── config/                  # Configuration management
│   │   │   ├── config.go            # Configuration structs
│   │   │   └── env.go               # Environment variables
│   │   ├── domain/                  # Business logic layer
│   │   │   ├── user/                # User domain
│   │   │   │   ├── entity.go        # User entity
│   │   │   │   ├── repository.go    # Repository interface
│   │   │   │   ├── service.go       # Business logic
│   │   │   │   └── validator.go     # Validation logic
│   │   │   ├── therapist/           # Therapist domain
│   │   │   ├── session/             # Session domain
│   │   │   ├── message/             # Message domain
│   │   │   ├── notification/        # Notification domain
│   │   │   └── auth/                # Authentication domain
│   │   ├── infrastructure/          # External concerns
│   │   │   ├── database/            # Database layer
│   │   │   │   ├── postgres/        # PostgreSQL implementation
│   │   │   │   │   ├── connection.go
│   │   │   │   │   ├── migrations/  # Database migrations
│   │   │   │   │   └── repositories/ # Repository implementations
│   │   │   │   └── redis/           # Redis implementation
│   │   │   ├── external/            # External service integrations
│   │   │   │   ├── calendly/        # Calendly integration
│   │   │   │   ├── oauth/           # OAuth providers
│   │   │   │   ├── email/           # Email service
│   │   │   │   └── storage/         # File storage
│   │   │   └── websocket/           # WebSocket server
│   │   ├── delivery/                # Delivery layer (API handlers)
│   │   │   ├── graphql/             # GraphQL resolvers
│   │   │   │   ├── schema.graphql   # GraphQL schema
│   │   │   │   ├── resolvers/       # Resolver implementations
│   │   │   │   └── middleware/      # GraphQL middleware
│   │   │   ├── http/                # HTTP handlers
│   │   │   │   ├── auth.go          # Auth endpoints
│   │   │   │   ├── upload.go        # File upload endpoints
│   │   │   │   └── health.go        # Health check endpoints
│   │   │   └── websocket/           # WebSocket handlers
│   │   ├── middleware/              # HTTP middleware
│   │   │   ├── auth.go              # Authentication middleware
│   │   │   ├── cors.go              # CORS middleware
│   │   │   ├── logging.go           # Logging middleware
│   │   │   └── rate_limit.go        # Rate limiting
│   │   └── pkg/                     # Shared packages
│   │       ├── jwt/                 # JWT utilities
│   │       ├── password/            # Password hashing
│   │       ├── validator/           # Input validation
│   │       ├── logger/              # Logging utilities
│   │       └── errors/              # Error handling
│   ├── api/                         # API definitions
│   │   ├── graphql/                 # GraphQL schema files
│   │   └── openapi/                 # OpenAPI specifications
│   ├── migrations/                  # Database migrations
│   ├── scripts/                     # Build and deployment scripts
│   ├── docker/                      # Docker configurations
│   │   ├── Dockerfile
│   │   └── docker-compose.yml
│   ├── go.mod
│   ├── go.sum
│   └── Makefile
│
├── docs/                            # Documentation
│   ├── api/                         # API documentation
│   ├── deployment/                  # Deployment guides
│   └── development/                 # Development guides
│
├── docker-compose.yml               # Development environment
├── .env.example                     # Environment variables template
├── .gitignore
└── README.md
```

---

## 🧠 **State Management Architecture**

### **Frontend State (Pinia Stores)**

#### **1. Auth Store (`stores/auth.ts`)**
```typescript
interface AuthState {
  user: User | null
  token: string | null
  isAuthenticated: boolean
  isLoading: boolean
  error: string | null
}

interface AuthActions {
  login(credentials: LoginCredentials): Promise<void>
  register(userData: RegisterData): Promise<void>
  logout(): void
  refreshToken(): Promise<void>
  resetPassword(email: string): Promise<void>
}
```

#### **2. User Store (`stores/user.ts`)**
```typescript
interface UserState {
  profile: UserProfile | null
  sessions: Session[]
  progress: ProgressData
  notifications: Notification[]
  preferences: UserPreferences
}

interface UserActions {
  fetchProfile(): Promise<void>
  updateProfile(data: Partial<UserProfile>): Promise<void>
  fetchSessions(): Promise<void>
  bookSession(sessionData: SessionBooking): Promise<void>
  updatePreferences(prefs: UserPreferences): Promise<void>
}
```

#### **3. Chat Store (`stores/chat.ts`)**
```typescript
interface ChatState {
  conversations: Conversation[]
  activeConversation: Conversation | null
  messages: Message[]
  unreadCount: number
  isConnected: boolean
}

interface ChatActions {
  connect(): void
  disconnect(): void
  sendMessage(content: string): Promise<void>
  markAsRead(conversationId: string): void
  fetchConversations(): Promise<void>
}
```

### **Backend State Management**

#### **1. Domain Services**
- **UserService**: User business logic
- **TherapistService**: Therapist business logic
- **SessionService**: Session management
- **MessageService**: Real-time messaging
- **NotificationService**: Notification handling

#### **2. Repository Pattern**
```go
type UserRepository interface {
    Create(ctx context.Context, user *User) error
    GetByID(ctx context.Context, id string) (*User, error)
    GetByEmail(ctx context.Context, email string) (*User, error)
    Update(ctx context.Context, user *User) error
    Delete(ctx context.Context, id string) error
}
```

---

## 🔌 **Service Connections & Communication**

### **1. GraphQL API Layer**

#### **Schema Structure**
```graphql
type Query {
  me: User
  sessions: [Session!]!
  therapists: [Therapist!]!
  messages(conversationId: ID!): [Message!]!
  notifications: [Notification!]!
}

type Mutation {
  login(input: LoginInput!): AuthPayload!
  register(input: RegisterInput!): AuthPayload!
  bookSession(input: SessionBookingInput!): Session!
  sendMessage(input: MessageInput!): Message!
  updateProfile(input: ProfileUpdateInput!): User!
}

type Subscription {
  messageReceived(conversationId: ID!): Message!
  sessionUpdated(sessionId: ID!): Session!
  notificationReceived: Notification!
}
```

#### **Resolver Implementation**
```go
type Resolver struct {
    userService        *domain.UserService
    sessionService     *domain.SessionService
    messageService     *domain.MessageService
    notificationService *domain.NotificationService
}

func (r *Resolver) Login(ctx context.Context, input LoginInput) (*AuthPayload, error) {
    return r.userService.Login(ctx, input)
}
```

### **2. WebSocket Real-time Communication**

#### **Connection Management**
```go
type WebSocketManager struct {
    clients    map[string]*Client
    register   chan *Client
    unregister chan *Client
    broadcast  chan []byte
}

type Client struct {
    ID       string
    UserID   string
    Role     string
    Conn     *websocket.Conn
    Send     chan []byte
    Manager  *WebSocketManager
}
```

#### **Message Types**
```go
type MessageType string

const (
    MessageTypeChat        MessageType = "chat"
    MessageTypeNotification MessageType = "notification"
    MessageTypeSessionUpdate MessageType = "session_update"
    MessageTypeTyping      MessageType = "typing"
)
```

### **3. Database Layer**

#### **PostgreSQL Schema**
```sql
-- Users table
CREATE TABLE users (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL,
    profile_data JSONB,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

-- Sessions table
CREATE TABLE sessions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    therapist_id UUID REFERENCES users(id),
    client_id UUID REFERENCES users(id),
    type VARCHAR(50) NOT NULL,
    scheduled_at TIMESTAMP NOT NULL,
    status VARCHAR(50) DEFAULT 'scheduled',
    notes TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Messages table
CREATE TABLE messages (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    conversation_id UUID NOT NULL,
    sender_id UUID REFERENCES users(id),
    content TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT NOW(),
    read_at TIMESTAMP
);
```

#### **Repository Implementation**
```go
type PostgresUserRepository struct {
    db *sql.DB
}

func (r *PostgresUserRepository) Create(ctx context.Context, user *User) error {
    query := `
        INSERT INTO users (email, password_hash, role, profile_data)
        VALUES ($1, $2, $3, $4)
        RETURNING id, created_at, updated_at
    `
    return r.db.QueryRowContext(ctx, query, user.Email, user.PasswordHash, user.Role, user.ProfileData).Scan(&user.ID, &user.CreatedAt, &user.UpdatedAt)
}
```

---

## 🔄 **Data Flow Architecture**

### **1. Authentication Flow**
```
Frontend → GraphQL Mutation → Auth Resolver → User Service → Repository → Database
                ↓
        JWT Token Generation → Redis Cache → Response to Frontend
```

### **2. Real-time Messaging Flow**
```
User A sends message → GraphQL Mutation → Message Resolver → Message Service
                ↓
        Database Storage → WebSocket Broadcast → User B receives message
```

### **3. Session Booking Flow**
```
User books session → GraphQL Mutation → Session Resolver → Session Service
                ↓
        Calendly Integration → Database Storage → Notification Service
                ↓
        WebSocket Notification → Therapist receives notification
```

---

## 🛡️ **Security Architecture**

### **1. Authentication & Authorization**
- **JWT Tokens**: Stateless authentication
- **Refresh Tokens**: Secure token renewal
- **Role-based Access**: User, Therapist, Admin roles
- **OAuth Integration**: Google, Microsoft login

### **2. Data Protection**
- **Password Hashing**: bcrypt with salt
- **Data Encryption**: AES-256 for sensitive data
- **HTTPS**: TLS 1.3 for all communications
- **CORS**: Configured for frontend domain

### **3. Input Validation**
- **GraphQL Schema**: Type-safe queries
- **Go Validators**: Server-side validation
- **SQL Injection Prevention**: Parameterized queries
- **XSS Protection**: Input sanitization

---

## 📊 **Monitoring & Observability**

### **1. Logging**
```go
type Logger struct {
    level  string
    format string
    output io.Writer
}

func (l *Logger) LogRequest(ctx context.Context, req *http.Request, duration time.Duration) {
    l.Info("HTTP Request", 
        "method", req.Method,
        "path", req.URL.Path,
        "duration", duration,
        "user_id", getUserIDFromContext(ctx),
    )
}
```

### **2. Metrics**
- **Prometheus Metrics**: Request rates, response times, error rates
- **Custom Metrics**: Session bookings, message counts, user activity
- **Health Checks**: Database connectivity, external service status

### **3. Error Handling**
```go
type AppError struct {
    Code    string `json:"code"`
    Message string `json:"message"`
    Details string `json:"details,omitempty"`
}

func (e *AppError) Error() string {
    return e.Message
}
```

---

## 🚀 **Deployment Architecture**

### **1. Development Environment**
```yaml
# docker-compose.yml
version: '3.8'
services:
  postgres:
    image: postgres:15
    environment:
      POSTGRES_DB: ever_peaceful_dev
      POSTGRES_USER: dev
      POSTGRES_PASSWORD: dev_password
    ports:
      - "5432:5432"
  
  redis:
    image: redis:7-alpine
    ports:
      - "6379:6379"
  
  backend:
    build: ./backend
    ports:
      - "8080:8080"
    environment:
      DATABASE_URL: postgres://dev:dev_password@postgres:5432/ever_peaceful_dev
      REDIS_URL: redis://redis:6379
    depends_on:
      - postgres
      - redis
```

### **2. Production Architecture**
- **Load Balancer**: Nginx/HAProxy
- **Application Servers**: Multiple Go instances
- **Database**: PostgreSQL with read replicas
- **Cache**: Redis cluster
- **File Storage**: AWS S3
- **CDN**: CloudFront for static assets

---

## 🔧 **Development Workflow**

### **1. Local Development Setup**
```bash
# Backend setup
cd backend
go mod download
go run cmd/server/main.go

# Frontend setup
cd frontend
npm install
npm run dev

# Database setup
make migrate-up
make seed-data
```

### **2. Testing Strategy**
- **Unit Tests**: Domain logic testing
- **Integration Tests**: API endpoint testing
- **E2E Tests**: Frontend-backend integration
- **Load Tests**: Performance testing

### **3. CI/CD Pipeline**
```yaml
# .github/workflows/deploy.yml
name: Deploy
on:
  push:
    branches: [main]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Run tests
        run: make test
  
  deploy:
    needs: test
    runs-on: ubuntu-latest
    steps:
      - name: Deploy to production
        run: make deploy
```

---

## 📈 **Scalability Considerations**

### **1. Horizontal Scaling**
- **Stateless Services**: JWT-based authentication
- **Database Sharding**: User-based partitioning
- **Microservices**: Domain-based service separation
- **Caching Strategy**: Redis for session data

### **2. Performance Optimization**
- **Database Indexing**: Optimized queries
- **Connection Pooling**: Efficient database connections
- **GraphQL Caching**: Query result caching
- **CDN Integration**: Static asset delivery

### **3. Monitoring & Alerting**
- **Application Metrics**: Prometheus + Grafana
- **Log Aggregation**: ELK Stack
- **Error Tracking**: Sentry integration
- **Uptime Monitoring**: Health check endpoints

---

This architecture provides a robust, scalable foundation for the Ever Peaceful mental health platform, ensuring security, performance, and maintainability while supporting the complex requirements of a healthcare application.

