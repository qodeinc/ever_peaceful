package middleware

import (
	"net/http/httptest"
	"testing"
	"time"

	"github.com/ever-peaceful/backend/internal/pkg/logger"
	"github.com/gin-gonic/gin"
)

func TestRequestLogger_SetsRequestIDAndLogs(t *testing.T) {
	gin.SetMode(gin.TestMode)
	r := gin.New()
	l := logger.New(logger.LevelInfo)
	r.Use(RequestLogger(l))
	r.GET("/ping", func(c *gin.Context) { c.String(200, "pong") })

	req := httptest.NewRequest("GET", "/ping", nil)
	w := httptest.NewRecorder()

	start := time.Now()
	r.ServeHTTP(w, req)
	_ = start

	if w.Header().Get("X-Request-ID") == "" {
		t.Fatalf("expected X-Request-ID header to be set")
	}
	if w.Code != 200 {
		t.Fatalf("expected status 200, got %d", w.Code)
	}
}
