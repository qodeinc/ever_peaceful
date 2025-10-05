package errors

import (
	"errors"
	"net/http"
)

// Code represents a machine-readable error code.
type Code string

const (
	CodeValidation   Code = "validation_error"
	CodeNotFound     Code = "not_found"
	CodeUnauthorized Code = "unauthorized"
	CodeForbidden    Code = "forbidden"
	CodeConflict     Code = "conflict"
	CodeInternal     Code = "internal_error"
)

// AppError is a structured application error.
type AppError struct {
	Code    Code   `json:"code"`
	Message string `json:"message"`
	// Details is optional and should not leak sensitive data.
	Details string `json:"details,omitempty"`
	// cause holds the underlying error for wrapping/unwrapping.
	cause error
}

func (e *AppError) Error() string { return e.Message }
func (e *AppError) Unwrap() error { return e.cause }

func New(code Code, message string) *AppError {
	return &AppError{Code: code, Message: message}
}

func Wrap(err error, code Code, message string) *AppError {
	if err == nil {
		return &AppError{Code: code, Message: message}
	}
	return &AppError{Code: code, Message: message, cause: err}
}

func Validation(message string) *AppError   { return New(CodeValidation, message) }
func NotFound(message string) *AppError     { return New(CodeNotFound, message) }
func Unauthorized(message string) *AppError { return New(CodeUnauthorized, message) }
func Forbidden(message string) *AppError    { return New(CodeForbidden, message) }
func Conflict(message string) *AppError     { return New(CodeConflict, message) }
func Internal(message string) *AppError     { return New(CodeInternal, message) }

// StatusFromCode maps error codes to HTTP status codes.
func StatusFromCode(code Code) int {
	switch code {
	case CodeValidation:
		return http.StatusBadRequest
	case CodeNotFound:
		return http.StatusNotFound
	case CodeUnauthorized:
		return http.StatusUnauthorized
	case CodeForbidden:
		return http.StatusForbidden
	case CodeConflict:
		return http.StatusConflict
	default:
		return http.StatusInternalServerError
	}
}

// Is reports whether target code matches the provided error or its cause.
func Is(err error, code Code) bool {
	var ae *AppError
	if errors.As(err, &ae) {
		return ae.Code == code
	}
	return false
}
