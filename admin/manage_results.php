<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

// Get all results with student and subject details
$sql = "SELECT r.*, s.roll_number, s.student_name, s.class, s.section, 
               sub.subject_code, sub.subject_name, sub.max_marks, sub.passing_marks
        FROM results r
        JOIN students s ON r.student_id = s.id
        JOIN subjects sub ON r.subject_id = sub.id
        ORDER BY r.created_at DESC, s.roll_number";
$results = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Results - <?php echo SITE_NAME; ?></title>
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
                <li><a href="manage_subjects.php">📚 Manage Subjects</a></li>
                <li><a href="add_result.php">➕ Add Result</a></li>
                <li class="active"><a href="manage_results.php">📝 Manage Results</a></li>
                <li><a href="../logout.php">🚪 Logout</a></li>
            </ul>
        </nav>
        
        <div class="main-content">
            <header class="content-header">
                <h1>📝 Manage Results</h1>
                <a href="add_result.php" class="btn btn-primary">➕ Add New Result</a>
            </header>
            
            <div class="content-body">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Roll Number</th>
                                <th>Student Name</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Marks Obtained</th>
                                <th>Max Marks</th>
                                <th>Grade</th>
                                <th>Status</th>
                                <th>Exam Type</th>
                                <th>Academic Year</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($results) > 0): ?>
                                <?php while ($result = mysqli_fetch_assoc($results)): 
                                    $percentage = ($result['marks_obtained'] / $result['max_marks']) * 100;
                                    $grade = calculateGrade($percentage);
                                    $passed = isPassed($result['marks_obtained'], $result['passing_marks']);
                                ?>
                                    <tr>
                                        <td><strong><?php echo escape($result['roll_number']); ?></strong></td>
                                        <td><?php echo escape($result['student_name']); ?></td>
                                        <td><?php echo escape($result['class']); ?>-<?php echo escape($result['section']); ?></td>
                                        <td><?php echo escape($result['subject_code']); ?> - <?php echo escape($result['subject_name']); ?></td>
                                        <td><strong><?php echo escape($result['marks_obtained']); ?></strong></td>
                                        <td><?php echo escape($result['max_marks']); ?></td>
                                        <td><span class="grade <?php echo getGradeColor($grade); ?>"><?php echo escape($grade); ?></span></td>
                                        <td><span class="status <?php echo $passed ? 'status-pass' : 'status-fail'; ?>">
                                            <?php echo $passed ? 'PASS' : 'FAIL'; ?>
                                        </span></td>
                                        <td><?php echo escape($result['exam_type']); ?></td>
                                        <td><?php echo escape($result['academic_year']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10" style="text-align: center;">No results found</td>
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
