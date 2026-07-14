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

echo Starting n8n natively...
start "n8n" cmd /k "%N8N_EXECUTABLE%"
exit /b 0
