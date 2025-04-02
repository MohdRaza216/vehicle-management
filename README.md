# Vehicle Management System

## 📌 Overview
The **Vehicle Management System** is a web-based application developed using **CodeIgniter 4.6.0, PHP, MySQL, jQuery, and AJAX**. It allows efficient management of **customers, vehicles, and payments** with real-time updates and seamless user interactions.

## 🚀 Features
- **Customer Management**: Add, edit, and delete customers using AJAX-based CRUD operations.
- **Vehicle Management**:
  - Add, edit, delete, and filter vehicles by status (**Available, Pending, Booked**).
  - Vehicles automatically update status based on payment completion.
- **Payments Module**:
  - Tracks total and pending amounts per customer.
  - Restricts payments for **Booked** vehicles.
  - Updates vehicle status dynamically.
- **AJAX-Based Interactions**: Ensures smooth user experience without full page reloads.
- **Server-Side Validation**: Uses CodeIgniter’s validation system for data integrity.
- **Toastr Notifications**: Provides real-time feedback for actions.

## 🛠️ Installation Guide
1. **Clone the repository**:
   ```sh
   git clone https://github.com/MohdRaza216/vehicle-management.git
   ```
2. **Move to project directory**:
   ```sh
   cd vehicle-management
   ```
3. **Set up the database**:
   - Create a database named **vehicle_management** in phpMyAdmin.
   - Import the provided SQL file (`database/vehicle_management.sql`).
4. **Configure Environment Variables**:
   - Rename `.env.example` to `.env` and update the database credentials:
     ```env
     database.default.hostname = localhost
     database.default.database = vehicle_management
     database.default.username = root
     database.default.password =
     database.default.DBDriver = MySQLi
     ```
5. **Start the server**:
   ```sh
   php spark serve
   ```
6. **Access the application**:
   Open your browser and visit: `http://localhost:8080`

## 📂 Folder Structure
```
vehicle-management/
│-- app/
│   ├── Controllers/  # Handles requests & business logic
│   ├── Models/       # Database interactions
│   ├── Views/        # HTML templates & UI files
│-- public/           # Frontend assets (CSS, JS, images)
│-- writable/         # Logs & cache
│-- .env              # Environment configuration
│-- composer.json     # PHP dependencies
│-- README.md         # Project documentation
```

## 🏆 Contributing
Feel free to submit issues and pull requests to improve this project! 🚀

## 📜 License
This project is **open-source** and available under the [MIT License](LICENSE).

