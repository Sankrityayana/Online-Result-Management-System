<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

// Get statistics
$sql = "SELECT COUNT(*) as count FROM students";
$total_students = mysqli_fetch_assoc(mysqli_query($conn, $sql))['count'];

$sql = "SELECT COUNT(*) as count FROM subjects";
$total_subjects = mysqli_fetch_assoc(mysqli_query($conn, $sql))['count'];

$sql = "SELECT COUNT(DISTINCT CONCAT(student_id, '_', exam_type, '_', academic_year)) as count FROM results";
$total_results = mysqli_fetch_assoc(mysqli_query($conn, $sql))['count'];

// Get recent results
$sql = "SELECT r.*, s.student_name, s.roll_number, s.class, s.section, sub.subject_name 
        FROM results r 
        JOIN students s ON r.student_id = s.id 
        JOIN subjects sub ON r.subject_id = sub.id 
        ORDER BY r.created_at DESC LIMIT 10";
$recent_results = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2>👨‍💼 Admin Panel</h2>
            </div>
            <ul class="sidebar-menu">
                <li class="active"><a href="dashboard.php">📊 Dashboard</a></li>
                <li><a href="add_student.php">➕ Add Student</a></li>
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
                <h1>Welcome, <?php echo escape($_SESSION['admin_name']); ?>!</h1>
                <p>Manage the result management system from here</p>
            </header>
            
            <div class="content-body">
                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-icon">👥</div>
                        <div class="stat-details">
                            <h3><?php echo $total_students; ?></h3>
                            <p>Total Students</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">📚</div>
                        <div class="stat-details">
                            <h3><?php echo $total_subjects; ?></h3>
                            <p>Total Subjects</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">📝</div>
                        <div class="stat-details">
                            <h3><?php echo $total_results; ?></h3>
                            <p>Published Results</p>
                        </div>
                    </div>
                </div>
                
                <div class="section-header">
                    <h2>🔥 Recent Activity</h2>
                </div>
                
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Roll Number</th>
                                <th>Student Name</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Marks</th>
                                <th>Exam Type</th>
                                <th>Academic Year</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($recent_results) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($recent_results)): ?>
                                    <tr>
                                        <td><?php echo escape($row['roll_number']); ?></td>
                                        <td><?php echo escape($row['student_name']); ?></td>
                                        <td><?php echo escape($row['class']); ?>-<?php echo escape($row['section']); ?></td>
                                        <td><?php echo escape($row['subject_name']); ?></td>
                                        <td><strong><?php echo escape($row['marks_obtained']); ?></strong></td>
                                        <td><?php echo escape($row['exam_type']); ?></td>
                                        <td><?php echo escape($row['academic_year']); ?></td>
                                        <td><?php echo formatDate($row['created_at']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" style="text-align: center;">No recent activity</td>
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
