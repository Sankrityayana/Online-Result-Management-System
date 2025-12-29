<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

// Get all subjects
$sql = "SELECT * FROM subjects ORDER BY class, subject_name";
$subjects = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subjects - <?php echo SITE_NAME; ?></title>
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
                <li><a href="add_subject.php">➕ Add Subject</a></li>
                <li class="active"><a href="manage_subjects.php">📚 Manage Subjects</a></li>
                <li><a href="add_result.php">➕ Add Result</a></li>
                <li><a href="manage_results.php">📝 Manage Results</a></li>
                <li><a href="../logout.php">🚪 Logout</a></li>
            </ul>
        </nav>
        
        <div class="main-content">
            <header class="content-header">
                <h1>📚 Manage Subjects</h1>
                <a href="add_subject.php" class="btn btn-primary">➕ Add New Subject</a>
            </header>
            
            <div class="content-body">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Class</th>
                                <th>Maximum Marks</th>
                                <th>Passing Marks</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($subjects) > 0): ?>
                                <?php while ($subject = mysqli_fetch_assoc($subjects)): ?>
                                    <tr>
                                        <td><strong><?php echo escape($subject['subject_code']); ?></strong></td>
                                        <td><?php echo escape($subject['subject_name']); ?></td>
                                        <td><?php echo escape($subject['class']); ?></td>
                                        <td><?php echo escape($subject['max_marks']); ?></td>
                                        <td><?php echo escape($subject['passing_marks']); ?></td>
                                        <td><?php echo formatDate($subject['created_at']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align: center;">No subjects found</td>
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
