# Changelog

All notable changes to the Pengaduan Warga project will be documented in this file.

## [1.0.0] - 2024-01-01

### Added
- Initial release of Pengaduan Warga system
- User registration and authentication system
- Complaint submission with photo upload
- Admin dashboard for managing complaints
- Category management system
- Status tracking for complaints
- Report generation with filters
- Export to Excel functionality
- Print-friendly report layouts
- Responsive design with Bootstrap 5
- Input validation and sanitization
- Error logging system
- Security features (CSRF protection, password hashing)
- User profile management
- Password change functionality

### Features
#### For Citizens:
- User registration and login
- Submit complaints with categories
- Upload supporting photos
- Track complaint status in real-time
- Personal dashboard with statistics
- Profile management

#### For Administrators:
- Admin dashboard with overview statistics
- Manage all complaints (view, update status, respond)
- Category management (add, edit, delete)
- Generate filtered reports
- Export reports to Excel
- Print reports
- User management

### Technical Features:
- MVC architecture pattern
- PHP native implementation
- MySQL database
- Bootstrap 5 responsive design
- Font Awesome icons
- File upload handling
- Session management
- Input validation
- Error handling and logging
- Security best practices

### Security:
- Password hashing with PHP password_hash()
- Input sanitization and validation
- SQL injection protection with PDO
- File upload security
- Session security
- CSRF token protection
- Error logging

### Database:
- Users table with role-based access
- Complaints table with status tracking
- Categories table for organization
- Foreign key relationships
- UTF-8 character set support

### File Structure:
- Organized MVC folder structure
- Separate configuration files
- Helper classes for common functions
- Error pages for better UX
- Comprehensive documentation

## [Future Releases]

### Planned Features:
- Email notifications for status updates
- SMS notifications integration
- Advanced reporting with charts
- API endpoints for mobile app
- Multi-language support
- Advanced search and filtering
- Complaint priority levels
- File attachment support (documents)
- Complaint forwarding between departments
- Public complaint viewing (anonymous)
- Social media integration
- Mobile app companion
