package logger

import (
	"encoding/json"
	"io"
	"net/http"
	"os"
	"time"
)

// Level represents log verbosity.
type Level string

const (
	LevelDebug Level = "DEBUG"
	LevelInfo  Level = "INFO"
	LevelWarn  Level = "WARN"
	LevelError Level = "ERROR"
)

// Logger provides minimal structured logging.
type Logger struct {
	level  Level
	output io.Writer
}

// New returns a Logger writing JSON lines to stdout at the provided level.
func New(level Level) *Logger {
	return &Logger{level: level, output: os.Stdout}
}

type logEntry map[string]any

func (l *Logger) write(entry logEntry) {
	enc := json.NewEncoder(l.output)
	_ = enc.Encode(entry)
}

// Info logs a message with key-value fields.
func (l *Logger) Info(message string, fields ...any) {
	entry := logEntry{"level": string(LevelInfo), "ts": time.Now().UTC().Format(time.RFC3339Nano), "msg": message}
	for i := 0; i+1 < len(fields); i += 2 {
		k, ok := fields[i].(string)
		if !ok {
			continue
		}
		entry[k] = fields[i+1]
	}
	l.write(entry)
}

// LogRequest logs minimal HTTP request info.
func (l *Logger) LogRequest(req *http.Request, duration time.Duration, requestID string, status int) {
	l.Info("http_request",
		"method", req.Method,
		"path", req.URL.Path,
		"duration_ms", duration.Milliseconds(),
		"status", status,
		"request_id", requestID,
	)
}
