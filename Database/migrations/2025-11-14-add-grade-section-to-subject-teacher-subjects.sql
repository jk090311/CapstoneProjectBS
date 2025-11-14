-- Migration: Add grade_level and section_id columns to subject_teacher_subjects table
-- This allows each subject assignment to have its own grade level and section
-- Run this in your educguarddb (phpMyAdmin or mysql CLI)

-- Add the new columns
ALTER TABLE subject_teacher_subjects 
ADD COLUMN grade_level VARCHAR(10) DEFAULT '7' AFTER subject_id,
ADD COLUMN section_id INT NULL AFTER grade_level,
ADD INDEX idx_section (section_id);

-- Migrate existing data from subject_teachers table to populate the new columns
UPDATE subject_teacher_subjects sts
INNER JOIN subject_teachers st ON sts.subject_teacher_id = st.stID
SET 
    sts.grade_level = st.stGradelvl,
    sts.section_id = st.stSection;

-- Now that data is migrated, you can optionally remove stGradelvl and stSection from subject_teachers
-- (commented out for safety - only run if you're sure)
-- ALTER TABLE subject_teachers DROP COLUMN stGradelvl;
-- ALTER TABLE subject_teachers DROP COLUMN stSection;
