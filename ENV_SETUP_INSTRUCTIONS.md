# Environment Variables Setup Instructions

## Overview
The application now uses environment variables for secure credential management. This prevents sensitive credentials from being exposed in source code.

## Setup Steps

### 1. Create .env File
Copy the `.env.example` file to `.env` in the root directory:

```bash
cp .env.example .env
```

**Note:** The `.env` file is automatically excluded from version control via `.gitignore`.

### 2. Configure Your .env File
Edit the `.env` file and fill in your actual database credentials:

```env
# Server Database Settings
DB_HOST_SERVER=10.26.1.196
DB_USERNAME_SERVER=schoolerp
DB_PASSWORD_SERVER=your_actual_password_here
DB_NAME_SERVER=schoolerpbeta

# Localhost Database Settings (for development)
DB_HOST_LOCAL=127.0.0.1
DB_PORT_LOCAL=3308
DB_USERNAME_LOCAL=root
DB_PASSWORD_LOCAL=
DB_NAME_LOCAL=schoolerpbeta

# Environment (use 'server' or 'localhost')
DB_ENVIRONMENT=server

# URL Settings
BASE_URL=http://localhost/cursorai/Testing/studentportal/
IMAGE_BASE_URL=http://localhost/cursorai/Testing/studentportal/

# Error Handling (set to 'false' or '0' to disable for debugging)
ENABLE_ERROR_HANDLER=true
```

### 3. Verify Setup
- Ensure `.env` file exists in the root directory
- Verify `.env` is listed in `.gitignore` (already done)
- Test the application to ensure database connection works

## Security Notes
- ✅ Never commit `.env` file to version control
- ✅ Keep `.env` file permissions restricted (readable only by web server)
- ✅ Use different credentials for development and production
- ✅ Rotate credentials periodically

## Troubleshooting

### Connection Issues
If you experience database connection issues:
1. Verify credentials in `.env` file are correct
2. Check that `DB_ENVIRONMENT` is set correctly ('server' or 'localhost')
3. Ensure database server is accessible
4. Check error logs in `logs/error.log` (if directory exists)

### Environment Variables Not Loading
If environment variables aren't loading:
1. Verify `includes/env_loader.php` exists
2. Check file permissions
3. Ensure `.env` file is in the root directory
4. Check PHP error logs

