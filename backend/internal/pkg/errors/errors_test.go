package errors

import (
	"errors"
	"net/http"
	"testing"
)

func TestStatusFromCode(t *testing.T) {
	cases := []struct{
		code Code
		want int
	}{
		{CodeValidation, http.StatusBadRequest},
		{CodeNotFound, http.StatusNotFound},
		{CodeUnauthorized, http.StatusUnauthorized},
		{CodeForbidden, http.StatusForbidden},
		{CodeConflict, http.StatusConflict},
		{CodeInternal, http.StatusInternalServerError},
	}
	for _, c := range cases {
		if got := StatusFromCode(c.code); got != c.want {
			t.Fatalf("status for %s: got %d want %d", c.code, got, c.want)
		}
	}
}

func TestWrapAndIs(t *testing.T) {
	base := errors.New("root")
	w := Wrap(base, CodeNotFound, "missing")
	if !Is(w, CodeNotFound) {
		t.Fatalf("expected Is(CodeNotFound) to be true")
	}
	if errors.Unwrap(w) == nil {
		t.Fatalf("expected unwrap to return base error")
	}
}
