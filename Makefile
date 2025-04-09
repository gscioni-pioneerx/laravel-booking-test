# Makefile for Laravel Docker-based project

# Define the container name (update this to your actual container name)
APP_CONTAINER_NAME = laravel-app
DB_CONTAINER_NAME = laravel-db

# Up Docker Compose
up:
	docker compose up -d --build

# Destroy Docker Containers
down:
	docker compose down --volumes --remove-orphans

# Execute a script inside the Docker app container
exec-script:
	docker exec -it $(APP_CONTAINER_NAME) /bin/bash -c "$(script)"

# Run migrations inside the Docker app container
migrate:
	docker exec -it $(APP_CONTAINER_NAME) php artisan migrate

# Execute seeding inside the Docker app container
seed:
	docker exec -it $(APP_CONTAINER_NAME) php artisan db:seed --class=UserSeeder

# Run tests inside the Docker app container
test:
	docker exec -it $(APP_CONTAINER_NAME) php artisan test
