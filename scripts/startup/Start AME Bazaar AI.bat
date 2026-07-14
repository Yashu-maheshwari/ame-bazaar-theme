@echo off
setlocal enabledelayedexpansion

:: Get the directory of this batch file and resolve absolute repository root
set "SCRIPT_DIR=%~dp0"
cd /d "%SCRIPT_DIR%..\.."
set "REPO_ROOT=%CD%"

:: Initialize log files
set "LOG_DIR=%REPO_ROOT%\logs"
if not exist "%LOG_DIR%" mkdir "%LOG_DIR%"
set "STARTUP_LOG=%LOG_DIR%\startup.log"
set "CF_LOG=%LOG_DIR%\cloudflared_temp.log"

echo ========================================= > "%STARTUP_LOG%"
echo AME Bazaar AI OS Startup - %date% %time% >> "%STARTUP_LOG%"
echo ========================================= >> "%STARTUP_LOG%"

goto :main

:log
echo %~1
echo [%date% %time%] %~1 >> "%STARTUP_LOG%"
goto :eof

:: Helper to check n8n local port connectivity using a raw TCP socket test (more reliable than HTTP requests)
:check_n8n_port
powershell -Command "$c = New-Object System.Net.Sockets.TcpClient; try { $c.Connect('127.0.0.1', %N8N_PORT%); if ($c.Connected) { $c.Close(); exit 0 } } catch { exit 1 }" >nul 2>&1
goto :eof

:main
call :log "[INFO] Initializing AME Bazaar AI Platform bootstrap startup system..."

set "ENV_FILE=%REPO_ROOT%\config\local.env"
set "ENV_EXAMPLE=%REPO_ROOT%\config\local.env.example"

:: Check if local.env exists, if not copy from example
if not exist "%ENV_FILE%" (
    if exist "%ENV_EXAMPLE%" (
        call :log "[WARN] config\local.env not found. Copying from config\local.env.example..."
        copy "%ENV_EXAMPLE%" "%ENV_FILE%" >nul
        call :log "[IMPORTANT] Created config\local.env. Please configure machine-specific paths in config\local.env and restart."
        set "ERR_CODE=1"
        goto :error_exit
    ) else (
        call :log "[ERROR] Neither config\local.env nor config\local.env.example exists."
        set "ERR_CODE=1"
        goto :error_exit
    )
)

:: Load variables from config\local.env
call :log "[INFO] Loading configuration from config\local.env..."
for /f "usebackq tokens=1* delims==" %%A in ("%ENV_FILE%") do (
    set "key=%%A"
    set "val=%%B"
    if not "!key!"=="" (
        set "first_char=!key:~0,1!"
        if not "!first_char!"=="#" (
            set "!key!=!val!"
        )
    )
)

:: Validate loaded variables
if "%DOCKER_DESKTOP_PATH%"=="" (
    call :log "[ERROR] DOCKER_DESKTOP_PATH is not defined in local.env"
    set "ERR_CODE=1"
    goto :error_exit
)
if "%N8N_EXECUTABLE%"=="" (
    call :log "[ERROR] N8N_EXECUTABLE is not defined in local.env"
    set "ERR_CODE=1"
    goto :error_exit
)
if "%N8N_PORT%"=="" (
    set "N8N_PORT=5678"
)
if "%CLOUDFLARED_PATH%"=="" (
    call :log "[ERROR] CLOUDFLARED_PATH is not defined in local.env"
    set "ERR_CODE=1"
    goto :error_exit
)

:: 1. Check & Start Docker Desktop
call :log "[INFO] Checking Docker status..."
docker info >nul 2>&1
if %errorlevel% neq 0 (
    call :log "[INFO] Docker is not running. Starting Docker Desktop..."
    if not exist "%DOCKER_DESKTOP_PATH%" (
        call :log "[ERROR] Docker Desktop executable not found at: %DOCKER_DESKTOP_PATH%"
        set "ERR_CODE=2"
        goto :error_exit
    )
    start "" "%DOCKER_DESKTOP_PATH%"
    
    call :log "[INFO] Waiting for Docker Engine to become available..."
    set "DOCKER_TIMEOUT=30"
    :docker_poll
    docker info >nul 2>&1
    if %errorlevel% equ 0 (
        call :log "[INFO] Docker Engine is online."
    ) else (
        set /a DOCKER_TIMEOUT-=1
        if !DOCKER_TIMEOUT! leq 0 (
            call :log "[ERROR] Timeout waiting for Docker Engine to start."
            set "ERR_CODE=2"
            goto :error_exit
        )
        timeout /t 2 /nobreak >nul
        goto :docker_poll
    )
) else (
    call :log "[INFO] Docker Engine is already running."
)

:: 2. Check & Start n8n natively
call :log "[INFO] Checking if n8n is already running on port %N8N_PORT%..."
call :check_n8n_port
if %errorlevel% equ 0 (
    call :log "[INFO] n8n is already running."
) else (
    call :log "[INFO] Starting n8n natively..."
    start "n8n" cmd /k "%N8N_EXECUTABLE%"
    
    call :log "[INFO] Waiting for n8n to respond on port %N8N_PORT%..."
    set "N8N_TIMEOUT=30"
    :n8n_poll
    call :check_n8n_port
    if %errorlevel% equ 0 (
        call :log "[INFO] n8n is now online."
    ) else (
        set /a N8N_TIMEOUT-=1
        if !N8N_TIMEOUT! leq 0 (
            call :log "[ERROR] Timeout waiting for n8n to start."
            set "ERR_CODE=3"
            goto :error_exit
        )
        timeout /t 2 /nobreak >nul
        goto :n8n_poll
    )
)

:: 3. Check & Start Cloudflared natively
call :log "[INFO] Checking if cloudflared is already running..."
tasklist /fi "imagename eq cloudflared.exe" 2>nul | findstr /i "cloudflared.exe" >nul
if %errorlevel% equ 0 (
    call :log "[INFO] cloudflared is already running. Reusing existing instance."
    set "CF_URL="
    if exist "config\runtime.env" (
        for /f "usebackq tokens=1* delims==" %%A in ("config\runtime.env") do (
            if "%%A"=="CURRENT_TUNNEL" set "CF_URL=%%B"
        )
    )
    if "!CF_URL!"=="" (
        set "CF_URL=Unknown (Reused existing running instance)"
    )
) else (
    if exist "%CF_LOG%" del /f "%CF_LOG%"
    call :log "[INFO] Starting Cloudflared tunnel natively..."
    if not exist "%CLOUDFLARED_PATH%" (
        call :log "[ERROR] cloudflared.exe not found at: %CLOUDFLARED_PATH%"
        set "ERR_CODE=4"
        goto :error_exit
    )

    start "cloudflared_process" /min "%CLOUDFLARED_PATH%" tunnel --url http://localhost:%N8N_PORT% --logfile "%CF_LOG%"

    call :log "[INFO] Waiting for Cloudflared tunnel to generate URL..."
    set "CF_TIMEOUT=15"
    set "CF_URL="
    :cf_poll
    if exist "%CF_LOG%" (
        for /f "usebackq tokens=*" %%A in (`powershell -Command "Select-String -Path '%CF_LOG%' -Pattern 'https://[a-zA-Z0-9.-]+\.trycloudflare\.com' | ForEach-Object { $_.Matches.Value }" 2^>nul`) do (
            set "CF_URL=%%A"
        )
    )
    if not "!CF_URL!"=="" (
        call :log "[INFO] Cloudflare tunnel established successfully: !CF_URL!"
    ) else (
        set /a CF_TIMEOUT-=1
        if !CF_TIMEOUT! leq 0 (
            call :log "[ERROR] Timeout waiting for Cloudflared tunnel URL. Check %CF_LOG% for details."
            set "ERR_CODE=4"
            goto :error_exit
        )
        timeout /t 1 /nobreak >nul
        goto :cf_poll
    )
)

:: 4. Save runtime environment variables (Only reached on absolute success)
call :log "[INFO] Saving runtime variables to config\runtime.env..."
for /f "usebackq tokens=*" %%T in (`powershell -Command "Get-Date -Format 'yyyy-MM-ddTHH:mm:sszzz'"`) do set "START_TIME=%%T"
(
echo CURRENT_TUNNEL=!CF_URL!
echo STARTED_AT=!START_TIME!
echo PLATFORM_VERSION=1.0.0
) > "config\runtime.env"

:: Clean up temporary log
if exist "%CF_LOG%" del /f "%CF_LOG%"

:: Gather system details for summary
set "GIT_BRANCH=N/A"
set "GIT_COMMIT=N/A"
for /f "usebackq tokens=*" %%B in (`git rev-parse --abbrev-ref HEAD 2^>nul`) do set "GIT_BRANCH=%%B"
for /f "usebackq tokens=*" %%C in (`git log -1 --format^="%%h - %%s" 2^>nul`) do set "GIT_COMMIT=%%C"

echo.
echo ===================================================
echo             AME BAZAAR AI PLATFORM STATUS          
echo ===================================================
echo  Repository:       %REPO_ROOT%
echo  Branch:           !GIT_BRANCH!
echo  Last Commit:      !GIT_COMMIT!
echo  Docker:           Running (Docker Engine Online)
echo  n8n:              Running (http://localhost:%N8N_PORT%)
echo  Cloudflare:       Active (Tunnel Connected)
echo  Tunnel URL:       !CF_URL!
echo  Platform Version: 1.0.0
echo ===================================================
echo.
call :log "[INFO] Platform started successfully."

pause
exit /b 0

:error_exit
call :log "[ERROR] Platform startup failed."
pause
exit /b %ERR_CODE%
