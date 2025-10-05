package config

import (
	"errors"
	"os"
)

// Load reads configuration from environment variables and validates required fields.
func Load() (Config, error) {
	cfg := Config{
		DatabaseURL: os.Getenv("DATABASE_URL"),
		Port:        os.Getenv("PORT"),
		JWTSecret:   os.Getenv("JWT_SECRET"),
		RedisURL:    os.Getenv("REDIS_URL"),
	}

	if cfg.DatabaseURL == "" {
		return Config{}, errors.New("DATABASE_URL is required")
	}
	if cfg.Port == "" {
		cfg.Port = "8080"
	}
	if cfg.JWTSecret == "" {
		return Config{}, errors.New("JWT_SECRET is required")
	}
	if cfg.RedisURL == "" {
		return Config{}, errors.New("REDIS_URL is required")
	}

	return cfg, nil
}
