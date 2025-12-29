<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_name = trim($_POST['student_name'] ?? '');
    $roll_number = trim($_POST['roll_number'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $class = $_POST['class'] ?? '';
    $section = $_POST['section'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    
    if (empty($student_name) || empty($roll_number) || empty($email) || empty($password) || 
        empty($class) || empty($section) || empty($dob) || empty($gender)) {
        $error = 'Please fill all required fields!';
    } else {
        // Check if roll number already exists
        $sql = "SELECT id FROM students WHERE roll_number = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $roll_number);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_get_result($stmt)->num_rows > 0) {
            $error = 'Roll number already exists!';
        } else {
            // Check if email already exists
            $sql = "SELECT id FROM students WHERE email = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            if (mysqli_stmt_get_result($stmt)->num_rows > 0) {
                $error = 'Email already exists!';
            } else {
                // Insert student
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $sql = "INSERT INTO students (student_name, roll_number, email, password, class, section, dob, gender, phone, address) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ssssssssss", $student_name, $roll_number, $email, $hashed_password, 
                                      $class, $section, $dob, $gender, $phone, $address);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success = 'Student added successfully!';
                    // Clear form
                    $_POST = [];
                } else {
                    $error = 'Failed to add student. Please try again.';
                }
            }
        }
    }
}

$classes = getClasses();
$sections = getSections();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2>👨‍💼 Admin Panel</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php">📊 Dashboard</a></li>
                <li class="active"><a href="add_student.php">➕ Add Student</a></li>
                <li><a href="manage_students.php">👥 Manage Students</a></li>
                <li><a href="add_subject.php">➕ Add Subject</a></li>
                <li><a href="manage_subjects.php">📚 Manage Subjects</a></li>
                <li><a href="add_result.php">➕ Add Result</a></li>
                <li><a href="manage_results.php">📝 Manage Results</a></li>
                <li><a href="../logout.php">🚪 Logout</a></li>
            </ul>
        </nav>
        
        <div class="main-content">
            <header class="content-header">
                <h1>➕ Add New Student</h1>
            </header>
            
            <div class="content-body">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo escape($success); ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo escape($error); ?></div>
                <?php endif; ?>
                
                <div class="form-container">
                    <form method="POST" action="add_student.php">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Student Name <span class="required">*</span></label>
                                <input type="text" name="student_name" required 
                                       value="<?php echo escape($_POST['student_name'] ?? ''); ?>"
                                       placeholder="Enter student name">
                            </div>
                            
                            <div class="form-group">
                                <label>Roll Number <span class="required">*</span></label>
                                <input type="text" name="roll_number" required 
                                       value="<?php echo escape($_POST['roll_number'] ?? ''); ?>"
                                       placeholder="Enter roll number">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Email <span class="required">*</span></label>
                                <input type="email" name="email" required 
                                       value="<?php echo escape($_POST['email'] ?? ''); ?>"
                                       placeholder="Enter email address">
                            </div>
                            
                            <div class="form-group">
                                <label>Password <span class="required">*</span></label>
                                <input type="password" name="password" required 
                                       placeholder="Enter password">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Class <span class="required">*</span></label>
                                <select name="class" required>
                                    <option value="">Select Class</option>
                                    <?php foreach ($classes as $class): ?>
                                        <option value="<?php echo escape($class); ?>" 
                                                <?php echo (($_POST['class'] ?? '') === $class) ? 'selected' : ''; ?>>
                                            <?php echo escape($class); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Section <span class="required">*</span></label>
                                <select name="section" required>
                                    <option value="">Select Section</option>
                                    <?php foreach ($sections as $section): ?>
                                        <option value="<?php echo escape($section); ?>" 
                                                <?php echo (($_POST['section'] ?? '') === $section) ? 'selected' : ''; ?>>
                                            <?php echo escape($section); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Date of Birth <span class="required">*</span></label>
                                <input type="date" name="dob" required 
                                       value="<?php echo escape($_POST['dob'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Gender <span class="required">*</span></label>
                                <select name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" <?php echo (($_POST['gender'] ?? '') === 'Male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo (($_POST['gender'] ?? '') === 'Female') ? 'selected' : ''; ?>>Female</option>
                                    <option value="Other" <?php echo (($_POST['gender'] ?? '') === 'Other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="phone" 
                                       value="<?php echo escape($_POST['phone'] ?? ''); ?>"
                                       placeholder="Enter phone number">
                            </div>
                            
                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="address" rows="3" placeholder="Enter address"><?php echo escape($_POST['address'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Add Student</button>
                            <a href="manage_students.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
