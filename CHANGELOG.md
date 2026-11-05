# Changelog

## v0.4.0 — Campus Academics & Calendar Engine

- Added a campus Academics Hub for Wagholi, Kalyani Nagar and Hadapsar.
- Added 12 new campus routes: Academics Hub, Academic Planner, Assessment & Evaluation and Holiday Calendar for all three campuses.
- Converted supplied 2026–27 planner content into structured `content/academics.json` with 138 curated key events across the three schools.
- Added Academic Pulse cards showing upcoming key dates on campus Academics pages and campus overview pages.
- Added Family Lens preferences (Early Years / I–V / VI–VIII / IX–XII), stored locally on the visitor device.
- Added interactive month/category/family filters, desktop calendar grid and mobile agenda presentation.
- Added downloadable `.ics` campus calendars for Apple Calendar, Google Calendar and Outlook.
- Added class-group assessment views, internal-assessment breakdown, portfolio breakdown, subject enrichment and Hadapsar grading/promotion information.
- Added responsive holiday and break timelines with explicit source-integrity notes rather than silently reconciling conflicting source files.
- Published the supplied campus planner/assessment/holiday PDFs as source-linked downloads where valid.
- Deliberately excluded the file uploaded as `wagholi holidays.pdf` from the Wagholi public source set because the rendered document identifies itself as The Lexicon School, Hadapsar.
- Flagged Kalyani Nagar and Hadapsar Eid-al-Adha source conflicts rather than guessing a date.
- Added Learning → Academics routing in the floating campus ribbon.
- Preserved PHP SSR + TypeScript/Vite/Tailwind + JSON architecture with no DB/Redis dependency.

## v0.3.9 — Transport Route Finder

- Added Group Transport route directory and campus-specific transport pages.
- Imported sanitized 2026–27 transport data: 49 routes / 353 stops.
- Added search by locality/society/stop, campus filter, route filter, matching-stop counter and responsive route accordions.
- Added desktop table and mobile stop-card presentations.
- Preserved Pickup only / Drop only service notes.
- Added Transport to campus floating ribbon / mobile swipe navigation.
- Added transport CTAs to all three campus overview and admissions pages.
- Kept operational vehicle registration numbers and route/departure times out of public data.
- Added XLSX → JSON source pipeline; no database/Redis introduced.

## v0.3.8 — Floating Campus Ribbon + Mobile Swipe Navigation

- Applied the approved Style 3 campus header direction across Wagholi, Kalyani Nagar and Hadapsar campus routes.
- Converted the campus-level bar into a floating, rounded, elevated ribbon below the global Group header on desktop.
- Replaced the previous tablet/mobile campus drawer with an always-visible horizontal swipe rail.
- Added scroll snapping, momentum scrolling, hidden scrollbars, edge-fade affordance and automatic centering of the active campus tab.

## v0.3.7 — Campus Navigation + Kalyani Nagar / Hadapsar Admissions

- Added responsive campus-level navigation and official light campus logo assets.
- Added Kalyani Nagar and Hadapsar 2026–27 Admissions Guide and Class XI pathways.
- Added campus-specific fee, subject, document and current Class XI annexure information.

## v0.3.6 — Wagholi Admissions 2026–27 + Class XI

- Added the dedicated Wagholi admissions guide and Class XI pathway.
- Rebuilt the useful Wagholi Class XI annexure content as responsive web sections while excluding outdated prior-year fee/incentive values.

## v0.3.5 — Campus Tour, Virtual Tour & Conversion Journeys

- Added Campus Tour, Virtual Tour, Apply Now, Enquire and Book a Visit journeys.

## v0.3.4 — Campus School Pages

- Added full data-driven Wagholi, Kalyani Nagar and Hadapsar campus pages.

## v0.3.3 — Alumni, Careers, Contact Maps & Legal Pages

- Added full Alumni, Careers, Contact, Privacy, Terms and Disclosure pages.

## v0.3.2 — Runtime & Scroll Recovery + Visual Re-Audit

- Recovered browser-safe JavaScript, scrolling and mobile navigation.

## v0.3.1 — Subdirectory Deployment Hardening

- Removed domain-root dependency from routing, assets, links and API calls.

## v0.3.0 — Architecture Migration

- Migrated to PHP SSR + Vite/TypeScript/Tailwind source with JSON content.
