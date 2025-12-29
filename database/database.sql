-- Create database
CREATE DATABASE IF NOT EXISTS result_management;
USE result_management;

-- Students table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_number VARCHAR(20) UNIQUE NOT NULL,
    student_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    class VARCHAR(50) NOT NULL,
    section VARCHAR(10) NOT NULL,
    dob DATE NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    phone VARCHAR(15),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Subjects table
CREATE TABLE IF NOT EXISTS subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_code VARCHAR(20) UNIQUE NOT NULL,
    subject_name VARCHAR(100) NOT NULL,
    max_marks INT NOT NULL,
    passing_marks INT NOT NULL,
    class VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Results table
CREATE TABLE IF NOT EXISTS results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    marks_obtained INT NOT NULL,
    exam_type VARCHAR(50) NOT NULL,
    academic_year VARCHAR(20) NOT NULL,
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    UNIQUE KEY unique_result (student_id, subject_id, exam_type, academic_year)
);

-- Admin table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample admin
INSERT INTO admins (username, email, password, full_name) VALUES
('admin', 'admin@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator');

-- Insert sample students
INSERT INTO students (roll_number, student_name, email, password, class, section, dob, gender, phone, address) VALUES
('2024001', 'Rahul Kumar', 'rahul@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '10th', 'A', '2008-05-15', 'Male', '9876543210', '123 Main Street, Delhi'),
('2024002', 'Priya Sharma', 'priya@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '10th', 'A', '2008-08-22', 'Female', '9876543211', '456 Park Avenue, Mumbai'),
('2024003', 'Amit Patel', 'amit@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '10th', 'B', '2008-03-10', 'Male', '9876543212', '789 Lake Road, Bangalore'),
('2024004', 'Sneha Reddy', 'sneha@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '10th', 'B', '2008-11-05', 'Female', '9876543213', '321 Hill View, Hyderabad'),
('2024005', 'Vikram Singh', 'vikram@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '12th', 'A', '2006-07-18', 'Male', '9876543214', '654 River Side, Pune'),
('2024006', 'Anita Verma', 'anita@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '12th', 'A', '2006-09-25', 'Female', '9876543215', '987 Garden Lane, Chennai'),
('2024007', 'Rajesh Gupta', 'rajesh@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '12th', 'B', '2006-04-12', 'Male', '9876543216', '147 Market Street, Kolkata'),
('2024008', 'Pooja Jain', 'pooja@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '12th', 'B', '2006-12-30', 'Female', '9876543217', '258 Temple Road, Jaipur');

-- Insert sample subjects
INSERT INTO subjects (subject_code, subject_name, max_marks, passing_marks, class) VALUES
('MATH10', 'Mathematics', 100, 33, '10th'),
('SCI10', 'Science', 100, 33, '10th'),
('ENG10', 'English', 100, 33, '10th'),
('SS10', 'Social Science', 100, 33, '10th'),
('HIN10', 'Hindi', 100, 33, '10th'),
('MATH12', 'Mathematics', 100, 33, '12th'),
('PHY12', 'Physics', 100, 33, '12th'),
('CHEM12', 'Chemistry', 100, 33, '12th'),
('BIO12', 'Biology', 100, 33, '12th'),
('ENG12', 'English', 100, 33, '12th'),
('CS12', 'Computer Science', 100, 33, '12th');

-- Insert sample results
INSERT INTO results (student_id, subject_id, marks_obtained, exam_type, academic_year, remarks) VALUES
-- Rahul Kumar (10th A) - Mid Term 2024
(1, 1, 85, 'Mid Term', '2024-25', 'Excellent performance'),
(1, 2, 78, 'Mid Term', '2024-25', 'Good'),
(1, 3, 92, 'Mid Term', '2024-25', 'Outstanding'),
(1, 4, 75, 'Mid Term', '2024-25', 'Good'),
(1, 5, 88, 'Mid Term', '2024-25', 'Very Good'),

-- Priya Sharma (10th A) - Mid Term 2024
(2, 1, 95, 'Mid Term', '2024-25', 'Excellent'),
(2, 2, 89, 'Mid Term', '2024-25', 'Very Good'),
(2, 3, 97, 'Mid Term', '2024-25', 'Outstanding'),
(2, 4, 82, 'Mid Term', '2024-25', 'Good'),
(2, 5, 91, 'Mid Term', '2024-25', 'Excellent'),

-- Amit Patel (10th B) - Mid Term 2024
(3, 1, 72, 'Mid Term', '2024-25', 'Average'),
(3, 2, 68, 'Mid Term', '2024-25', 'Average'),
(3, 3, 80, 'Mid Term', '2024-25', 'Good'),
(3, 4, 65, 'Mid Term', '2024-25', 'Average'),
(3, 5, 75, 'Mid Term', '2024-25', 'Good'),

-- Sneha Reddy (10th B) - Mid Term 2024
(4, 1, 88, 'Mid Term', '2024-25', 'Very Good'),
(4, 2, 85, 'Mid Term', '2024-25', 'Very Good'),
(4, 3, 90, 'Mid Term', '2024-25', 'Excellent'),
(4, 4, 78, 'Mid Term', '2024-25', 'Good'),
(4, 5, 86, 'Mid Term', '2024-25', 'Very Good'),

-- Vikram Singh (12th A) - Mid Term 2024
(5, 6, 76, 'Mid Term', '2024-25', 'Good'),
(5, 7, 82, 'Mid Term', '2024-25', 'Good'),
(5, 8, 79, 'Mid Term', '2024-25', 'Good'),
(5, 10, 85, 'Mid Term', '2024-25', 'Very Good'),
(5, 11, 90, 'Mid Term', '2024-25', 'Excellent'),

-- Anita Verma (12th A) - Mid Term 2024
(6, 6, 92, 'Mid Term', '2024-25', 'Excellent'),
(6, 7, 88, 'Mid Term', '2024-25', 'Very Good'),
(6, 8, 95, 'Mid Term', '2024-25', 'Outstanding'),
(6, 10, 91, 'Mid Term', '2024-25', 'Excellent'),
(6, 11, 87, 'Mid Term', '2024-25', 'Very Good');
