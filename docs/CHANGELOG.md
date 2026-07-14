# CHANGELOG.md - Project Revision History

- **Last Updated:** 2026-07-14
- **Version:** 1.2.0
- **Owner:** AME Bazaar AI OS Core
- **Purpose:** Automatically and manually tracks all important architectural, code, and documentation changes in the repository.
- **Dependencies:** None
- **Status:** Active

---

## Change Log

### [1.2.0] - 2026-07-14
#### Added
- Created modular startup scripts `scripts/startup/Start n8n.bat` and `scripts/startup/Start Cloudflared.bat`.
#### Changed
- Refactored master script `scripts/startup/Start AME Bazaar AI.bat` to act as a lightweight orchestrator that verifies Docker, spawns individual startup scripts in separate windows, and opens the browser.
- Removed complex health checks, loops, polling, and PowerShell socket checks to improve startup simplicity and reliability.

### [1.1.0] - 2026-07-14
#### Added
- Created desktop launcher script: `scripts/startup/Create Desktop Shortcut.bat`.
- Detects the current Windows user's Desktop folder dynamically and creates/updates a portable shortcut named "🚀 Start AME Bazaar AI" pointing to the startup script.
- Configured to run cleanly without requiring admin privileges.
#### Changed
- Refined native n8n startup command to use `start "n8n" cmd /k` for enhanced startup execution reliability and direct error feedback.

### [1.0.0] - 2026-07-14
#### Added
- Created production-ready startup system script: `scripts/startup/Start AME Bazaar AI.bat`.
- Added configuration templates: `config/local.env.example` and dynamic runtime environment `config/runtime.env`.
- Configured git-ignore rules for `local.env` and `runtime.env` files.
- Formulated custom health checking system structure with folder `scripts/checks/`.
- Configured complete start-to-finish platform status output summary.
- Created project management foundation docs inside `docs/`.
- Established new directory structures `/workflows`, `/prompts`, `/memory`, `/scripts`, `/backups` with `.gitkeep` placeholders.
- Initialized local state databases (`/memory/`) for business config, products, customers, campaigns, settings, and agent configurations.
- Created root-level playbooks: `START_HERE.md` (onboarding), `SETUP.md` (environment config), and `RECOVERY.md` (disaster recovery playbook).
- Configured local agent task execution rules inside `.agents/AGENTS.md`.
