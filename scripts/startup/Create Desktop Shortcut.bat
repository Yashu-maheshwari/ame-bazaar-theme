@echo off
setlocal enabledelayedexpansion

:: Get the directory of this batch file
set "SCRIPT_DIR=%~dp0"
cd /d "%SCRIPT_DIR%"

:: Resolve the absolute path of the startup script and its working directory
set "TARGET_BAT=%CD%\Start AME Bazaar AI.bat"
set "WORK_DIR=%CD%"

:: Detect Windows User's Desktop folder path using PowerShell (robust, no hardcoded username)
for /f "usebackq tokens=*" %%D in (`powershell -Command "[Environment]::GetFolderPath('Desktop')"`) do (
    set "DESKTOP_DIR=%%D"
)

if "%DESKTOP_DIR%"=="" (
    echo [ERROR] Could not automatically detect the Windows Desktop folder path.
    pause
    exit /b 1
)

set "SHORTCUT_PATH=%DESKTOP_DIR%\🚀 Start AME Bazaar AI.lnk"

echo [INFO] Creating/Updating desktop shortcut...
echo [INFO] Shortcut Path: %SHORTCUT_PATH%
echo [INFO] Target Path:   %TARGET_BAT%
echo [INFO] Working Dir:   %WORK_DIR%

:: Create or update the shortcut file (.lnk) using PowerShell (no admin permissions required)
powershell -Command "$WshShell = New-Object -ComObject WScript.Shell; $Shortcut = $WshShell.CreateShortcut('%SHORTCUT_PATH%'); $Shortcut.TargetPath = '%TARGET_BAT%'; $Shortcut.WorkingDirectory = '%WORK_DIR%'; $Shortcut.Save()"

if %errorlevel% equ 0 (
    echo [SUCCESS] Desktop shortcut '🚀 Start AME Bazaar AI' has been created or updated successfully!
) else (
    echo [ERROR] Failed to create or update the desktop shortcut.
    pause
    exit /b 1
)

pause
exit /b 0
