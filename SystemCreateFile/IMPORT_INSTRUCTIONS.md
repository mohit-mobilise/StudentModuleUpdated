# Database Import Instructions

## Your SQL File
- **Location:** `C:\Users\mohit.yadav\Documents\Mohit Yadav\DB Backup\schoolerpbeta_MY03112025.sql`
- **Size:** ~702 MB (Large file)

## Method 1: Using phpMyAdmin (Best for Large Files)

Since your SQL file is 702 MB, phpMyAdmin might have upload limits. Follow these steps:

### Step 1: Increase phpMyAdmin Upload Limits

1. Find your `php.ini` file (usually in `C:\xampp\php\php.ini`)
2. Open it in a text editor (as Administrator)
3. Find and change these values:
   ```
   upload_max_filesize = 1024M
   post_max_size = 1024M
   max_execution_time = 0
   memory_limit = 2048M
   ```
4. **Save the file**
5. **Restart Apache** in XAMPP Control Panel

### Step 2: Import in phpMyAdmin

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Click on **`schoolerpbeta`** database in left sidebar
3. Click **"Import"** tab at top
4. Click **"Choose File"**
5. Navigate to: `C:\Users\mohit.yadav\Documents\Mohit Yadav\DB Backup\schoolerpbeta_MY03112025.sql`
6. **Important Settings:**
   - Format: SQL
   - Partial import: Unchecked
   - Allow interrupt: Checked (in case it takes too long)
7. Click **"Go"** button
8. **Wait patiently** - 702 MB can take 5-15 minutes depending on your system

---

## Method 2: Using PHP Import Script

1. Open: `http://localhost/cursorai/Testing/studentportal/import_sql_direct.php`
2. The script will automatically:
   - Connect to database
   - Import the SQL file
   - Show progress
   - Display results

**Note:** This may take 10-20 minutes for a 702 MB file. Don't close the browser.

---

## Method 3: Command Line (Fastest for Large Files)

If phpMyAdmin or PHP scripts timeout, use command line:

1. Open **Command Prompt as Administrator**
2. Navigate to MySQL bin:
   ```cmd
   cd C:\xampp\mysql\bin
   ```
3. Run import command:
   ```cmd
   mysql.exe -u root -P 3308 schoolerpbeta < "C:\Users\mohit.yadav\Documents\Mohit Yadav\DB Backup\schoolerpbeta_MY03112025.sql"
   ```

If port 3308 doesn't work, try:
```cmd
mysql.exe -u root schoolerpbeta < "C:\Users\mohit.yadav\Documents\Mohit Yadav\DB Backup\schoolerpbeta_MY03112025.sql"
```

4. Wait for completion (no output means success)

---

## Troubleshooting

### If Import Fails or Times Out:

1. **Check PHP limits:**
   - Open: `http://localhost/cursorai/Testing/studentportal/phpinfo_config.php`
   - Check `upload_max_filesize` and `post_max_size`
   - They should be at least 1024M

2. **Split the SQL file:**
   - Use a SQL file splitter tool
   - Import in smaller chunks

3. **Use Command Line:**
   - Most reliable for large files
   - No PHP/phpMyAdmin limits

---

## Verification

After import, verify in phpMyAdmin:
1. Click on `schoolerpbeta` database
2. You should see many tables listed
3. Check for key tables:
   - `student_master`
   - `class_master`
   - `fees`
   - `attendance`
   - etc.

---

**File Created:** 2025-11-03  
**For Database:** schoolerpbeta  
**File Size:** ~702 MB


