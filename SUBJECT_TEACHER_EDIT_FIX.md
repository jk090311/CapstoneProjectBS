# Subject Teacher Edit Modal - Multiple Sections Per Subject Fix

## Overview
This fix allows subject teachers to have different sections for each subject they teach. Previously, the system only allowed one grade level and one section per teacher, even when they taught multiple subjects.

## Database Changes

### Migration File
Location: `Database/migrations/2025-11-14-add-grade-section-to-subject-teacher-subjects.sql`

**IMPORTANT: Run this migration first before testing the edit functionality!**

```sql
ALTER TABLE subject_teacher_subjects 
ADD COLUMN grade_level VARCHAR(10) DEFAULT '7' AFTER subject_id,
ADD COLUMN section_id INT NULL AFTER grade_level,
ADD INDEX idx_section (section_id);
```

This adds:
- `grade_level` column to store the grade level for each subject assignment
- `section_id` column to store the section for each subject assignment
- Index on section_id for better query performance

The migration also migrates existing data from the `subject_teachers` table to populate these new columns.

## Modified Files

### 1. adminTeachers.php
**Changes:**
- Replaced single grade/section/subject form with dynamic rows
- Added JavaScript functions: `addSubjectAssignmentRow()`, `removeSubjectAssignmentRow()`, `clearSubjectAssignments()`
- Each row contains: Grade Level + Section + Subject + Remove button
- Modal width increased to `modal-lg` for better display

**New UI Features:**
- "Add Another Subject" button to add more rows
- Each assignment row can be removed individually
- Pre-populated section and subject dropdowns

### 2. subjectTeacherEdit_new.js
**Changes:**
- Removed old logic that read data from button attributes
- Added AJAX call to `getSubjectTeacherAssignments.php` to fetch current assignments
- Populates multiple rows based on fetched data
- Adds one empty row if no assignments exist

### 3. getSubjectTeacherAssignments.php (NEW)
**Purpose:** Fetch subject assignments with grade level and section for a given teacher

**Returns JSON:**
```json
{
  "success": true,
  "assignments": [
    {
      "subject_id": "1",
      "grade_level": "7",
      "section_id": "5",
      "subject_name": "Mathematics",
      "section_name": "BRONZE"
    }
  ]
}
```

### 4. subjectTeacherUpdate.php
**Changes:**
- Modified to accept arrays: `stGradelvl[]`, `stSection[]`, `stSubject[]`
- Validates that all three arrays have the same length
- Loops through assignments and inserts each with its specific grade_level and section_id
- Improved error handling with transaction rollback

**SQL Insert:**
```sql
INSERT INTO subject_teacher_subjects 
(subject_teacher_id, subject_id, grade_level, section_id) 
VALUES ('$stID', '$subjectId', '$gradeLevel', '$sectionId')
```

## How It Works

### Edit Flow:
1. User clicks edit button on a subject teacher
2. JavaScript captures the teacher ID
3. AJAX request fetches all assignments from database
4. For each assignment, a new row is added with pre-selected values
5. User can modify existing rows, add new rows, or remove rows
6. On submit, arrays are sent to PHP backend
7. PHP validates arrays, deletes old assignments, and inserts new ones

### Data Structure:
```
Teacher: John Doe
Assignments:
  Row 1: Grade 7 + BRONZE + Mathematics
  Row 2: Grade 7 + GOLD + Science
  Row 3: Grade 7 + BRONZE + English
```

Submitted as:
- `stGradelvl[]` = ['7', '7', '7']
- `stSection[]` = ['5', '8', '5']  (section IDs)
- `stSubject[]` = ['1', '2', '3']  (subject IDs)

## Testing Instructions

1. **Run the migration:**
   - Open phpMyAdmin
   - Select `educguarddb` database
   - Go to SQL tab
   - Copy and paste the contents of `2025-11-14-add-grade-section-to-subject-teacher-subjects.sql`
   - Click "Go"

2. **Test edit functionality:**
   - Go to Admin → Teachers page
   - Click edit on any Subject Teacher
   - Verify that existing assignments are loaded correctly
   - Try adding a new subject assignment
   - Try removing an assignment
   - Try changing the section for one subject
   - Click "Save Changes"
   - Verify the changes are saved correctly

3. **Verify data in database:**
   ```sql
   SELECT * FROM subject_teacher_subjects WHERE subject_teacher_id = [ID];
   ```
   Should show multiple rows with different grade_level and section_id values.

## Backwards Compatibility

- The `subject_teachers` table still has `stGradelvl` and `stSection` columns
- These are no longer updated by the edit form
- They remain for backwards compatibility with other parts of the system
- You can remove them later if they're not used elsewhere

## Benefits

1. **Accurate representation:** Each subject can have its own section
2. **Flexibility:** Teachers can teach the same subject to multiple sections
3. **Data integrity:** Grade level and section are stored at the assignment level
4. **Better reporting:** Can query which teacher teaches which subject to which section

## Future Enhancements

- Update the "Add Subject Teacher" form to use the same multi-row structure
- Update the display table to show all assignments in a better format (maybe nested table)
- Add validation to prevent duplicate assignments (same subject + section + grade)
