# How to Start the SmartEvent Project

Welcome to the SmartEvent platform! To run this project locally on your machine, you need to start both the backend server and the frontend server. 

Follow the instructions below to get everything up and running.

## 1. Start the Backend Server (Laravel)

Open a new terminal window, navigate to the `backend` folder, and start the Laravel development server.

```bash
# Navigate to the backend directory
cd backend

# Start the Laravel server
php artisan serve
```
*The backend API will typically start at `http://localhost:8000` or `http://127.0.0.1:8000`.*

## 2. Start the Frontend Server (React / Vite)

Open a **second, separate terminal window**, navigate to the `frontend` folder, and start the Vite development server.

```bash
# Navigate to the frontend directory
cd frontend

# Start the Vite development server
npm run dev
```
*The frontend application will typically start at `http://localhost:5173`. Open this link in your browser to view the application.*

---

### Important Notes:
- **Database:** Make sure your MySQL database server (e.g., XAMPP, WAMP, or standalone MySQL) is running before starting the backend, as the application requires database access.
- **Terminal Windows:** You must keep both terminal windows open and running simultaneously. If you close either terminal, that part of the application will stop working.
