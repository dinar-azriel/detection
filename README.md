<h1 align="center">🎓 Student Detection with YOLOv11 + FastAPI + PHP</h1>

<p align="center">
  🔍 Real-time detection of students in classrooms using multiple USB cameras<br>
  Powered by YOLOv8 + FastAPI backend and PHP dashboard frontend.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Python-3.10+-blue?style=flat&logo=python" />
  <img src="https://img.shields.io/badge/FastAPI-Backend-success?style=flat&logo=fastapi" />
  <img src="https://img.shields.io/badge/PHP-Frontend-blueviolet?style=flat&logo=php" />
  <img src="https://img.shields.io/badge/PostgreSQL-Database-336791?style=flat&logo=postgresql" />
  <img src="https://img.shields.io/badge/YOLOv8-Ultralytics-orange?style=flat&logo=ultralytics" />
</p>

---

## 📸 Features

- ✅ **Multi-camera USB input** (from backend server)
- ✅ **Real-time object detection (YOLOv8)**
- ✅ **Start/Stop cameras from web dashboard**
- ✅ **Capture image + count people + save to database**
- ✅ **PHP dashboard with user login**
- ✅ **Filter detection data by room**
- ✅ **Delete detection entries**
- ✅ **Responsive UI in blue-yellow theme**

---

## 🧠 Tech Stack

| Layer        | Technology                     |
|--------------|--------------------------------|
| Backend API  | FastAPI + Python + OpenCV      |
| AI Model     | YOLOv8 (`ultralytics`)         |
| Database     | PostgreSQL                     |
| Frontend     | PHP (native), HTML, CSS, JS    |
| Styling      | CSS custom (modern theme)      |

---

## 🗂️ Folder Structure

project/
│
├── backend/
│ ├── main.py
│ ├── auth.py
│ ├── detection.py
│ ├── camera_manager.py
│ ├── database.py
│ ├── models.py
│ └── init.sql
│
├── frontend/
│ ├── views/
│ │ ├── login.php
│ │ ├── register.php
│ │ ├── dashboard.php
│ │ └── cameras.php
│ ├── api/
│ │ ├── start_camera.php
│ │ ├── stop_camera.php
│ │ ├── capture.php
│ │ ├── fetch_detections.php
│ │ └── delete_detection.php
│ ├── assets/
│ │ ├── css/style.css
│ │ └── js/main.js
│ └── config.php
│
├── captures/
├── requirements.txt
└── README.md


---

## 🚀 How to Run

### 1. 📦 Backend (FastAPI)

```bash
pip install -r requirements.txt
uvicorn main:app --reload

```bash
php -S localhost:8080 -t frontend/


🗄️ Database Setup (PostgreSQL)
Buat database baru dengan nama "detection"

