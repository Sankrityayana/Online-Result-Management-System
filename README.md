# Online Result Management System

A comprehensive web-based result management system built with PHP and MySQL, designed for educational institutions to manage and publish student exam results efficiently.

## ✨ Features

### Student Portal
- 🔐 Secure student login using roll number
- 📊 View exam results by exam type and academic year
- 📈 Detailed result sheet with subject-wise marks, grades, and overall performance
- 🖨️ Print-friendly result format
- 📱 Responsive design for mobile and desktop

### Admin Panel
- 👨‍💼 Secure admin authentication
- ➕ Add new students with complete details
- 📚 Manage subjects with custom marking schemes
- 📝 Add and publish exam results
- 👥 View all students, subjects, and results
- 📊 Dashboard with statistics and recent activity

### Grading System
- **A+** - 90-100% (Excellent)
- **A** - 80-89% (Very Good)
- **B+** - 70-79% (Good)
- **B** - 60-69% (Above Average)
- **C+** - 50-59% (Average)
- **C** - 40-49% (Below Average)
- **D** - 33-39% (Pass)
- **F** - Below 33% (Fail)

## 🎨 Design

- **Theme**: Light multicolor design with soft pastel shades
- **Colors**: Soft blue, green, purple, pink, and orange on white background
- **Style**: Clean, modern interface without gradients
- **Responsive**: Works seamlessly on all devices

## 🛠️ Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL (Port 3307)
- **Frontend**: HTML5, CSS3
- **Server**: Apache (XAMPP)

## 📋 Prerequisites

- XAMPP (or any PHP development environment)
- MySQL Server
- Web Browser (Chrome, Firefox, Edge, etc.)

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/Sankrityayana/Online-Result-Management-System.git
cd Online-Result-Management-System
```

### 2. Setup Database

1. Start XAMPP Control Panel
2. Start **Apache** and **MySQL** services
3. Open phpMyAdmin: `http://localhost/phpmyadmin`
4. Create a new database named `result_management`
5. Import the database schema:
   - Click on the `result_management` database
   - Go to **Import** tab
   - Choose file: `database/database.sql`
   - Click **Go**

### 3. Configure Database Connection

The system is pre-configured for MySQL port **3307**. If your MySQL runs on a different port:

1. Open `includes/config.php`
2. Update the `DB_PORT` constant:
```php
define('DB_PORT', '3307'); // Change to your MySQL port
```

### 4. Move to Web Directory

Copy the project folder to your XAMPP `htdocs` directory:
```
C:\xampp\htdocs\Online-Result-Management-System\
```

### 5. Access the Application

Open your browser and navigate to:
```
http://localhost/Online-Result-Management-System
```

## 👤 Demo Credentials

### Admin Login
- **Username**: admin
- **Password**: password

### Student Login (Sample Accounts)
| Roll Number | Password | Class |
|-------------|----------|-------|
| 2024001 | password | 10th-A |
| 2024002 | password | 10th-A |
| 2024003 | password | 10th-B |
| 2024004 | password | 10th-B |
| 2024005 | password | 12th-A |
| 2024006 | password | 12th-A |
| 2024007 | password | 12th-B |
| 2024008 | password | 12th-B |

## 📁 Project Structure

```
Online-Result-Management-System/
│
├── admin/                      # Admin panel pages
│   ├── dashboard.php          # Admin dashboard
│   ├── add_student.php        # Add new student
│   ├── manage_students.php    # View all students
│   ├── add_subject.php        # Add new subject
│   ├── manage_subjects.php    # View all subjects
│   ├── add_result.php         # Add exam results
│   └── manage_results.php     # View all results
│
├── css/
│   └── style.css              # Light multicolor styling
│
├── database/
│   └── database.sql           # Database schema and sample data
│
├── includes/
│   ├── config.php             # Database configuration
│   └── functions.php          # Helper functions
│
├── index.php                   # Home page
├── student_login.php           # Student login page
├── admin_login.php             # Admin login page
├── student_dashboard.php       # Student dashboard
├── view_result.php             # View result details
├── logout.php                  # Logout handler
├── .gitignore                  # Git ignore file
├── LICENSE                     # MIT License
└── README.md                   # This file
```

## 🔧 Database Schema

### Students Table
- Personal information (name, roll number, email)
- Class and section details
- Secure password storage with bcrypt hashing

### Subjects Table
- Subject code and name
- Maximum and passing marks
- Class assignment

### Results Table
- Student-subject relationship
- Marks obtained
- Exam type and academic year
- Unique constraint to prevent duplicate entries

### Admins Table
- Admin authentication details
- Secure password hashing

## 🎯 Key Features Explained

### Automatic Grade Calculation
The system automatically calculates grades based on percentage:
- Percentage = (Marks Obtained / Maximum Marks) × 100
- Grade assigned based on percentage range

### Pass/Fail Determination
- Student must score at least the passing marks in **all subjects**
- Overall status: PASS (all subjects passed) or FAIL (any subject failed)

### Unique Result Entry
- Prevents duplicate result entries for the same student, subject, exam type, and academic year
- Database constraint ensures data integrity

## 🔒 Security Features

- ✅ Password hashing using bcrypt
- ✅ SQL injection prevention with prepared statements
- ✅ XSS protection with output escaping
- ✅ Session-based authentication
- ✅ Access control for student and admin areas

## 🐛 Troubleshooting

### MySQL Connection Error
**Problem**: "Connection failed: Can't connect to MySQL server"

**Solution**:
1. Verify MySQL is running in XAMPP
2. Check if MySQL port is 3307 in config.php
3. Verify database name is `result_management`
4. Check MySQL credentials (default: root with no password)

### Port 3307 Already in Use
**Problem**: MySQL won't start on port 3307

**Solution**:
1. Change MySQL port in XAMPP config
2. Update `DB_PORT` in `includes/config.php`
3. Restart Apache and MySQL

### Page Not Found (404)
**Problem**: Cannot access the application

**Solution**:
1. Ensure project is in `htdocs` directory
2. Check folder name matches the URL
3. Restart Apache in XAMPP

### Login Not Working
**Problem**: Valid credentials don't work

**Solution**:
1. Verify database import was successful
2. Check if `students` and `admins` tables have data
3. Clear browser cache and cookies
4. Try demo credentials listed above

## 📝 Usage Guide

### For Students
1. Go to homepage and click "Student Login"
2. Enter your roll number and password
3. View available exam results on the dashboard
4. Click "View Details" to see complete result sheet
5. Use "Print Result" button to save or print

### For Admins
1. Go to homepage and click "Admin Login"
2. Enter admin username and password
3. Use the sidebar to navigate:
   - **Dashboard**: View statistics and recent activity
   - **Add Student**: Register new students
   - **Manage Students**: View all students
   - **Add Subject**: Create new subjects
   - **Manage Subjects**: View all subjects
   - **Add Result**: Publish exam results
   - **Manage Results**: View all published results

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

**Sankrityayana**
- GitHub: [@Sankrityayana](https://github.com/Sankrityayana)

## 🙏 Acknowledgments

- PHP and MySQL documentation
- XAMPP development environment
- Modern CSS design principles
- Educational institutions for inspiration

## 📞 Support

If you encounter any issues or have questions:
1. Check the Troubleshooting section above
2. Review the demo credentials
3. Verify your XAMPP and MySQL configuration
4. Open an issue on GitHub

---

**Note**: This is a demo application. For production use, implement additional security measures, input validation, and error handling.

⭐ If you find this project helpful, please give it a star on GitHub!
