<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_code = trim(strtoupper($_POST['subject_code'] ?? ''));
    $subject_name = trim($_POST['subject_name'] ?? '');
    $max_marks = intval($_POST['max_marks'] ?? 0);
    $passing_marks = intval($_POST['passing_marks'] ?? 0);
    $class = $_POST['class'] ?? '';
    
    if (empty($subject_code) || empty($subject_name) || $max_marks <= 0 || $passing_marks <= 0 || empty($class)) {
        $error = 'Please fill all required fields with valid values!';
    } elseif ($passing_marks >= $max_marks) {
        $error = 'Passing marks must be less than maximum marks!';
    } else {
        // Check if subject code already exists
        $sql = "SELECT id FROM subjects WHERE subject_code = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $subject_code);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_get_result($stmt)->num_rows > 0) {
            $error = 'Subject code already exists!';
        } else {
            // Insert subject
            $sql = "INSERT INTO subjects (subject_code, subject_name, max_marks, passing_marks, class) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssiis", $subject_code, $subject_name, $max_marks, $passing_marks, $class);
            
            if (mysqli_stmt_execute($stmt)) {
                $success = 'Subject added successfully!';
                $_POST = [];
            } else {
                $error = 'Failed to add subject. Please try again.';
            }
        }
    }
}

$classes = getClasses();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subject - <?php echo SITE_NAME; ?></title>
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
                <li><a href="add_student.php">➕ Add Student</a></li>
                <li><a href="manage_students.php">👥 Manage Students</a></li>
                <li class="active"><a href="add_subject.php">➕ Add Subject</a></li>
                <li><a href="manage_subjects.php">📚 Manage Subjects</a></li>
                <li><a href="add_result.php">➕ Add Result</a></li>
                <li><a href="manage_results.php">📝 Manage Results</a></li>
                <li><a href="../logout.php">🚪 Logout</a></li>
            </ul>
        </nav>
        
        <div class="main-content">
            <header class="content-header">
                <h1>➕ Add New Subject</h1>
            </header>
            
            <div class="content-body">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo escape($success); ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo escape($error); ?></div>
                <?php endif; ?>
                
                <div class="form-container">
                    <form method="POST" action="add_subject.php">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Subject Code <span class="required">*</span></label>
                                <input type="text" name="subject_code" required 
                                       value="<?php echo escape($_POST['subject_code'] ?? ''); ?>"
                                       placeholder="e.g., MATH10, SCI12">
                            </div>
                            
                            <div class="form-group">
                                <label>Subject Name <span class="required">*</span></label>
                                <input type="text" name="subject_name" required 
                                       value="<?php echo escape($_POST['subject_name'] ?? ''); ?>"
                                       placeholder="e.g., Mathematics, Science">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Maximum Marks <span class="required">*</span></label>
                                <input type="number" name="max_marks" required min="1" 
                                       value="<?php echo escape($_POST['max_marks'] ?? '100'); ?>"
                                       placeholder="Enter maximum marks">
                            </div>
                            
                            <div class="form-group">
                                <label>Passing Marks <span class="required">*</span></label>
                                <input type="number" name="passing_marks" required min="1" 
                                       value="<?php echo escape($_POST['passing_marks'] ?? '33'); ?>"
                                       placeholder="Enter passing marks">
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
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Add Subject</button>
                            <a href="manage_subjects.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
