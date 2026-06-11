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

### Entity Relationship Diagram

┌──────────────┐ ┌──────────────┐
│ Patient │ │ Doctor │
├──────────────┤ ├──────────────┤
│ patient_id★ │ │ doctor_id★ │
│ name │ │ name │
│ age │ │ specialization│
│ phone │ │ fee │
└──────┬───────┘ └──────┬───────┘
│ │
│ 1:M │ 1:M
▼ ▼
┌──────────────────────────────┐
│ Appointment │
├──────────────────────────────┤
│ appt_id★ │
│ patient_id (FK→Patient) │
│ doctor_id (FK→Doctor) │
│ appt_date │
│ status │
└──────────────┬───────────────┘
│
│ 1:1
▼
┌──────────────────────────────┐
│ Billing │
├──────────────────────────────┤
│ bill_id★ │
│ patient_id (FK→Patient) │
│ appt_id (FK→Appointment) │
│ amount │
│ payment_status │
│ bill_date │
└──────────────────────────────┘

★ = Primary Key | FK = Foreign Key


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
