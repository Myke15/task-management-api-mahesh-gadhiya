#!/bin/bash

echo "🛑 Shutting down services..."

# Stops containers and removes the internal network
# Use 'docker-compose down -v' if you also want to delete the database data
docker-compose down

echo "✅ Services stopped."