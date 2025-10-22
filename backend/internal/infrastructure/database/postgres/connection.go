package postgres

import (
	"database/sql"
	"errors"
	"time"

	_ "github.com/lib/pq"
)

// Connect opens a PostgreSQL connection using a standard DATABASE_URL and configures pooling.
func Connect(databaseURL string) (*sql.DB, error) {
	if databaseURL == "" {
		return nil, errors.New("DATABASE_URL is required")
	}
	db, err := sql.Open("postgres", databaseURL)
	if err != nil {
		return nil, err
	}
	// Minimal, safe defaults; can be tuned later.
	db.SetMaxOpenConns(10)
	db.SetMaxIdleConns(5)
	db.SetConnMaxLifetime(30 * time.Minute)

	return db, nil
}

// HealthCheck pings the database to verify connectivity.
func HealthCheck(db *sql.DB) error {
	return db.Ping()
}
