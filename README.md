# Attendance Management System

This project is an **Attendance Management System** built using **PHP**, **MySQL**, and **Apache** on an EC2 instance. It allows users to manage student attendance, track roll numbers, and save them in a database. The system consists of a backend that interacts with MySQL, providing a seamless experience for managing attendance data.

### Features:
- Create and manage student details.
- Record and track student attendance.
- Easy integration with MySQL for database operations.
- Provides a clear, user-friendly interface.

### Prerequisites:
- **Apache** Web Server
- **MySQL** Database
- **PHP** installed on EC2 instance

---

## Deployment Steps on EC2 Instance 

1. **Launch an EC2 instance** with **Ubuntu 20.04** and SSH into it.

2. **Update and install Apache, PHP, and MySQL**:
   ```bash
   sudo apt update
   sudo apt install apache2 php libapache2-mod-php mysql-server php-mysql
