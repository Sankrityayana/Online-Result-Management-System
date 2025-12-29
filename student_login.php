<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roll_number = trim($_POST['roll_number'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($roll_number) || empty($password)) {
        $error = 'Please enter both roll number and password!';
    } else {
        $sql = "SELECT * FROM students WHERE roll_number = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $roll_number);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($student = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $student['password'])) {
                $_SESSION['student_id'] = $student['id'];
                $_SESSION['student_name'] = $student['student_name'];
                $_SESSION['roll_number'] = $student['roll_number'];
                header('Location: student_dashboard.php');
                exit;
            } else {
                $error = 'Invalid roll number or password!';
            }
        } else {
            $error = 'Invalid roll number or password!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h1>🎓 Student Login</h1>
            <p>Enter your credentials to view results</p>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo escape($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="student_login.php">
                <div class="form-group">
                    <label>Roll Number</label>
                    <input type="text" name="roll_number" required 
                           value="<?php echo escape($_POST['roll_number'] ?? ''); ?>"
                           placeholder="Enter your roll number">
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Enter your password">
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
            
            <div class="demo-credentials">
                <h4>Demo Student Credentials:</h4>
                <ul>
                    <li><strong>Roll Number:</strong> 2024001 | <strong>Password:</strong> password</li>
                    <li><strong>Roll Number:</strong> 2024002 | <strong>Password:</strong> password</li>
                </ul>
            </div>
            
            <div class="auth-links">
                <p><a href="index.php">← Back to Home</a></p>
            </div>
        </div>
    </div>
</body>
</html>
