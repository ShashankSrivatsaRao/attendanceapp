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

3.**Clone this repository into the virtual machine using git clone command**.
  ```bash
  git clone "url"
  ```

4.Now check if the repo attendanceapp is there in the machine using ls command.Also check if or not the **apache** and the **mysql** server is running or not.
  ```bash
  ls
  sudo systemctl start apache2
  sudo systemctl status apache2
  sudo systemctl start mysql
  sudo systemctl status mysql
  ```

5.Create a database and set up user credentials:
  ```bash
  sudo mysql -u root
  CREATE DATABASE attendanceapp;
  CREATE USER 'username'@'localhost' IDENTIFIED BY 'password';
  GRANT ALL PRIVILEGES ON attendanceapp.* TO 'username'@'localhost';
  FLUSH PRIVILEGES;
  EXIT;
  ```

6.**Upload your project files to** /var/www/html/attendanceapp/ directory:
  ```bash
  sudo cp -r /path/to/your/project/* /var/www/html/attendanceapp/
  ```

7.**Now navigate to the**/var/www/html/attendanceapp file to see if all files have been copied.
  **Set Correct file peermissions**
  ```bash
  sudo chown -R www-data:www-data /var/www/html/attendanceapp
  sudo chmod -R 755 /var/www/html/attendanceapp
  ```

8.Now go into the database.php file in the attendanceapp/database foleder and set your database name , username and password as what you had set up in **Step5**
  Replace the values with your EC2 MySQL database credentials:
  ```bash
   <?php
   $servername = "localhost";
   $username = "attendanceuser";
   $password = "yourpassword";
   $dbname = "attendance";
   
   $conn = new mysqli($servername, $username, $password, $dbname);
   
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }
   ?>
  ```
  ***PLEASE NOTE THE ABOVE CODE IS NOT PRESENT IN MY DATABSE.PHP FILE INSIDE DATABSE FOLDER.IN THAT CODE JUST CHANGE THE CREDENTIALS OF THE DATABASE AND IT WILL WORK***

  **_9.MOST IMPORTANT STEP_** Now navigate into the attendanceapp/database/createtables.php folder and change the require once path to the path where the databse.php file is stored.This can be done by changing only the 4th line og the code like this .
   ```bash
            <?php
         
         // Include the database connection
         require_once __DIR__ . '/../database.php';
         
         // Your code for creating tables
         function clearTable($dbo, $tabName)
         {
             $c = "DELETE FROM :tabname";
             $s = $dbo->conn->prepare($c);
             try {
                 $s->execute([":tabname" => $tabName]);
             } catch (PDOException $oo) {
                 // Handle exception
             }
         }
         
         // Initialize Database connection
         $dbo = new Database();
         
         // SQL to create table
         $c = "CREATE TABLE student_details (
             id INT AUTO_INCREMENT PRIMARY KEY,
             roll_no VARCHAR(20) UNIQUE,
             name VARCHAR(50)
         )";
         
         // Execute query
         $s = $dbo->conn->prepare($c);
         try {
             $s->execute();
             echo("<br>student_details table created.");
         } catch (PDOException $o) {
             echo("<br>Error: student_details table not created.");
         }
         ?>
      ```

9.**Now run the following php command to create tables in the databse**
  ```bash
  php createtables.php
  ```
 ***If you encounter any error it is beacase you have not provided the correct path to your database.php folder or have not entered the credentials correctly into the databse.php folder*** 

10.Message saying all tables created is displayed.Now verify the tables are created by logging into the sql shell and checking the databse for tables using simple select query.
 ```bash
 sudo mysql -u root -p
 password:********
 mysql>use database attendance;
 mysql>show tables;
 mysql>select * from faculty_details;
 ```

11.Now Open the IP address of the EC2 instance in the browser and add <public-IPV4-address>/attendanceapp/login.php

12.Enter the login credentials username:rcb and password:123

# Use the project by changing all the tables in createtables as per your requirements and use it

Youtube I referred to :https://www.youtube.com/playlist?list=PLJ4-ETiGBrdOZ4kvbzNGidD26M24BLImM 
 

   

 


  



 
