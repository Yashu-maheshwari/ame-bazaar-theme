@echo off
setlocal enabledelayedexpansion

:: Get the directory of this batch file and resolve absolute repository root
set "SCRIPT_DIR=%~dp0"
cd /d "%SCRIPT_DIR%..\.."

:: Load local.env
if exist "config\local.env" (
    for /f "usebackq tokens=1* delims==" %%A in ("config\local.env") do (
        set "key=%%A"
        set "val=%%B"
        if not "!key!"=="" if not "!key:~0,1!"=="#" set "!key!=!val!"
    )
)

if "%N8N_PORT%"=="" set "N8N_PORT=5678"

:: Detect if localhost:N8N_PORT is already responding
powershell -Command "$c = New-Object System.Net.Sockets.TcpClient; try { $c.Connect('127.0.0.1', %N8N_PORT%); if ($c.Connected) { $c.Close(); exit 0 } } catch { exit 1 }" >nul 2>&1
if %errorlevel% equ 0 (
    echo ✅ n8n already running.
) else (
    echo Starting n8n natively...
    start "n8n" cmd /k "%N8N_EXECUTABLE%"
)

exit /b 0
