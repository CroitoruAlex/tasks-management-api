
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

# 6. Run tests
docker exec -it tasks-management-api-app-1 php artisan test --coverage

# 6. API available at
http://localhost:8000/api/
