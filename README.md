# Attendance Management System

This project is an **Attendance Management System** built using **PHP**, **MySQL**, and **Apache** on an EC2 instance. It allows users to manage student attendance, track roll numbers, and save them in a database. The system consists of a backend that interacts with MySQL, providing a seamless experience for managing attendance data.

<img width="430" alt="Screenshot 2024-12-23 124613" src="https://github.com/user-attachments/assets/bad47a28-8e39-40dd-bf22-29ef2babf747" />
<img width="430" alt="Screenshot 2024-12-23 124625" src="https://github.com/user-attachments/assets/21213f76-731b-48c1-8900-7c9ce8b32303" />
<img width="430" alt="Screenshot 2024-12-23 124634" src="https://github.com/user-attachments/assets/59bdbf4d-bf65-4fef-8bc1-91da2628ed5b" />
<img width="430" alt="Screenshot 2024-12-23 124648" src="https://github.com/user-attachments/assets/86c625e4-1ef4-4cc4-97e9-607416b2b245" />
<img width="430" alt="Screenshot 2024-12-23 124710" src="https://github.com/user-attachments/assets/b5b566fc-9c6f-45d0-9357-2e815f685a42" />
<img width="430" alt="{7F03D54A-FCD6-487B-807D-FAD35D24BC66}" src="https://github.com/user-attachments/assets/8f1bf2cd-3424-40e9-b3fd-c85ee749c519" />


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
  ***PLEASE NOTE THE ABOVE CODE IS NOT PRESENT IN MY DATABSE.PHP FILE INSIDE DATABASE FOLDER.IN THAT CODE JUST CHANGE THE CREDENTIALS OF THE DATABASE AND IT WILL WORK***

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

# Modify the tables in createtables to suit your needs and use the project accordingly.

Youtube I referred to :https://www.youtube.com/playlist?list=PLJ4-ETiGBrdOZ4kvbzNGidD26M24BLImM 

# Dockerizing this application using Docker 
**To run this application, you must have Docker Desktop or Docker Engine installed on your system** 
```
git clone https://github.com/ShashankSrivatsaRao/attendanceapp
cd attendanceapp
docker compose up --build
```
Now go to http://localhost and see the application use username:rcb and password :123 
 
After everything run docker compose down 

# PROCESS 
1. Analyze all the system dependencies , libraries ,files required to create a container image.
2. Write a Dockerfile to create a base image of the web server and install all the system dependencies.
   
   ```
   FROM php:8.2-apache

      # Install system dependencies
      RUN apt-get update && apt-get install -y \
          libpng-dev \
          libjpeg-dev \
          libonig-dev \
          libxml2-dev \
          zip \
          curl \
          unzip
      
      # Install PHP extensions
      RUN docker-php-ext-install mysqli pdo pdo_mysql mbstring exif pcntl bcmath gd
      
      # Enable Apache modules
      RUN a2enmod rewrite
      
      # Configure Apache
      RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
      RUN sed -i 's/DocumentRoot \/var\/www\/html/DocumentRoot \/var\/www\/html\/attendanceapp/g' /etc/apache2/sites-available/000-default.conf
      
      # Set working directory
      WORKDIR /var/www/html/attendanceapp
      
      # Copy application files
      COPY . .
      
      # Set permissions
      RUN chown -R www-data:www-data /var/www/html \
          && chmod -R 755 /var/www/html
      
      # Configure PHP
      RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
   ```
3. Now that you have a docker file write a docker-compose.yml file to create 2 services web and db
   ```
    version: '3.8'
         
         services:
           web:
             build:
               context: .
               dockerfile: Dockerfile
             ports:
               - "80:80"
             volumes:
               - .:/var/www/html/attendanceapp
             depends_on:
               - db
             networks:
               - attendance-network
         
           db:
             image: mysql:8.0
             command: --default-authentication-plugin=mysql_native_password
             restart: always
             ports:
               - "3307:3306"
             environment:
               - MYSQL_DATABASE=attendance
               - MYSQL_USER=attendanceuser
               - MYSQL_PASSWORD=Saswe@123
               - MYSQL_ROOT_PASSWORD=root
             volumes:
               - mysql_data:/var/lib/mysql
             networks:
               - attendance-network
         
         networks:
           attendance-network:
             driver: bridge
         
         volumes:
           mysql_data:
    ```
4.Now add a init.sql file to initialize the database and grant the user permissions.

  ```
     CREATE DATABASE IF NOT EXISTS attendance;
     GRANT ALL PRIVILEGES ON attendance.* TO 'attendanceuser'@'%';
     FLUSH PRIVILEGES;
  ```


5.Now Run ```docker compose up --build ```

# CHALLENGES FACED DURING DEPLOYING

WHEN I GOT TO http://localhost/   I SEE THIS SCREEN 
 
  <img width="430" alt="{9C2E992C-F40D-42A1-AF45-4061D2219019}" src="https://github.com/user-attachments/assets/3d83ef87-323a-4a21-8437-edc23b00a60e" />

  **THIS IS BEACAUSE WE HAVE NOT MENTIONED THE DIRECTORY INDEX IN THE WEB SERVER CONFIG FILE**

  **ALSO WE HAVE NOT CREATED THE TABLES INSIDE THE DB CONTAINER OF THE DATABASE**

## TROUBLESHOOTING

1. Open a new terminal and *cd into the project folder* . Inside this folder see the running containers.
RUN CoMMAND                  
```
docker compose exec db mysql -u attendanceuser -pSaswe@123 -e "SHOW DATABASES;"     
```
to show databases in the db container.

Output is this:
<img width="430" alt="Screenshot 2024-12-26 150506" src="https://github.com/user-attachments/assets/74034359-e89f-46c6-91e3-3aa5b9fd4488" />

If not check the command properly.

2. Go into the web container by
   
```docker compose exec web bash```

Go and execute the createtables.php file in apache2 var/www/html folder

<img width="430" alt="Screenshot 2024-12-26 150811" src="https://github.com/user-attachments/assets/7cf52d79-f86c-4285-96ae-bc0aa448a9ff" />

3. Now do not exit like I did. We have to add the directory index as login.php to the config file using the command inside the web container.

```
 vim /etc/apache2/sites-available/000-default.conf
```

IF vim is not there ``` apt install vim ```

Inside the config file below the DocumentRoot add the ```DirectoryIndex login.php``` line.

Save it and exit.

4.Restart the web service using

```
docker compose restart web
```

<img width="340" alt="Screenshot 2024-12-26 151138" src="https://github.com/user-attachments/assets/15516c64-9dfd-47f1-86c4-d1f7f14b0650" />

NOW VISIT http://localhost/ 



     

 


  



 
