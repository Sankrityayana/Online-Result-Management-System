<?php
/**
 * Check if student is logged in
 */
function isStudentLoggedIn() {
    return isset($_SESSION['student_id']);
}

/**
 * Check if admin is logged in
 */
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

/**
 * Require student login
 */
function requireStudentLogin() {
    if (!isStudentLoggedIn()) {
        header('Location: student_login.php');
        exit;
    }
}

/**
 * Require admin login
 */
function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header('Location: admin_login.php');
        exit;
    }
}

/**
 * Get current student ID
 */
function getCurrentStudentId() {
    return $_SESSION['student_id'] ?? null;
}

/**
 * Get current admin ID
 */
function getCurrentAdminId() {
    return $_SESSION['admin_id'] ?? null;
}

/**
 * Sanitize output
 */
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Calculate grade based on percentage
 */
function calculateGrade($percentage) {
    if ($percentage >= 90) return 'A+';
    if ($percentage >= 80) return 'A';
    if ($percentage >= 70) return 'B+';
    if ($percentage >= 60) return 'B';
    if ($percentage >= 50) return 'C+';
    if ($percentage >= 40) return 'C';
    if ($percentage >= 33) return 'D';
    return 'F';
}

/**
 * Get grade color class
 */
function getGradeColor($grade) {
    $colors = [
        'A+' => 'grade-aplus',
        'A' => 'grade-a',
        'B+' => 'grade-bplus',
        'B' => 'grade-b',
        'C+' => 'grade-cplus',
        'C' => 'grade-c',
        'D' => 'grade-d',
        'F' => 'grade-f'
    ];
    return $colors[$grade] ?? 'grade-f';
}

/**
 * Check if passed
 */
function isPassed($marks, $passingMarks) {
    return $marks >= $passingMarks;
}

/**
 * Get overall result status
 */
function getOverallStatus($results, $subjects) {
    $subjectMap = [];
    foreach ($subjects as $subject) {
        $subjectMap[$subject['id']] = $subject['passing_marks'];
    }
    
    foreach ($results as $result) {
        $passingMarks = $subjectMap[$result['subject_id']] ?? 33;
        if (!isPassed($result['marks_obtained'], $passingMarks)) {
            return 'FAIL';
        }
    }
    return 'PASS';
}

/**
 * Format date
 */
function formatDate($date) {
    return date('d M, Y', strtotime($date));
}

/**
 * Get student details by ID
 */
function getStudentById($conn, $studentId) {
    $sql = "SELECT * FROM students WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $studentId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

/**
 * Get results by student ID and exam type
 */
function getStudentResults($conn, $studentId, $examType, $academicYear) {
    $sql = "SELECT r.*, s.subject_name, s.subject_code, s.max_marks, s.passing_marks
            FROM results r
            LEFT JOIN subjects s ON r.subject_id = s.id
            WHERE r.student_id = ? AND r.exam_type = ? AND r.academic_year = ?
            ORDER BY s.subject_name";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $studentId, $examType, $academicYear);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

/**
 * Get all exam types for student
 */
function getStudentExamTypes($conn, $studentId) {
    $sql = "SELECT DISTINCT exam_type, academic_year 
            FROM results 
            WHERE student_id = ? 
            ORDER BY academic_year DESC, exam_type";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $studentId);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

/**
 * Calculate percentage
 */
function calculatePercentage($totalMarks, $maxMarks) {
    if ($maxMarks == 0) return 0;
    return round(($totalMarks / $maxMarks) * 100, 2);
}

/**
 * Get class list
 */
function getClasses() {
    return ['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th', '9th', '10th', '11th', '12th'];
}

/**
 * Get section list
 */
function getSections() {
    return ['A', 'B', 'C', 'D', 'E'];
}

/**
 * Get exam types
 */
function getExamTypes() {
    return ['Mid Term', 'Final Term', 'Unit Test 1', 'Unit Test 2', 'Annual'];
}

/**
 * Get academic years
 */
function getAcademicYears() {
    $years = [];
    $currentYear = date('Y');
    for ($i = 0; $i < 5; $i++) {
        $year = $currentYear - $i;
        $years[] = $year . '-' . substr($year + 1, 2);
    }
    return $years;
}
?>
