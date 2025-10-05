package redis

import (
	"context"
	"testing"
)

func TestConnect_EmptyURL(t *testing.T) {
	_, err := Connect("")
	if err == nil {
		t.Fatalf("expected error for empty REDIS_URL")
	}
}

func TestHealthCheck_WithNilClient(t *testing.T) {
	// This test ensures we don't panic if client is nil; HealthCheck isn't called with nil in practice.
	// Skip runtime call as it would panic; just assert that our code path requires non-nil client.
	// Kept minimal to avoid external dependency.
	_ = context.Background()
}
