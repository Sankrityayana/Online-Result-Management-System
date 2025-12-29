<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireStudentLogin();

$exam_type = $_GET['exam_type'] ?? '';
$academic_year = $_GET['academic_year'] ?? '';

if (empty($exam_type) || empty($academic_year)) {
    header('Location: student_dashboard.php');
    exit;
}

$student = getStudentById($_SESSION['student_id']);
$results = getStudentResults($_SESSION['student_id'], $exam_type, $academic_year);

// Get subjects for student's class
$sql = "SELECT * FROM subjects WHERE class = ? ORDER BY subject_name";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $student['class']);
mysqli_stmt_execute($stmt);
$subjects_result = mysqli_stmt_get_result($stmt);
$subjects = [];
while ($row = mysqli_fetch_assoc($subjects_result)) {
    $subjects[$row['id']] = $row;
}

$overallStatus = getOverallStatus($results, $subjects);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Result - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2>🎓 Student Portal</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="student_dashboard.php">📊 Dashboard</a></li>
                <li><a href="../logout.php">🚪 Logout</a></li>
            </ul>
        </nav>
        
        <div class="main-content">
            <header class="content-header">
                <h1>📄 Exam Result</h1>
                <button onclick="window.print()" class="btn btn-secondary">🖨️ Print Result</button>
            </header>
            
            <div class="content-body">
                <div class="result-sheet">
                    <div class="result-header">
                        <h1><?php echo escape(SCHOOL_NAME); ?></h1>
                        <h2>Academic Result Sheet</h2>
                    </div>
                    
                    <div class="student-info-grid">
                        <div class="info-row">
                            <span class="label">Student Name:</span>
                            <span class="value"><?php echo escape($student['student_name']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Roll Number:</span>
                            <span class="value"><?php echo escape($student['roll_number']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Class:</span>
                            <span class="value"><?php echo escape($student['class']); ?>-<?php echo escape($student['section']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Exam Type:</span>
                            <span class="value"><?php echo escape($exam_type); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Academic Year:</span>
                            <span class="value"><?php echo escape($academic_year); ?></span>
                        </div>
                    </div>
                    
                    <?php if (empty($results)): ?>
                        <div class="alert alert-info">
                            <p>No results found for this exam.</p>
                        </div>
                    <?php else: ?>
                        <table class="result-table">
                            <thead>
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Max Marks</th>
                                    <th>Passing Marks</th>
                                    <th>Marks Obtained</th>
                                    <th>Grade</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalMarks = 0;
                                $totalMaxMarks = 0;
                                
                                foreach ($results as $result):
                                    $subject = $subjects[$result['subject_id']];
                                    $grade = calculateGrade(($result['marks_obtained'] / $subject['max_marks']) * 100);
                                    $passed = isPassed($result['marks_obtained'], $subject['passing_marks']);
                                    
                                    $totalMarks += $result['marks_obtained'];
                                    $totalMaxMarks += $subject['max_marks'];
                                ?>
                                    <tr>
                                        <td><?php echo escape($subject['subject_code']); ?></td>
                                        <td><?php echo escape($subject['subject_name']); ?></td>
                                        <td><?php echo escape($subject['max_marks']); ?></td>
                                        <td><?php echo escape($subject['passing_marks']); ?></td>
                                        <td><strong><?php echo escape($result['marks_obtained']); ?></strong></td>
                                        <td><span class="grade <?php echo getGradeColor($grade); ?>"><?php echo escape($grade); ?></span></td>
                                        <td><span class="status <?php echo $passed ? 'status-pass' : 'status-fail'; ?>">
                                            <?php echo $passed ? 'PASS' : 'FAIL'; ?>
                                        </span></td>
                                    </tr>
                                <?php endforeach; ?>
                                
                                <tr class="total-row">
                                    <td colspan="2"><strong>Total</strong></td>
                                    <td><strong><?php echo $totalMaxMarks; ?></strong></td>
                                    <td colspan="1"></td>
                                    <td><strong><?php echo $totalMarks; ?></strong></td>
                                    <td><strong><?php echo calculateGrade(calculatePercentage($totalMarks, $totalMaxMarks)); ?></strong></td>
                                    <td><strong><?php echo number_format(calculatePercentage($totalMarks, $totalMaxMarks), 2); ?>%</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="result-summary">
                            <div class="summary-item">
                                <span class="label">Total Marks:</span>
                                <span class="value"><?php echo $totalMarks; ?> / <?php echo $totalMaxMarks; ?></span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Percentage:</span>
                                <span class="value"><?php echo number_format(calculatePercentage($totalMarks, $totalMaxMarks), 2); ?>%</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Overall Grade:</span>
                                <span class="value grade <?php echo getGradeColor(calculateGrade(calculatePercentage($totalMarks, $totalMaxMarks))); ?>">
                                    <?php echo calculateGrade(calculatePercentage($totalMarks, $totalMaxMarks)); ?>
                                </span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Overall Status:</span>
                                <span class="value status <?php echo $overallStatus['passed'] ? 'status-pass' : 'status-fail'; ?>">
                                    <?php echo $overallStatus['passed'] ? 'PASSED' : 'FAILED'; ?>
                                </span>
                            </div>
                        </div>
                        
                        <?php if (!$overallStatus['passed']): ?>
                            <div class="alert alert-error">
                                <p><strong>Failed Subjects:</strong> <?php echo implode(', ', $overallStatus['failed_subjects']); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="grading-info">
                            <h3>Grading System</h3>
                            <div class="grading-grid">
                                <div class="grade-item"><span class="grade grade-a-plus">A+</span> 90-100%</div>
                                <div class="grade-item"><span class="grade grade-a">A</span> 80-89%</div>
                                <div class="grade-item"><span class="grade grade-b-plus">B+</span> 70-79%</div>
                                <div class="grade-item"><span class="grade grade-b">B</span> 60-69%</div>
                                <div class="grade-item"><span class="grade grade-c-plus">C+</span> 50-59%</div>
                                <div class="grade-item"><span class="grade grade-c">C</span> 40-49%</div>
                                <div class="grade-item"><span class="grade grade-d">D</span> 33-39%</div>
                                <div class="grade-item"><span class="grade grade-f">F</span> Below 33%</div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="result-footer">
                        <p>Generated on: <?php echo date('F d, Y'); ?></p>
                    </div>
                </div>
                
                <div class="action-buttons no-print">
                    <a href="student_dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
