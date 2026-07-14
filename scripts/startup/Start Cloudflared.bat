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

echo Starting Cloudflared natively...
start "cloudflared" "%CLOUDFLARED_PATH%" tunnel --url http://localhost:%N8N_PORT%
exit /b 0
