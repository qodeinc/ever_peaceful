package logger

import (
	"bytes"
	"net/http"
	"testing"
	"time"
)

func TestLogger_LogRequest(t *testing.T) {
	buf := &bytes.Buffer{}
	l := &Logger{level: LevelInfo, output: buf}
	req, _ := http.NewRequest("GET", "/health", nil)
	l.LogRequest(req, 12*time.Millisecond, "rid", 200)
	if buf.Len() == 0 {
		t.Fatalf("expected output, got none")
	}
}
