# Upload Directory

This directory contains uploaded photos from user complaints.

## Security Notes

- This directory is protected by .htaccess
- Only image files are allowed to be accessed directly
- Directory listing is disabled
- All uploads are validated on the server side

## File Structure

```
uploads/
├── .htaccess          # Security configuration
├── README.md          # This file
└── [uploaded files]   # User uploaded photos
```

## Supported Formats

- JPEG (.jpg, .jpeg)
- PNG (.png)
- GIF (.gif)
- WebP (.webp)

## File Size Limits

- Maximum file size: 5MB
- Files are automatically validated
- Invalid files are rejected

## Backup Recommendations

- Regular backup of this directory is recommended
- Consider cloud storage integration for production
- Monitor disk space usage

---

**Note**: This README.md file is kept in the repository to ensure the uploads directory exists in Git, but actual uploaded files are ignored via .gitignore.
