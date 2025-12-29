<?php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="home-container">
        <div class="home-content">
            <h1>📚 <?php echo SCHOOL_NAME; ?></h1>
            <h2>Online Result Management System</h2>
            <p>Access your academic results and performance reports online</p>
            
            <div class="home-cards">
                <div class="home-card">
                    <div class="card-icon">🎓</div>
                    <h3>Student Portal</h3>
                    <p>View your exam results, grades, and academic performance</p>
                    <a href="student_login.php" class="btn btn-primary">Student Login</a>
                </div>
                
                <div class="home-card">
                    <div class="card-icon">👨‍💼</div>
                    <h3>Admin Portal</h3>
                    <p>Manage students, subjects, and publish exam results</p>
                    <a href="admin_login.php" class="btn btn-secondary">Admin Login</a>
                </div>
            </div>
            
            <div class="home-footer">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SCHOOL_NAME; ?>. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
