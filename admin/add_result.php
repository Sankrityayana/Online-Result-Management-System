<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

$success = '';
$error = '';

// Get all students
$students_sql = "SELECT id, roll_number, student_name, class FROM students ORDER BY roll_number";
$students = mysqli_query($conn, $students_sql);

// Get subjects based on selected class
$subjects = [];
if (isset($_POST['class']) && !empty($_POST['class'])) {
    $class = $_POST['class'];
    $subjects_sql = "SELECT * FROM subjects WHERE class = ? ORDER BY subject_name";
    $stmt = mysqli_prepare($conn, $subjects_sql);
    mysqli_stmt_bind_param($stmt, "s", $class);
    mysqli_stmt_execute($stmt);
    $subjects = mysqli_stmt_get_result($stmt);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_result'])) {
    $student_id = intval($_POST['student_id'] ?? 0);
    $subject_id = intval($_POST['subject_id'] ?? 0);
    $marks_obtained = intval($_POST['marks_obtained'] ?? 0);
    $exam_type = $_POST['exam_type'] ?? '';
    $academic_year = $_POST['academic_year'] ?? '';
    $remarks = trim($_POST['remarks'] ?? '');
    
    if ($student_id <= 0 || $subject_id <= 0 || $marks_obtained < 0 || empty($exam_type) || empty($academic_year)) {
        $error = 'Please fill all required fields!';
    } else {
        // Get subject max marks
        $sql = "SELECT max_marks FROM subjects WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $subject_id);
        mysqli_stmt_execute($stmt);
        $subject = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        
        if ($marks_obtained > $subject['max_marks']) {
            $error = 'Marks obtained cannot exceed maximum marks (' . $subject['max_marks'] . ')!';
        } else {
            // Check if result already exists
            $sql = "SELECT id FROM results WHERE student_id = ? AND subject_id = ? AND exam_type = ? AND academic_year = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "iiss", $student_id, $subject_id, $exam_type, $academic_year);
            mysqli_stmt_execute($stmt);
            
            if (mysqli_stmt_get_result($stmt)->num_rows > 0) {
                $error = 'Result already exists for this student, subject, exam type, and academic year!';
            } else {
                // Insert result
                $sql = "INSERT INTO results (student_id, subject_id, marks_obtained, exam_type, academic_year, remarks) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "iiisss", $student_id, $subject_id, $marks_obtained, $exam_type, $academic_year, $remarks);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success = 'Result added successfully!';
                } else {
                    $error = 'Failed to add result. Please try again.';
                }
            }
        }
    }
}

$exam_types = getExamTypes();
$academic_years = getAcademicYears();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Result - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <script>
        function loadSubjects() {
            const studentId = document.querySelector('select[name="student_id"]').value;
            if (!studentId) return;
            
            // Get student's class
            const studentSelect = document.querySelector('select[name="student_id"]');
            const selectedOption = studentSelect.options[studentSelect.selectedIndex];
            const studentClass = selectedOption.getAttribute('data-class');
            
            // Set class hidden field and trigger form submission to reload subjects
            document.querySelector('input[name="class"]').value = studentClass;
            document.querySelector('form').submit();
        }
    </script>
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
                <li class="active"><a href="add_result.php">➕ Add Result</a></li>
                <li><a href="manage_results.php">📝 Manage Results</a></li>
                <li><a href="../logout.php">🚪 Logout</a></li>
            </ul>
        </nav>
        
        <div class="main-content">
            <header class="content-header">
                <h1>➕ Add New Result</h1>
            </header>
            
            <div class="content-body">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo escape($success); ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo escape($error); ?></div>
                <?php endif; ?>
                
                <div class="form-container">
                    <form method="POST" action="add_result.php">
                        <input type="hidden" name="class" value="<?php echo escape($_POST['class'] ?? ''); ?>">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Student <span class="required">*</span></label>
                                <select name="student_id" required onchange="loadSubjects()">
                                    <option value="">Select Student</option>
                                    <?php mysqli_data_seek($students, 0); while ($student = mysqli_fetch_assoc($students)): ?>
                                        <option value="<?php echo $student['id']; ?>" 
                                                data-class="<?php echo escape($student['class']); ?>"
                                                <?php echo (($_POST['student_id'] ?? 0) == $student['id']) ? 'selected' : ''; ?>>
                                            <?php echo escape($student['roll_number']); ?> - <?php echo escape($student['student_name']); ?> (<?php echo escape($student['class']); ?>)
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Subject <span class="required">*</span></label>
                                <select name="subject_id" required>
                                    <option value="">Select Subject</option>
                                    <?php if ($subjects && mysqli_num_rows($subjects) > 0): ?>
                                        <?php while ($subject = mysqli_fetch_assoc($subjects)): ?>
                                            <option value="<?php echo $subject['id']; ?>"
                                                    <?php echo (($_POST['subject_id'] ?? 0) == $subject['id']) ? 'selected' : ''; ?>>
                                                <?php echo escape($subject['subject_code']); ?> - <?php echo escape($subject['subject_name']); ?> (Max: <?php echo $subject['max_marks']; ?>)
                                            </option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Marks Obtained <span class="required">*</span></label>
                                <input type="number" name="marks_obtained" required min="0" 
                                       value="<?php echo escape($_POST['marks_obtained'] ?? ''); ?>"
                                       placeholder="Enter marks obtained">
                            </div>
                            
                            <div class="form-group">
                                <label>Exam Type <span class="required">*</span></label>
                                <select name="exam_type" required>
                                    <option value="">Select Exam Type</option>
                                    <?php foreach ($exam_types as $type): ?>
                                        <option value="<?php echo escape($type); ?>"
                                                <?php echo (($_POST['exam_type'] ?? '') === $type) ? 'selected' : ''; ?>>
                                            <?php echo escape($type); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Academic Year <span class="required">*</span></label>
                                <select name="academic_year" required>
                                    <option value="">Select Academic Year</option>
                                    <?php foreach ($academic_years as $year): ?>
                                        <option value="<?php echo escape($year); ?>"
                                                <?php echo (($_POST['academic_year'] ?? '') === $year) ? 'selected' : ''; ?>>
                                            <?php echo escape($year); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea name="remarks" rows="3" placeholder="Enter remarks (optional)"><?php echo escape($_POST['remarks'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" name="submit_result" class="btn btn-primary">Add Result</button>
                            <a href="manage_results.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
