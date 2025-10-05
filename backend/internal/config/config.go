package config

// Config holds application configuration loaded from environment variables.
type Config struct {
	DatabaseURL string
	Port        string
	JWTSecret   string
	RedisURL    string
}
