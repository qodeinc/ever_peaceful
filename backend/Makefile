.PHONY: migrate-up migrate-down build test

MIGRATIONS_PATH ?= file://migrations

migrate-up:
	@DATABASE_URL=$$DATABASE_URL MIGRATIONS_PATH=$(MIGRATIONS_PATH) go run ./cmd/migrate -action up

migrate-down:
	@DATABASE_URL=$$DATABASE_URL MIGRATIONS_PATH=$(MIGRATIONS_PATH) go run ./cmd/migrate -action down

build:
	go build ./...

test:
	go test ./...
