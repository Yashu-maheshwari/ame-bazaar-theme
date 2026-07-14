# PROJECT_CONTEXT.md - System Overview

- **Last Updated:** 2026-07-14
- **Version:** 1.0.0
- **Owner:** AME Bazaar AI OS Core
- **Purpose:** High-level project summary to onboard any new AI agent or developer in under five minutes.
- **Dependencies:** None
- **Status:** Active

---

## 1. Executive Summary
- **Client:** Apparel Maheshwari Enterprises (AME Bazaar)
- **Business Type:** Premium Offline Family Garment Retail Store (Men, Women, Kids)
- **Location:** Kirari, Delhi, India
- **Core Technology:** WordPress (CMS) + Astra Child Theme + PHP 8+ + HTML5/CSS3 + Vanilla JS
- **Core Strategy:** Drive local foot traffic to the physical store using search visibility (SEO & AI Search), transition into WooCommerce e-commerce, and lay foundations for private labels and franchise models.

## 2. Directory Layout & Architecture
This repository is configured as a local execution state ("AI Operating System") for AI development:
- `/docs`: Single source of truth documents (Master Plan, Context, Changelogs, Decisions).
- `/workflows`: Logical automated tasks and steps (Marketing, Sales, Customer Service).
- `/prompts`: Structured system and specialized prompt files for AI agent runs.
- `/memory`: Local JSON files storing configuration and dynamic settings.
- `/scripts`: Shell/PowerShell scripts for backup, recovery, startup, and shutdown.
- `/theme/astra-child/`: Custom child theme containing Astra CSS styles and layout custom functions.

## 3. High-Priority Constraints (Read Before Writing Code)
1. **No Javascript Frameworks:** React, Next.js, Vue, SPAs, and heavy frontend state libraries are strictly banned.
2. **Mobile First:** Over 80% of customer traffic is mobile. Styling must prioritize viewport scales.
3. **No Placeholders:** Code, pages, or content must be production-ready and functional. No "lorem ipsum" or dummy routes.
4. **Git Branch Workflow:** Commit only to `feature/<name>` branches and open Draft Pull Requests for human review.
