# START_HERE.md - AI & Developer Onboarding

- **Last Updated:** 2026-07-14
- **Version:** 1.0.0
- **Owner:** AME Bazaar AI OS Core
- **Purpose:** Onboarding guide for humans and AI agents to understand the project structure, vision, rules, and status in under five minutes.
- **Dependencies:** None
- **Status:** Approved

---

## 1. What This Project Is
This repository contains the digital infrastructure, custom WordPress templates (Astra Child Theme), automated workflows, and execution memory for the **AME Bazaar AI Operating System**. It serves as the single source of truth for all business logic, technical details, and code.

## 2. Business Vision
To scale **AME Bazaar** (Apparel Maheshwari Enterprises) from a trusted offline family garment retailer in Kirari, Delhi, into an omnichannel fashion platform. The business model transitions through four stages:
1. **Local Dominance:** Drive physical store foot traffic using Hyperlocal SEO and AI Search Optimization.
2. **WhatsApp commerce:** Catalogs and order placement routed directly via WhatsApp.
3. **WooCommerce Transactions:** Full-scale automated e-commerce.
4. **Scale:** Private label products and nationwide franchise networks.

## 3. Current Stage & Milestone
- **Current Stage:** Phase 1 (Theme Foundation Setup Completed).
- **Current Milestone:** Milestone 1: AI Operating System Core Repository Setup (Initialized).
- **Active Branch:** `main`

## 4. Project Memory Location
- **Local Database State:** Stored inside [/memory/](file:///C:/Users/user/.gemini/antigravity/scratch/ame-bazaar-git/memory/) (`business.json`, `products.json`, `customers.json`, `campaigns.json`, `settings.json`, `ai_agents.json`).
- **Project Tracking:** Active status and checklist live in [docs/CURRENT_STATUS.md](file:///C:/Users/user/.gemini/antigravity/scratch/ame-bazaar-git/docs/CURRENT_STATUS.md).

## 5. Mandatory Reading Order
Before making *any* code edits or writing documentation, humans and AI agents **MUST** read:
1. [docs/MASTER_PLAN.md](file:///C:/Users/user/.gemini/antigravity/scratch/ame-bazaar-git/docs/MASTER_PLAN.md)
2. [docs/CURRENT_STATUS.md](file:///C:/Users/user/.gemini/antigravity/scratch/ame-bazaar-git/docs/CURRENT_STATUS.md)
3. [docs/NEXT_TASK.md](file:///C:/Users/user/.gemini/antigravity/scratch/ame-bazaar-git/docs/NEXT_TASK.md)
4. [docs/PROJECT_CONTEXT.md](file:///C:/Users/user/.gemini/antigravity/scratch/ame-bazaar-git/docs/PROJECT_CONTEXT.md)

## 6. How Future AI Should Continue Development
1. Read the mandatory files listed above.
2. Read the active next task in [docs/NEXT_TASK.md](file:///C:/Users/user/.gemini/antigravity/scratch/ame-bazaar-git/docs/NEXT_TASK.md).
3. Create a feature branch named `feature/<task-name>`.
4. Perform the development following the rules in [docs/PROMPT_RULES.md](file:///C:/Users/user/.gemini/antigravity/scratch/ame-bazaar-git/docs/PROMPT_RULES.md) and [.agents/AGENTS.md](file:///C:/Users/user/.gemini/antigravity/scratch/ame-bazaar-git/.agents/AGENTS.md).
5. Open a Draft Pull Request (do not merge to main directly).
6. Update `CURRENT_STATUS.md`, `CHANGELOG.md`, and `NEXT_TASK.md`.

## 7. Recovery Entry Point
In the event of system failure, local laptop crashes, or environment corruption, execute the playbook in the root [RECOVERY.md](file:///C:/Users/user/.gemini/antigravity/scratch/ame-bazaar-git/RECOVERY.md).
