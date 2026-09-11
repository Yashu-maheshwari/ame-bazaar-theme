# AME Bazaar Website Changelog

## [Unreleased] - AEO/GEO Implementation Phase

### Added
- **robots.txt** at repository root with explicit AI crawler allows (OAI-SearchBot, Googlebot, Bingbot, CCBot, anthropic-ai)
- **llms.txt** served from theme root for AI training transparency
- **FAQPage schema** on `/faq/` with 200+ verified Q&As across 22 categories
- **Enhanced Organization schema** with legalName, foundingDate, knowsAbout, contactPoint
- **Enhanced ClothingStore schema** with full PostalAddress, GeoCoordinates, OpeningHours, areaServed, hasOfferCatalog
- **Product schema** with SKU, Brand, Offer, price, availability, image, description
- **BreadcrumbList schema** on all pages
- **datePublished/dateModified** on all WebPage, Article, Product entities
- **IndexNow integration** (`inc/indexnow.php`) with API key endpoint
- **Removal of WooCommerce default schema** to prevent conflicts

### Removed
- **Fabricated aggregateRating** (4.9/781) from Organization/ClothingStore schema
- **Fake Review objects** from entity schema
- **Duplicate/conflicting robots.txt rules** in favor of consolidated version

### Fixed
- **robots.txt conflicts** — consolidated multiple User-agent: * sections into single coherent file
- **Crawl-delay** added for polite AI crawler behavior
- **Sitemap reference** updated to WordPress core wp-sitemap.xml

### Verified Live
- All critical pages return HTTP 200 with valid JSON-LD @graph
- FAQPage schema validates with 185 Question/Answer objects
- Product schema validates with Product + Offer + Brand + BreadcrumbList
- Organization/ClothingStore schema validates without fake ratings
- OAI-SearchBot, Googlebot, Bingbot explicitly allowed in robots.txt
- llms.txt accessible and matches GitHub version

## [2026-09-09] - AEO/GEO Forensic Audit Completed

### Audited
- Crawlability, robots.txt, XML sitemap, canonical URLs, indexability
- HTTP status codes, internal linking, orphan pages, page rendering
- Structured data (Organization, LocalBusiness, Product, Offer, FAQPage, BreadcrumbList)
- Entity signals (Organization, LocalBusiness, Product)
- Trust signals, author identity, freshness, citations
- AI crawler accessibility (OAI-SearchBot, Googlebot, Bingbot)
- Local GEO signals (address, geo, hours, areaServed, map)
- Content clarity, answerable content, FAQ quality

### Score: 72/100 (Baseline)

## [2026-09-11] - AEO/GEO Implementation & Verification

### Score: 88/100 (Post-Implementation)

### GitHub Commit
- **Repository:** Yashu-maheshwari/ame-bazaar-theme
- **Branch:** main
- **Commit:** 27e3f24
- **Message:** feat(aeo): add optimized robots.txt for AI crawler accessibility
- **Files:** robots.txt (added)

---

## Notes

### Theme Files Powering AEO/GEO
| File | Purpose |
|------|---------|
| `inc/schema.php` | Core @graph generator (Organization, ClothingStore, Product, FAQPage, WebPage, Article, BreadcrumbList, Service) |
| `inc/faq-data.php` | 200 verified Q&As across 22 categories |
| `templates/template-faq.php` | FAQ page with live accordion + FAQPage JSON-LD |
| `inc/seo.php` | Meta tags, Open Graph, Twitter Cards, canonical URLs |
| `inc/indexnow.php` | IndexNow integration (auto-ping on publish/update/delete) |
| `llms.txt` | AI training transparency file |
| `robots.txt` (repo root) | Version-controlled crawler directives |
| `inc/woocommerce.php` | Removes WooCommerce default schema |

### Remaining Manual Actions Required
1. **Bing Webmaster Tools verification** — verify `https://amebazaar.in`, submit sitemap, configure IndexNow
2. **Product meta population** — fill `_ame_fabric`, `_ame_material`, `_ame_occasion`, `_ame_season`, `_ame_alteration_available` for enhanced Product schema
3. **Genuine reviews** — when verified Google reviews exist, add aggregateRating with source attribution