<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireStudentLogin();

$student = getStudentById($_SESSION['student_id']);
$examTypes = getStudentExamTypes($_SESSION['student_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2>🎓 Student Portal</h2>
            </div>
            <ul class="sidebar-menu">
                <li class="active"><a href="student_dashboard.php">📊 Dashboard</a></li>
                <li><a href="../logout.php">🚪 Logout</a></li>
            </ul>
        </nav>
        
        <div class="main-content">
            <header class="content-header">
                <h1>Welcome, <?php echo escape($student['student_name']); ?>!</h1>
                <p>Roll Number: <?php echo escape($student['roll_number']); ?> | Class: <?php echo escape($student['class']); ?>-<?php echo escape($student['section']); ?></p>
            </header>
            
            <div class="content-body">
                <div class="info-cards">
                    <div class="info-card">
                        <div class="card-icon">🆔</div>
                        <div class="card-details">
                            <h3>Roll Number</h3>
                            <p><?php echo escape($student['roll_number']); ?></p>
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <div class="card-icon">📚</div>
                        <div class="card-details">
                            <h3>Class</h3>
                            <p><?php echo escape($student['class']); ?>-<?php echo escape($student['section']); ?></p>
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <div class="card-icon">📧</div>
                        <div class="card-details">
                            <h3>Email</h3>
                            <p><?php echo escape($student['email']); ?></p>
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <div class="card-icon">📱</div>
                        <div class="card-details">
                            <h3>Phone</h3>
                            <p><?php echo escape($student['phone']); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="section-header">
                    <h2>📝 Your Exam Results</h2>
                </div>
                
                <?php if (empty($examTypes)): ?>
                    <div class="alert alert-info">
                        <p>No exam results available yet. Results will appear here once published by the administration.</p>
                    </div>
                <?php else: ?>
                    <div class="results-grid">
                        <?php foreach ($examTypes as $exam): ?>
                            <div class="result-card">
                                <h3><?php echo escape($exam['exam_type']); ?></h3>
                                <p class="academic-year">Academic Year: <?php echo escape($exam['academic_year']); ?></p>
                                <a href="view_result.php?exam_type=<?php echo urlencode($exam['exam_type']); ?>&academic_year=<?php echo urlencode($exam['academic_year']); ?>" 
                                   class="btn btn-primary">View Details</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
