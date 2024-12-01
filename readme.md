# Employee Voting System

A simple web application that allows employees to vote for their colleagues in different categories and view voting results.

## Features
- Submit votes for colleagues in various categories
- View category winners
- Track most active voters
- Prevent self-nomination
- Dark theme UI

## Prerequisites
- XAMPP (with Apache and MySQL)
- PHP 7.4 or higher
- Web browser

## Installation

1. **Install XAMPP**
   - Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Make sure Apache and MySQL services are installed

2. **Clone the Repository**
   ```bash
   cd C:/xampp/htdocs
   git clone https://github.com/yourusername/employee_voting.git
   ```

3. **Set Up the Database**
   - Start Apache and MySQL services from XAMPP Control Panel
   - Open your web browser and navigate to [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
   - Create a new database named `employee_voting`
   - Select the `employee_voting` database
   - Click on the "Import" tab
   - Choose the file `database.sql` from the project's root directory
   - Click "Go" to import the database structure and initial data

4. **Configure Database Connection**
   - Open `config/Database.php`
   - Update the database credentials if needed (default values should work with XAMPP):
     ```php
     private $host = "localhost";
     private $db_name = "employee_voting";
     private $username = "root";
     private $password = "";
     ```

## Running the Application

1. Make sure XAMPP is running with both Apache and MySQL services started

2. Open your web browser and navigate to:
   - Main voting page: [http://localhost/employee_voting](http://localhost/employee_voting)
   - Results page: [http://localhost/employee_voting/results.php](http://localhost/employee_voting/results.php)

## Project Structure