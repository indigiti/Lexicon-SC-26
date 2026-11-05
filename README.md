# Lexicon Group Website v0.4.0

**Campus Academics & Calendar Engine** for The Lexicon Schools.

## Runtime

- PHP 8.x SSR/front controller
- Vite + TypeScript + Tailwind source
- compiled browser-safe ES2020 JavaScript/CSS included
- JSON content layer
- FastAPI/Python service boundary retained
- no database
- no Redis

## New campus academics routes

Each campus now has four first-class academic routes:

```text
/schools/{campus}/academics
/schools/{campus}/academics/calendar
/schools/{campus}/academics/assessment
/schools/{campus}/academics/holidays
```

Supported campuses:

- `wagholi`
- `kalyani-nagar`
- `hadapsar`

### Academics Hub

- Academic Pulse with upcoming dates
- Family Lens (Early Years / I–V / VI–VIII / IX–XII)
- next holiday
- assessment gateway
- transport connection
- source-document links
- downloadable campus `.ics` calendar

### Academic Planner

- month selector
- event-type filtering
- family-stage filtering
- desktop month-grid layout
- mobile agenda layout
- event categories for assessments, PTMs, holidays, breaks, openings, orientations and major events

### Assessment & Evaluation

- class-group assessment pattern
- 20-mark internal assessment breakdown
- portfolio structure
- subject enrichment
- co-scholastic areas
- Senior Secondary assessment patterns
- Hadapsar-specific promotion and grading information

### Holidays & Breaks

- responsive annual holiday list
- major break cards
- next-holiday callout
- source-integrity notes when supplied documents conflict
- official source PDF download where valid

## Academic source integrity

The website does not silently reconcile contradictory source files.

- `wagholi holidays.pdf` was **not** published as a Wagholi holiday source because the rendered document identifies itself as **The Lexicon School, Hadapsar**.
- Kalyani Nagar and Hadapsar supplied planner/holiday documents contain a conflicting Eid-al-Adha date. That date is omitted from the public web summary pending campus confirmation.
- Wagholi holiday summary is marked as planner-derived until a correct campus-specific holiday-list PDF is supplied.

## Calendar sync

Campus `.ics` feeds:

```text
/api/calendar/wagholi.ics
/api/calendar/kalyani-nagar.ics
/api/calendar/hadapsar.ics
```

These can be imported into Apple Calendar, Google Calendar and Outlook.

## Campus navigation

The approved floating campus ribbon remains the campus-level navigation pattern. **Learning** now opens the campus Academics Hub, while Transport and Visit remain directly accessible.

## QA summary

- 35 PHP files linted: PASS
- TypeScript `tsc --noEmit`: PASS
- production JavaScript `node --check`: PASS
- 10 content JSON files parsed: PASS
- 41 public SSR routes crawled: 0 broken
- 75 local asset references crawled: 0 missing
- duplicate IDs: 0
- `/v/0.4.0` root-path leaks: 0
- build/public CSS mirrors: byte-identical
- build/public JS mirrors: byte-identical
- supplied planner/assessment PDFs copied as source-linked public documents where valid

See `audit/V040/`.

## Staging deployment

Extract the deployment ZIP directly into the directory mapped to:

`https://lexicon.digiti.in/v/0.4.0/`

Do not create an additional nested release directory.

## Team website review (pending PR #1)

The pending team-review branch adds:

- Full campus names, CBSE affiliation codes, UDISE numbers, and verified milestone dates.
- Parent-feedback photo treatments using **campus photos, not invented reviewer portraits**.
- Data-backed admissions comparison with links to authoritative campus sources; removes unsupported school strength, ratio, stream and travel-time claims.
- Editable admissions imagery and copy in `content/admissions-banner.json` (Git-managed editing; **not** a browser CMS).
- Campus gallery entries in `content/campus-galleries.json`; only approved assets are rendered.
- Opt-in school song in `content/school-song.json`, disabled until lyrics or an official YouTube URL are supplied.
- Alumni profiles in `content/alumni-profiles.json`, rendered only with `approved: true`, `consent_confirmed: true`, and a non-empty transcript. The LinkedIn group button requires a verified group URL.
- Existing-parent ERP chooser at `/parent-portal`. Set a vetted HTTPS `erpUrl` in each campus entry in `content/schools.json` before login buttons are offered. The website never collects ERP credentials.
- Enquiry records validated against school slugs and tagged with `route_campus`; no automatic delivery to campus systems or central CRM is claimed.
- Careers entry under the Contact menu, and parent feedback on Achievements.
- Automated PR review syntax, JSON, compiled asset and route checks.

### School-confirmation / integration blockers

1. Supply current ERP portal URLs from each campus administrator (do not guess them).
2. Confirm school email addresses, opening hours, fees, grade-wise concessions and transport arrangements before public publication.
3. Supply approved photographs for labs, buses, library, grounds, auditorium, classrooms, educators and staff for each **specific** campus. Do not interchange campuses or claim an unverified photo shows a specific facility.
4. Provide source references, dates, and authorised imagery for MI FEST, Fuel to Fly, Kiran Bedi's visit and student-authored books before publishing as verified highlights. See `content/editorial-queue.json`.
5. Obtain reviewer attribution and permission for testimonial portraits and for reuse in advertisements; campus pictures are merely illustrations.
6. Provide the approved official school song lyrics and/or verified YouTube link.
7. Provide verified alumni interviews, consented photos, transcripts, and official LinkedIn group URL.
8. To send leads automatically to individual schools and grant central staff controlled access, integrate authenticated sCRM tenant routing, an outbound queue, delivery acknowledgements and campus-specific RBAC. The current JSON/NDJSON website storage is **not** an email or CRM distribution service.

The campus mandatory disclosure repository and mandatory legal links remain available. Only the promotional About-page disclosure card was removed.

