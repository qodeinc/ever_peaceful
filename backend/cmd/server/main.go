package main

import (
	"fmt"
	"log"
	"net/http"

	"github.com/gin-gonic/gin"
)

func main() {
	r := gin.Default()

	r.GET("/health", func(c *gin.Context) {
		c.JSON(http.StatusOK, gin.H{
			"status":  "ok",
			"message": "Ever Peaceful Backend is running",
		})
	})

	fmt.Println("Starting Ever Peaceful Backend server...")
	log.Fatal(r.Run(":8080"))
}
