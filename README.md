## 🔒 Authorization Highlights 

 - Admin users can manage all projects.
 - Managers can create and delete tasks, and update any assigned to them.
 - Assigned users can update their own tasks but not others’.
 - All authenticated users can view projects, tasks, and add comments.

## Coverage Summary
 - Overall coverage: 80%+

## 🛠️ Installation

Follow these steps to set up the project locally:

```bash
# 1. Clone the repository
git clone https://github.com/your-username/tasks-management-api.git

# 2. Enter the project directory
cd tasks-management-api

# 3. Copy the environment configuration
cp .env.example .env

# 4. Start Docker containers
docker-compose up -d

# 5. Run database migrations and seed initial data
docker exec -it tasks-management-api-app-1 php artisan migrate --seed

# 6. Run phpstan
docker exec -it tasks-management-api-app-1 vendor/bin/phpstan analyse app tests

# 7. Run tests
docker exec -it tasks-management-api-app-1 php artisan test --coverage

# 8. API available at
http://localhost:8000/api/
