✅ User registration & login (Sanctum)
✅ Role-based access (admin, manager, user)
✅ Projects CRUD (Admin only)
✅ Tasks CRUD (Manager or assigned user)
✅ Comments (Users only)
✅ Caching with invalidation
✅ Queued email notifications (Task assignments)
✅ Clean architecture with Services, Repositories & FormRequests
✅ Unit & Feature tests with 85%+ coverage


⚙️ Requirements
PHP >= 8.2
Composer >= 2.5
SQLite / MySQL

🛠️ Installation Steps
git clone https://github.com/yourusername/project-management-api.git
cd project-management-api

cp .env.example .env

docker-compose up -d

docker exec -it tasks-management-api-app-1 php artisan migrate --seed

API available at http://localhost:8000/api
