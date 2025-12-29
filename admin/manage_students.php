<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

// Get all students
$sql = "SELECT * FROM students ORDER BY roll_number";
$students = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - <?php echo SITE_NAME; ?></title>
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
                <li class="active"><a href="manage_students.php">👥 Manage Students</a></li>
                <li><a href="add_subject.php">➕ Add Subject</a></li>
                <li><a href="manage_subjects.php">📚 Manage Subjects</a></li>
                <li><a href="add_result.php">➕ Add Result</a></li>
                <li><a href="manage_results.php">📝 Manage Results</a></li>
                <li><a href="../logout.php">🚪 Logout</a></li>
            </ul>
        </nav>
        
        <div class="main-content">
            <header class="content-header">
                <h1>👥 Manage Students</h1>
                <a href="add_student.php" class="btn btn-primary">➕ Add New Student</a>
            </header>
            
            <div class="content-body">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Roll Number</th>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>DOB</th>
                                <th>Gender</th>
                                <th>Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($students) > 0): ?>
                                <?php while ($student = mysqli_fetch_assoc($students)): ?>
                                    <tr>
                                        <td><strong><?php echo escape($student['roll_number']); ?></strong></td>
                                        <td><?php echo escape($student['student_name']); ?></td>
                                        <td><?php echo escape($student['email']); ?></td>
                                        <td><?php echo escape($student['class']); ?></td>
                                        <td><?php echo escape($student['section']); ?></td>
                                        <td><?php echo formatDate($student['dob']); ?></td>
                                        <td><?php echo escape($student['gender']); ?></td>
                                        <td><?php echo escape($student['phone']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" style="text-align: center;">No students found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
