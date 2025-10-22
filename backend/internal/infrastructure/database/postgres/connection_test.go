package postgres

import (
	"testing"
)

func TestConnect_InvalidURLReturnsError(t *testing.T) {
	_, err := Connect("")
	if err == nil {
		t.Fatalf("expected error with empty DSN")
	}
}
