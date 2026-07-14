@echo off
setlocal enabledelayedexpansion

:: Get the directory of this batch file and resolve absolute repository root
set "SCRIPT_DIR=%~dp0"
cd /d "%SCRIPT_DIR%..\.."
set "REPO_ROOT=%CD%"

set "ENV_FILE=%REPO_ROOT%\config\local.env"
set "ENV_EXAMPLE=%REPO_ROOT%\config\local.env.example"

:: Check if local.env exists, if not copy from example
if not exist "%ENV_FILE%" (
    if exist "%ENV_EXAMPLE%" (
        echo [WARN] config\local.env not found. Copying from config\local.env.example...
        copy "%ENV_EXAMPLE%" "%ENV_FILE%" >nul
        echo [IMPORTANT] Created config\local.env. Please configure machine-specific paths in config\local.env and restart.
        pause
        exit /b 1
    ) else (
        echo [ERROR] Neither config\local.env nor config\local.env.example exists.
        pause
        exit /b 1
    )
)

:: Load variables from config\local.env
for /f "usebackq tokens=1* delims==" %%A in ("%ENV_FILE%") do (
    set "key=%%A"
    set "val=%%B"
    if not "!key!"=="" if not "!key:~0,1!"=="#" set "!key!=!val!"
)

:: Validate variables
if "%DOCKER_DESKTOP_PATH%"=="" (
    echo [ERROR] DOCKER_DESKTOP_PATH is not defined in local.env
    pause
    exit /b 1
)
if "%N8N_PORT%"=="" (
    set "N8N_PORT=5678"
)

:: 1. Start Docker Desktop if not running
echo Checking Docker status...
docker info >nul 2>&1
if %errorlevel% neq 0 (
    echo Docker is not running. Starting Docker Desktop...
    if not exist "%DOCKER_DESKTOP_PATH%" (
        echo [ERROR] Docker Desktop executable not found at: %DOCKER_DESKTOP_PATH%
        pause
        exit /b 2
    )
    start "" "%DOCKER_DESKTOP_PATH%"
    echo Waiting 5 seconds for Docker Desktop initialization...
    timeout /t 5 /nobreak >nul
) else (
    echo Docker is already running.
)

:: 2. Launch Start n8n.bat
echo Launching n8n startup...
start "" cmd /c "%SCRIPT_DIR%Start n8n.bat"
echo Waiting 3 seconds for n8n initialization...
timeout /t 3 /nobreak >nul

:: 3. Launch Start Cloudflared.bat
echo Launching Cloudflared startup...
start "" cmd /c "%SCRIPT_DIR%Start Cloudflared.bat"
echo Waiting 2 seconds...
timeout /t 2 /nobreak >nul

:: 4. Open browser to n8n console
echo Opening browser to http://localhost:%N8N_PORT%...
start http://localhost:%N8N_PORT%

echo.
echo ===================================================
echo  AME Bazaar AI OS Platform Bootstrap Started
echo ===================================================
echo  Docker:       Checked / Initiated
echo  n8n:          Launched in separate window
echo  Cloudflare:   Launched in separate window
echo  Local URL:    http://localhost:%N8N_PORT%
echo ===================================================
echo.

pause
exit /b 0
