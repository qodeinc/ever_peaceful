package config

import (
	"os"
	"testing"
)

func TestLoad_SucceedsWithAllEnv(t *testing.T) {
	os.Setenv("DATABASE_URL", "postgres://dev:dev@localhost:5432/db")
	os.Setenv("PORT", "9090")
	os.Setenv("JWT_SECRET", "secret")
	os.Setenv("REDIS_URL", "redis://localhost:6379")
	t.Cleanup(func() {
		os.Unsetenv("DATABASE_URL")
		os.Unsetenv("PORT")
		os.Unsetenv("JWT_SECRET")
		os.Unsetenv("REDIS_URL")
	})

	cfg, err := Load()
	if err != nil {
		t.Fatalf("expected no error, got %v", err)
	}
	if cfg.Port != "9090" {
		t.Fatalf("expected port 9090, got %s", cfg.Port)
	}
}

func TestLoad_DefaultPortAndMissingRequireds(t *testing.T) {
	os.Unsetenv("DATABASE_URL")
	os.Unsetenv("PORT")
	os.Unsetenv("JWT_SECRET")
	os.Unsetenv("REDIS_URL")

	_, err := Load()
	if err == nil {
		t.Fatalf("expected error when required envs are missing")
	}

	// Provide required and check default port
	os.Setenv("DATABASE_URL", "postgres://dev:dev@localhost:5432/db")
	os.Setenv("JWT_SECRET", "secret")
	os.Setenv("REDIS_URL", "redis://localhost:6379")
	t.Cleanup(func() {
		os.Unsetenv("DATABASE_URL")
		os.Unsetenv("JWT_SECRET")
		os.Unsetenv("REDIS_URL")
	})

	cfg, err := Load()
	if err != nil {
		t.Fatalf("expected no error, got %v", err)
	}
	if cfg.Port != "8080" {
		t.Fatalf("expected default port 8080, got %s", cfg.Port)
	}
}
