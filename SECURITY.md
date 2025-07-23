# Security Policy

## Supported Versions

| Version | Supported          |
| ------- | ------------------ |
| 1.0.x   | :white_check_mark: |

## Security Features

### Authentication & Authorization
- Password hashing using PHP's `password_hash()` function
- Session-based authentication
- Role-based access control (Admin/User)
- Session timeout and security

### Input Validation & Sanitization
- All user inputs are validated and sanitized
- SQL injection protection using PDO prepared statements
- XSS protection through HTML escaping
- File upload validation and restrictions

### File Security
- Upload directory protection via .htaccess
- File type validation for uploads
- File size limitations
- Secure file naming conventions

### Database Security
- PDO with prepared statements
- Database connection encryption ready
- Proper error handling without information disclosure
- Database user with minimal required privileges

### Session Security
- Secure session configuration
- Session regeneration on login
- Proper session cleanup on logout
- CSRF token protection

### Error Handling
- Comprehensive error logging
- No sensitive information in error messages
- Custom error pages for better UX
- Debug mode control for production

## Security Best Practices for Deployment

### Server Configuration
1. **Use HTTPS**: Always deploy with SSL/TLS encryption
2. **Hide Server Information**: Disable server signature and version disclosure
3. **File Permissions**: Set proper file and directory permissions
   - Files: 644
   - Directories: 755
   - Uploads directory: 755 (with .htaccess protection)
   - Logs directory: 755 (with .htaccess protection)

### Database Security
1. **Database User**: Create a dedicated database user with minimal privileges
2. **Database Password**: Use strong, unique passwords
3. **Database Access**: Restrict database access to application server only
4. **Regular Backups**: Implement secure backup procedures

### Application Security
1. **Debug Mode**: Set `DEBUG = false` in production
2. **Error Reporting**: Disable error display in production
3. **Log Monitoring**: Regularly monitor application logs
4. **Updates**: Keep PHP and dependencies updated

### File Security
1. **Upload Restrictions**: 
   - Limit file types to images only
   - Set maximum file size limits
   - Scan uploaded files for malware
2. **Directory Protection**: Ensure .htaccess files are in place
3. **File Permissions**: Regular audit of file permissions

