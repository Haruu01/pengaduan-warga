@echo off
echo ========================================
echo    Setup Git Repository
echo    Sistem Pengaduan Warga
echo ========================================
echo.

REM Check if git is installed
git --version >nul 2>&1
if errorlevel 1 (
    echo ERROR: Git is not installed or not in PATH
    echo Please install Git from https://git-scm.com/
    pause
    exit /b 1
)

echo [1/6] Initializing Git repository...
git init

echo [2/6] Adding all files...
git add .

echo [3/6] Creating initial commit...
git commit -m "Initial commit: Sistem Pengaduan Warga with photo display feature"

echo [4/6] Setting up remote repository...
echo.
echo Please create a new repository on GitHub first:
echo 1. Go to https://github.com/new
echo 2. Repository name: pengaduan-warga
echo 3. Description: Sistem Pengaduan Warga - Web Application for Citizen Complaints
echo 4. Set to Public or Private
echo 5. Do NOT initialize with README (we already have one)
echo 6. Click "Create repository"
echo.
set /p github_url="Enter your GitHub repository URL (e.g., https://github.com/username/pengaduan-warga.git): "

if "%github_url%"=="" (
    echo ERROR: GitHub URL is required
    pause
    exit /b 1
)

echo [5/6] Adding remote origin...
git remote add origin %github_url%

echo [6/6] Pushing to GitHub...
git branch -M main
git push -u origin main

echo.
echo ========================================
echo    SUCCESS! Repository uploaded to GitHub
echo ========================================
echo.
echo Your repository is now available at:
echo %github_url%
echo.
echo Next steps for deployment:
echo 1. Choose a hosting provider (shared hosting, VPS, cloud)
echo 2. Follow the DEPLOYMENT.md guide
echo 3. Update database configuration
echo 4. Test the application
echo.
echo Files ready for deployment:
echo - All application files
echo - Database structure
echo - Configuration examples
echo - Deployment guides
echo.
pause
