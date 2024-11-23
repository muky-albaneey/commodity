# Variables
COMPOSER = composer
PHP = php
ARTISAN = $(PHP) artisan

# Colors for terminal output
GREEN = \033[0;32m
NC = \033[0m # No Color
RED = \033[0;31m
YELLOW = \033[1;33m

# Default target
.DEFAULT_GOAL := help

# Help command
help:
	@echo "$(GREEN)Available commands:$(NC)"
	@echo "$(YELLOW)make install$(NC)        - Install dependencies and set up project"
	@echo "$(YELLOW)make fresh$(NC)          - Fresh database migration with seeders"
	@echo "$(YELLOW)make dev$(NC)            - Start development server"
	@echo "$(YELLOW)make schedule$(NC)       - Run the scheduler"
	@echo "$(YELLOW)make queue$(NC)          - Run the queue worker"
	@echo "$(YELLOW)make test$(NC)           - Run tests"
	@echo "$(YELLOW)make cache$(NC)          - Clear all caches"
	@echo "$(YELLOW)make update$(NC)         - Update market data manually"
	@echo "$(YELLOW)make status$(NC)         - Check service status"
	@echo "$(YELLOW)make all$(NC)            - Run all essential services"

# Installation and setup
install:
	@echo "$(GREEN)Installing dependencies...$(NC)"
	$(COMPOSER) install
	@echo "$(GREEN)Creating .env file...$(NC)"
	cp .env.example .env
	@echo "$(GREEN)Generating application key...$(NC)"
	$(ARTISAN) key:generate
	@echo "$(GREEN)Creating storage link...$(NC)"
	$(ARTISAN) storage:link
	@echo "$(GREEN)Installation complete!$(NC)"

# Database commands
fresh:
	@echo "$(GREEN)Refreshing database...$(NC)"
	$(ARTISAN) migrate:fresh --seed
	@echo "$(GREEN)Database refresh complete!$(NC)"

# Development server
dev:
	@echo "$(GREEN)Starting development server...$(NC)"
	$(ARTISAN) serve

# Run scheduler
schedule:
	@echo "$(GREEN)Running scheduler...$(NC)"
	$(ARTISAN) schedule:work

# Run queue worker
queue:
	@echo "$(GREEN)Starting queue worker...$(NC)"
	$(ARTISAN) queue:work

# Run tests
test:
	@echo "$(GREEN)Running tests...$(NC)"
	$(PHP) vendor/bin/phpunit

# Clear cache
cache:
	@echo "$(GREEN)Clearing cache...$(NC)"
	$(ARTISAN) cache:clear
	$(ARTISAN) config:clear
	$(ARTISAN) route:clear
	$(ARTISAN) view:clear
	@echo "$(GREEN)Cache cleared!$(NC)"

# Update market data manually
update:
	@echo "$(GREEN)Updating market data...$(NC)"
	$(ARTISAN) market:update

# Check status
status:
	@echo "$(GREEN)Checking service status...$(NC)"
	@ps aux | grep artisan
	@echo "$(GREEN)Database status:$(NC)"
	$(ARTISAN) migrate:status

# Run all essential services (in separate terminals)
all:
	@echo "$(GREEN)Starting all services...$(NC)"
	@gnome-terminal --tab --title="Laravel Server" -- make dev
	@gnome-terminal --tab --title="Queue Worker" -- make queue
	@gnome-terminal --tab --title="Scheduler" -- make schedule
	@echo "$(GREEN)All services started!$(NC)"

# Production deployment
deploy:
	@echo "$(GREEN)Deploying to production...$(NC)"
	$(COMPOSER) install --no-dev --optimize-autoloader
	$(ARTISAN) config:cache
	$(ARTISAN) route:cache
	$(ARTISAN) view:cache
	$(ARTISAN) migrate --force
	@echo "$(GREEN)Deployment complete!$(NC)"

# Maintenance mode commands
maintenance-on:
	@echo "$(YELLOW)Enabling maintenance mode...$(NC)"
	$(ARTISAN) down

maintenance-off:
	@echo "$(GREEN)Disabling maintenance mode...$(NC)"
	$(ARTISAN) up

.PHONY: help install fresh dev schedule queue test cache update status all deploy maintenance-on maintenance-off 