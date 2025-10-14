-- Migration: create subject_teacher_subjects relation table and migrate single stSubject IDs
-- Run this in your educguarddb (phpMyAdmin or mysql CLI)

CREATE TABLE IF NOT EXISTS subject_teacher_subjects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  subject_teacher_id INT NOT NULL,
  subject_id INT NOT NULL,
  UNIQUE KEY uk_teacher_subject (subject_teacher_id, subject_id),
  INDEX idx_subject (subject_id),
  INDEX idx_teacher (subject_teacher_id),
  CONSTRAINT fk_sts_teacher FOREIGN KEY (subject_teacher_id) REFERENCES subject_teachers(stID) ON DELETE CASCADE,
  CONSTRAINT fk_sts_subject FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Migrate existing numeric stSubject values into the relation table
INSERT IGNORE INTO subject_teacher_subjects (subject_teacher_id, subject_id)
SELECT stID, CAST(stSubject AS UNSIGNED)
FROM subject_teachers
WHERE stSubject IS NOT NULL AND stSubject <> '' AND stSubject REGEXP '^[0-9]+$';
