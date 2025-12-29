<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password!';
    } else {
        $sql = "SELECT * FROM admins WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($admin = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['full_name'];
                $_SESSION['admin_username'] = $admin['username'];
                header('Location: admin/dashboard.php');
                exit;
            } else {
                $error = 'Invalid username or password!';
            }
        } else {
            $error = 'Invalid username or password!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h1>👨‍💼 Admin Login</h1>
            <p>Access the administration panel</p>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo escape($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="admin_login.php">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required 
                           value="<?php echo escape($_POST['username'] ?? ''); ?>"
                           placeholder="Enter admin username">
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Enter password">
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
            
            <div class="demo-credentials">
                <h4>Demo Admin Credentials:</h4>
                <ul>
                    <li><strong>Username:</strong> admin | <strong>Password:</strong> password</li>
                </ul>
            </div>
            
            <div class="auth-links">
                <p><a href="index.php">← Back to Home</a></p>
            </div>
        </div>
    </div>
</body>
</html>
