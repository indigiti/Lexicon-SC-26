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
