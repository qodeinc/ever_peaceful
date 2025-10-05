package middleware

import (
	"time"

	"github.com/gin-gonic/gin"
	"github.com/google/uuid"
	"github.com/ever-peaceful/backend/internal/pkg/logger"
)

// RequestLogger returns a Gin middleware that logs requests with a request ID.
func RequestLogger(l *logger.Logger) gin.HandlerFunc {
	return func(c *gin.Context) {
		start := time.Now()
		reqID := uuid.NewString()
		c.Writer.Header().Set("X-Request-ID", reqID)
		c.Set("request_id", reqID)

		c.Next()

		dur := time.Since(start)
		l.LogRequest(c.Request, dur, reqID, c.Writer.Status())
	}
}
