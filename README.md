# Attendance System - Version 1.0

## Summary

This PHP script is designed to manage attendance and salary calculations. It includes the following features:

- **Record Attendance**: Record attendance for a specific date with options for in time and off time. The attendance types include present, paid leave, unpaid leave, short leave, half day, and absent.
- **View Monthly Attendance**: View attendance records on a monthly basis. Users can also print the records along with their profile details.
- **View Estimated Salary**: View details such as regular and double OT rates, OT hours, OT amounts, EPF deductions, loan deductions, net salary, work days, and attendance records with work hours for a chosen month.
- **Salary Settings**: Enter fields for basic salary, EPF rate, loan amount, monthly loan deduction, loan start date, and loan duration to calculate the final net salary for the months.
- **Profile Settings**: Fields to enter user first and last name, company, position, and employee number.
- **Admin Panel**: Accessible only to admin users. It includes system configurations such as work days per month, work days, regular OT days, double OT days, work start and end time, salary start and end date, and OT interval. The system uses these settings to calculate salary for each month's attendance.

## Installation Guide

To set up this PHP script, follow these steps:

1. **Upload Files**: Upload all the script files to your server.
2. **Update Configuration**: Open `config.php` and update the database credentials to match your server's configuration.
3. **Import Database**: Import the provided SQL file into your database to create the necessary tables and insert initial data.

### Detailed Steps

1. **Upload Files to Server**
   - Use an FTP client or your hosting control panel to upload all script files to the desired directory on your server.

2. **Update Database Configuration**
   - Open the `config.php` file in a text editor.
   - Update the following lines with your database credentials:
     ```php
     define('DB_HOST', 'your_database_host');
     define('DB_NAME', 'your_database_name');
     define('DB_USER', 'your_database_user');
     define('DB_PASS', 'your_database_password');
     ```

3. **Import SQL File**
   - Access your database management tool (such as phpMyAdmin).
   - Create a new database if it does not already exist.
   - Select the database and import the SQL file (`database.sql`) provided with the script.
   - This will create the necessary tables and insert initial data.

## Admin User Credentials

- **Username**: admin
- **Password**: admin

## License

This project is licensed under the MIT License - see the LICENSE.md file for details.

## Author

Matheesha Prathapa

## Contact

For further information or queries, you can reach out at: info.webnex@gmail.com
