package redis

import (
	"context"
	"errors"
	"time"

	redisv9 "github.com/redis/go-redis/v9"
)

// Connect creates a Redis client from REDIS_URL. Example: redis://localhost:6379
func Connect(redisURL string) (*redisv9.Client, error) {
	if redisURL == "" {
		return nil, errors.New("REDIS_URL is required")
	}
	opt, err := redisv9.ParseURL(redisURL)
	if err != nil {
		return nil, err
	}
	client := redisv9.NewClient(opt)
	return client, nil
}

// HealthCheck pings Redis.
func HealthCheck(ctx context.Context, client *redisv9.Client) error {
	ctx, cancel := context.WithTimeout(ctx, 2*time.Second)
	defer cancel()
	return client.Ping(ctx).Err()
}
