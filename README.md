# 🏥 MedCare Hospital Management System

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![Status](https://img.shields.io/badge/Status-Completed-brightgreen?style=for-the-badge)

> A complete web-based Hospital Management System to efficiently manage patients, doctors, appointments, and billing with an interactive dashboard and real-time data updates.

## 📋 Table of Contents
- [Project Overview](#project-overview)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Database Schema](#database-schema)
- [ER Diagram](#er-diagram)
- [SQL Queries Implemented](#sql-queries-implemented)
- [Installation Guide](#installation-guide)
- [How to Use](#how-to-use)
- [Screenshots](#screenshots)
- [Future Enhancements](#future-enhancements)
- [Author](#author)
- [License](#license)

---

## 🎯 Project Overview

Managing a hospital manually involves handling numerous registers for patients, doctors, appointments, and bills. This often leads to data redundancy, errors, and time-consuming processes.

**MedCare Hospital Management System** solves this by providing a digital platform where hospital staff can:
- Register and manage patient records
- View doctor information and specializations
- Schedule and track appointments
- Generate automated bills
- Generate revenue reports

This project demonstrates core **Database Management System (DBMS)** concepts including ER modeling, normalization, constraints, joins, aggregations, subqueries, and transactions.

---

## ✨ Features

### 👥 Patient Management
| Feature | Description |
|---------|-------------|
| View Patients | Display all registered patients in a table |
| Add Patient | Register new patients with personal and medical details |
| Delete Patient | Remove patient records (with confirmation) |
| Search Patient | Real-time search by name, phone, or blood group |

### 👨‍⚕️ Doctor Management
| Feature | Description |
|---------|-------------|
| View Doctors | Display all doctors with specialization and fees |
| Doctor Details | Contact info, email, and consultation fee |

### 📅 Appointment Management
| Feature | Description |
|---------|-------------|
| Schedule Appointment | Book appointments by selecting patient and doctor |
| Update Status | Change status (Scheduled → Completed → Cancelled) |
| Delete Appointment | Cancel and remove appointments |
| Auto-Billing | Bill automatically generated when appointment is completed |
| View Schedule | See all appointments with date, time, and status |

### 💰 Billing Management
| Feature | Description |
|---------|-------------|
| View Bills | All billing records with amounts and status |
| Payment Tracking | Paid/Pending status indicators |
| Auto Bill Generation | Bills created automatically from doctor's consultation fee |

### 📊 Reports & Analytics
| Feature | Description |
|---------|-------------|
| Revenue by Doctor | See how much revenue each doctor generated |
| Monthly Statistics | Track appointments per month (total, completed, cancelled) |

### 🎨 User Interface
- Modern gradient design with glass morphism effects
- Responsive layout (works on mobile, tablet, and desktop)
- Interactive statistics cards with hover effects
- Color-coded status badges
- Toast notifications for user actions
- Modal forms for adding records
- Real-time search with keyboard shortcut (Ctrl+F)

---

## 🛠️ Technologies Used

| Layer | Technology | Purpose |
|-------|------------|---------|
| **Database** | MySQL 8.0 | Store all data (patients, doctors, appointments, bills) |
| **Backend** | PHP 8.2 | Business logic, database connections, API endpoints |
| **Frontend** | HTML5, CSS3 | Structure and styling of web pages |
| **Interactivity** | JavaScript (Vanilla) | Real-time search, form submissions, dynamic updates |
| **Server** | Apache (XAMPP) | Local development server |
| **Database GUI** | phpMyAdmin | Visual database management |
| **Version Control** | Git & GitHub | Code tracking and hosting |

---

## 🗄️ Database Schema

### Database Name: `hospital_management`

### Tables Structure (4 Tables in 3NF Normalization)

#### Table 1: Patient
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `patient_id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique patient identifier |
| `name` | VARCHAR(100) | NOT NULL | Patient's full name |
| `age` | INT | CHECK (age >= 0) | Patient's age |
| `gender` | CHAR(1) | CHECK IN ('M','F','O') | Gender |
| `phone` | VARCHAR(15) | UNIQUE | Contact number |
| `address` | TEXT | - | Residential address |
| `blood_group` | VARCHAR(3) | - | Blood type (O+, A+, B+, etc.) |
| `medical_history` | TEXT | - | Past illnesses or conditions |

#### Table 2: Doctor
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `doctor_id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique doctor identifier |
| `name` | VARCHAR(100) | NOT NULL | Doctor's full name |
| `specialization` | VARCHAR(50) | - | Medical specialty (Cardiology, etc.) |
| `phone` | VARCHAR(15) | - | Contact number |
| `email` | VARCHAR(100) | UNIQUE | Email address |
| `consultation_fee` | DECIMAL(10,2) | CHECK (fee >= 0) | Fee per appointment |

#### Table 3: Appointment
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `appt_id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique appointment identifier |
| `patient_id` | INT | FOREIGN KEY → Patient(patient_id) | Which patient |
| `doctor_id` | INT | FOREIGN KEY → Doctor(doctor_id) | Which doctor |
| `appt_date` | DATETIME | NOT NULL | Date and time of appointment |
| `status` | VARCHAR(20) | DEFAULT 'Scheduled' | Scheduled, Completed, or Cancelled |

#### Table 4: Billing
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `bill_id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique bill identifier |
| `patient_id` | INT | FOREIGN KEY → Patient(patient_id) | Which patient |
| `appt_id` | INT | FOREIGN KEY → Appointment(appt_id), UNIQUE | Which appointment |
| `amount` | DECIMAL(10,2) | - | Bill amount |
| `payment_status` | VARCHAR(20) | DEFAULT 'Pending' | Paid or Pending |
| `bill_date` | DATE | DEFAULT CURRENT_DATE | When bill was created |


### Relationships
- **Patient (1) → (M) Appointment**: One patient can have many appointments
- **Doctor (1) → (M) Appointment**: One doctor can have many appointments
- **Appointment (1) → (1) Billing**: One appointment generates exactly one bill

---
## 📊 SQL Queries Implemented

### 1. INNER JOIN - Display Appointments with Names
```sql
SELECT a.appt_id, p.name AS patient, d.name AS doctor, a.appt_date, a.status
FROM Appointment a
JOIN Patient p ON a.patient_id = p.patient_id
JOIN Doctor d ON a.doctor_id = d.doctor_id
ORDER BY a.appt_date DESC;

SELECT d.name, COUNT(a.appt_id) AS visits, COALESCE(SUM(b.amount), 0) AS revenue
FROM Doctor d
LEFT JOIN Appointment a ON d.doctor_id = a.doctor_id AND a.status = 'Completed'
LEFT JOIN Billing b ON a.appt_id = b.appt_id
GROUP BY d.doctor_id;

SELECT name, phone FROM Patient
WHERE patient_id IN (
    SELECT patient_id FROM Billing WHERE payment_status = 'Pending'
);

SELECT 
    DATE_FORMAT(appt_date, '%M %Y') AS month,
    COUNT(*) AS total,
    SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) AS completed,
    SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) AS cancelled
FROM Appointment
GROUP BY YEAR(appt_date), MONTH(appt_date)
ORDER BY YEAR(appt_date) DESC, MONTH(appt_date) DESC;

SELECT COUNT(*) FROM Appointment WHERE DATE(appt_date) = CURDATE();

💻 Installation Guide
Prerequisites
Software	Download Link	Purpose
XAMPP	Apache Friends	Local server (Apache + MySQL)
Browser	Chrome/Firefox/Edge	To run the application
Code Editor (Optional)	VS Code	To modify code
Step-by-Step Installation
Step 1: Install XAMPP
Download XAMPP from the official website

Run the installer (default settings are fine)

Launch XAMPP Control Panel

Step 2: Start Services
text
[XAMPP Control Panel]
☑️ Apache → Start (Port 80)
☑️ MySQL  → Start (Port 3307)
Step 3: Clone or Download Project
Option A: Using Git

bash
git clone https://github.com/royaldev006/hospital-management-system.git
Option B: Download ZIP

Go to the GitHub repository

Click "Code" → "Download ZIP"

Extract the ZIP file

Step 4: Move to htdocs Folder
bash
# Move the project folder to:
C:\xampp\htdocs\hospital_system\
Step 5: Import Database
Open browser → http://localhost/phpmyadmin

Click New → Create database: hospital_management

Click Import tab

Select the database.sql file (if included) or run the CREATE TABLE queries manually

Click Go

Step 6: Configure Database Connection
Open config.php and verify settings:

php
$servername = "localhost:3307";  // MySQL port
$username = "root";
$password = "";                   // Your MySQL password
$dbname = "hospital_management";
Step 7: Run the Application
Open browser and go to:

text
http://localhost/hospital_system/index.php

🎮 How to Use
Adding a New Patient
Click "New Patient" button

Fill in patient details (name, age, gender, phone, blood group, medical history)

Click "Register Patient"

Patient appears in the Patients table ✅

Booking an Appointment
Click "Book Appointment" button

Select a Patient from dropdown

Select a Doctor from dropdown

Choose Date & Time

Click "Book Appointment"

Appointment appears in the schedule ✅

Updating Appointment Status
Go to Appointments Schedule table

Click the dropdown in the Status column

Select: Scheduled → Completed → Cancelled

Bill is automatically generated when status becomes "Completed"

Searching Records
Type in the Search Bar at the top

Results filter in real-time

Shortcut: Press Ctrl+F to focus search

Generating Reports
Click "Reports" button

View:

Revenue by Doctor

Monthly Appointment Statistics

Deleting Records
Find the record you want to delete

Click the 🗑️ Delete button

Confirm deletion

🚀 Future Enhancements
Feature	Description	Priority
🔐 User Authentication	Role-based login (Admin, Doctor, Receptionist)	High
📧 Email Notifications	Send appointment reminders via email	High
📱 Patient Portal	Patients can book appointments online	Medium
💳 Payment Gateway	Online bill payment integration	Medium
📄 PDF Reports	Export reports as PDF documents	Medium
🏥 Room/Bed Management	Track bed availability and assignments	Low
💊 Prescription Module	Digital prescriptions linked to appointments	Low
⭐ Rating System	Patients can rate doctors	Low

📁 Project Structure
text
hospital_system/
│
├── config.php           # Database connection configuration
├── index.php            # Main dashboard (all CRUD operations)
├── style.css            # Modern responsive styling
├── script.js            # Interactive JavaScript functions
├── add_patient.php      # API to add new patients
├── add_appointment.php  # API to book appointments
├── update_status.php    # API to update appointment status
├── delete_record.php    # API to delete records
├── reports.php          # Analytics and reports page
├── get_stats.php        # API for live statistics refresh
└── README.md            # Project documentation (this file)

Record is removed ✅

🤝 Contributing
Contributions, issues, and feature requests are welcome!

Fork the repository

Create your feature branch (git checkout -b feature/amazing-feature)

Commit your changes (git commit -m 'Add some amazing feature')

Push to the branch (git push origin feature/amazing-feature)

Open a Pull Request

📧 Contact & Author
Name	royaldev006
GitHub	@royaldev006
Project Link	https://github.com/royaldev006/hospital-management-system
Course	DBMS Mini Project
Year	2026

📜 License
This project is for educational purposes as part of a DBMS Mini Project.

You are free to use, modify, and distribute this code for learning purposes.

🙏 Acknowledgments
MySQL for the powerful database system

PHP community for excellent documentation

XAMPP for easy local development environment

All open-source contributors

⭐ Show Your Support
If you found this project helpful, please give it a ⭐ on GitHub!

📊 Project Statistics
Metric	Value
Tables	4
Total Lines of Code	~1500
PHP Files	9
Database Queries	15+
Development Time	2 weeks
🔄 Last Updated
June 11, 2026


