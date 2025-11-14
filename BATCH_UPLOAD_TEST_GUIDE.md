# Quick Test Guide - Student Batch Upload

## Step-by-Step Testing

### 1. Access the Feature
```
1. Log in as an adviser
2. Navigate to: PHPAdviser/studentList.php
3. Look for "Batch Upload" button (green button next to "Add Student")
```

### 2. Download Template
```
1. Click "Batch Upload" button
2. Modal will open with instructions
3. Click either:
   - "Excel Template (.xls)" button, OR
   - "CSV Template" button
4. Template file will download automatically
```

### 3. Fill in Data
Open the template in Excel, Google Sheets, or any spreadsheet software.

**Sample Valid Data:**
```
First Name: Maria
Middle Name: Santos
Last Name: Garcia
Birth Date: 2010-03-20
Sex: Female
Contact Number: 09123456789
Address: 456 Street Name, Valenzuela City
Parent/Guardian Name: Roberto Garcia
Parent/Guardian Number: 09198765432
Parent/Guardian Email: roberto.garcia@email.com
LRN: 123456789013
Email: maria.garcia@student.edu
Username: mgarcia
Password: Maria2024!
Status: active
```

**Common Mistakes to Avoid:**
- ❌ Birth date in wrong format (use YYYY-MM-DD)
- ❌ Sex not "Male" or "Female"
- ❌ LRN not 12 digits
- ❌ Duplicate LRN
- ❌ Empty required fields
- ❌ Invalid email format

### 4. Upload File
```
1. Save your filled template
2. Close the file in Excel (important!)
3. In the modal, click "Choose File"
4. Select your saved template
5. Click "Upload & Process" button
6. Wait for confirmation message
```

### 5. Verify Results

**Success Message Example:**
```
✓ Batch upload completed! Successfully added: 5 students.
```

**Partial Success Example:**
```
⚠ Batch upload completed! Successfully added: 3 students. Failed: 2 students.
Errors:
- Row 3: LRN 123456789012 already exists
- Row 5: Missing required fields
```

**Check Student List:**
```
1. Scroll down to the student table
2. New students should appear
3. Verify all information is correct
4. Test student login (optional)
```

## Sample Test Cases

### Test Case 1: Valid Single Student
```csv
Juan,Reyes,Dela Cruz,2010-05-15,Male,09123456789,123 Main St Manila,Maria Dela Cruz,09187654321,maria@email.com,123456789012,juan.dc@student.edu,jdelacruz,Pass123,active
```
**Expected:** ✓ Success

### Test Case 2: Invalid Date Format
```csv
Pedro,Santos,Lopez,05/15/2010,Male,09123456788,456 Street,Ana Lopez,09187654322,ana@email.com,123456789013,pedro@student.edu,plopez,Pass123,active
```
**Expected:** ❌ Error (date format)

### Test Case 3: Duplicate LRN
```csv
Ana,Maria,Santos,2010-06-20,Female,09123456787,789 Ave,Jose Santos,09187654323,jose@email.com,123456789012,ana@student.edu,asantos,Pass123,active
```
**Expected:** ❌ Error (LRN already exists if row 1 was processed)

### Test Case 4: Missing Required Field
```csv
Luis,,,2010-07-25,Male,09123456786,101 Road,Carmen Cruz,09187654324,carmen@email.com,123456789014,luis@student.edu,lcruz,Pass123,active
```
**Expected:** ❌ Error (missing last name)

### Test Case 5: Multiple Valid Students
```csv
Sofia,Angel,Reyes,2010-08-10,Female,09123456785,202 Blvd,Rosa Reyes,09187654325,rosa@email.com,123456789015,sofia@student.edu,sreyes,Pass123,active
Miguel,Jose,Torres,2010-09-15,Male,09123456784,303 Circle,Elena Torres,09187654326,elena@email.com,123456789016,miguel@student.edu,mtorres,Pass123,active
Carmen,Luna,Ramos,2010-10-20,Female,09123456783,404 Plaza,Diego Ramos,09187654327,diego@email.com,123456789017,carmen@student.edu,cramos,Pass123,active
```
**Expected:** ✓ Success (3 students)

## Troubleshooting

### Problem: "Please upload a valid Excel file"
**Solution:** Check that:
- File is not corrupted
- File extension is .xlsx, .xls, or .csv
- File is not open in Excel when uploading

### Problem: "Only Excel (.xlsx, .xls) or CSV (.csv) files are allowed"
**Solution:** 
- Make sure file has correct extension
- Don't use .xlsm, .xlsb, or other formats
- Try CSV if Excel isn't working

### Problem: "LRN already exists"
**Solution:**
- Each student needs a unique 12-digit LRN
- Check if student was already added manually
- Use different LRNs for each student

### Problem: No students added, no error shown
**Solution:**
- Check if file has data rows (not just headers)
- Verify file isn't corrupted
- Try downloading template again

### Problem: "Section details not found"
**Solution:**
- Make sure your adviser account is assigned to a section
- Contact admin to assign you a section

## Database Check (For Developers)

To verify students were added correctly:

```sql
-- Check students table
SELECT * FROM students 
WHERE section = 'YOUR_SECTION_NAME' 
ORDER BY last_name ASC;

-- Check user accounts
SELECT * FROM user_acc 
WHERE user_role = 'student' 
AND user_email LIKE '%@student.edu';

-- Count students by section
SELECT section, COUNT(*) as student_count 
FROM students 
GROUP BY section;
```

## Performance Notes

- **Small files (1-10 students)**: Instant processing
- **Medium files (11-50 students)**: 1-3 seconds
- **Large files (51-100 students)**: 3-10 seconds
- **Very large files (100+ students)**: May need PHP timeout adjustment

## Security Checklist

- ✓ Only advisers can access batch upload
- ✓ Students added only to adviser's assigned section
- ✓ Passwords are hashed (not stored plain text)
- ✓ User accounts automatically created
- ✓ SQL injection prevention enabled
- ✓ File type validation

## Success Indicators

After successful upload, you should see:
1. Success message at top of page
2. New students in the table
3. Student count increased
4. Students can log in with provided credentials
5. No database errors in PHP logs

---

**Ready to Test?** Follow steps 1-5 above and refer to this guide if you encounter issues!
