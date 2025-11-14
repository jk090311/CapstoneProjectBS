# Student Batch Upload Feature - Setup Instructions

## Overview
This feature allows advisers to upload multiple student records at once using an Excel file (.xlsx or .xls format).

## Basic Setup (No Additional Requirements)
The batch upload feature works out of the box with basic PHP functionality. It can read:
- `.xls` files (HTML table format)
- `.xlsx` files (using ZipArchive and SimpleXML)

## Enhanced Setup (Recommended)
For better Excel file handling and support for more complex spreadsheets, install PhpSpreadsheet library:

### Installation Steps:

1. **Install Composer** (if not already installed)
   - Download from: https://getcomposer.org/download/
   - Run the installer
   - Verify installation: `composer --version`

2. **Navigate to Project Directory**
   ```bash
   cd c:\xampp\htdocs\CapstoneProjectBS102825
   ```

3. **Install PhpSpreadsheet**
   ```bash
   composer require phpoffice/phpspreadsheet
   ```

4. **Verify Installation**
   - A `vendor` folder should be created in your project root
   - The batch upload script will automatically detect and use PhpSpreadsheet

## How to Use

### For Advisers:

1. **Navigate to Student List Page**
   - Log in as an adviser
   - Go to the Student List page

2. **Download Template**
   - Click "Batch Upload" button
   - Click "Download Excel Template"
   - Save the template file

3. **Fill in Student Data**
   - Open the template in Excel
   - Fill in the required fields (marked with *)
   - Required columns:
     - First Name, Middle Name, Last Name
     - Birth Date (YYYY-MM-DD format)
     - Sex (Male/Female)
     - Contact Number
     - Address
     - Parent/Guardian Name & Number
     - LRN (12-digit number)
     - Email, Username, Password
     - Status (active/inactive)

4. **Upload the File**
   - Click "Batch Upload" button
   - Click "Choose File" and select your filled template
   - Click "Upload & Process"
   - Wait for confirmation message

5. **Review Results**
   - Success message will show how many students were added
   - If there are errors, they will be listed
   - Students will be automatically assigned to your section

## Excel Template Format

| First Name | Middle Name | Last Name | Birth Date | Sex | Contact Number | Address | Parent Name | Parent Number | Parent Email | LRN | Email | Username | Password | Status |
|------------|-------------|-----------|------------|-----|----------------|---------|-------------|---------------|--------------|-----|-------|----------|----------|--------|
| Juan | Reyes | Dela Cruz | 2010-05-15 | Male | 09123456789 | 123 Main St | Maria Cruz | 09187654321 | maria@email.com | 123456789012 | juan@student.edu | jdelacruz | Pass123 | active |

## Important Notes

- All students will be assigned to the adviser's section automatically
- Grade level and year level are pulled from the section settings
- LRN must be unique (12 digits)
- Email addresses must be unique
- Passwords will be hashed for security
- Birth dates must be in YYYY-MM-DD format
- Sex must be either "Male" or "Female"
- Status can be "active" or "inactive"

## Troubleshooting

### File Upload Errors
- Make sure the file is in .xlsx or .xls format
- Check file size (should be under PHP's upload limit, typically 2MB)
- Ensure the file is not open in Excel when uploading

### Data Import Errors
- Verify all required fields are filled
- Check date format (must be YYYY-MM-DD)
- Ensure LRN is unique and 12 digits
- Make sure email addresses are valid and unique

### Permission Errors
- Only advisers can upload student data
- Students will only be added to the adviser's assigned section

## File Locations

- Template Download: `PHP/downloadStudentTemplate.php`
- Batch Upload Processor: `PHP/batchUploadStudents.php`
- Student List Page: `PHPAdviser/studentList.php`

## Security Features

- Session validation (must be logged in as adviser)
- SQL injection prevention (mysqli_real_escape_string)
- Password hashing (PASSWORD_BCRYPT)
- File type validation
- Duplicate LRN detection
- Transaction support (rollback on error)

## Future Enhancements

- Support for CSV files
- Data validation preview before import
- Download error report as file
- Support for updating existing students
- Image upload support
- Progress bar during upload
