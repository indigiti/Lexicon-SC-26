# Lexicon Group Website v0.4.0 Architecture

```text
Browser (/ or /v/0.4.0/ or another subpath)
  ↓
Apache rewrite / public front controller
  ↓
PHP 8.x SSR + routing
  ↓
Shared Group Header
  ↓
Campus Identity Navigation (campus routes only)
  ↓
Vite-built TypeScript + Tailwind assets
  ↓
JSON content / PHP API
  ↓
FastAPI / Python services (boundary prepared)
  ↓
Database / Redis / external APIs (future)
```

## Campus navigation layer

`app/Views/components/campus-nav.php` is rendered only for `/schools/{campus}` routes. The active campus is inferred from the request path. Campus logo assets live under:

```text
/assets/images/brand/campuses/
  wagholi-light.png
  kalyani-nagar-light.png
  hadapsar-light.png
```

The Group header remains canonical and unchanged. The campus bar adds local navigation, a campus switcher and an Apply CTA without duplicating the whole site header.

## Admissions content model

```text
content/wagholi-admissions.json
content/kalyani-admissions.json
content/hadapsar-admissions.json
```

Wagholi keeps its dedicated existing templates. Kalyani Nagar and Hadapsar use shared data-driven views:

```text
app/Views/pages/campus-admissions.php
app/Views/pages/campus-class-xi.php
```

This avoids three separate copies of the same admission UI while preserving campus-specific fees, streams, cut-offs, policies and documents.

## URL portability

No deployment path is hard-coded. `base_path()` derives the installation prefix and `apply_base_path_to_html()` prefixes same-site root-relative URLs. TypeScript reads the same base from `data-base-path`.

## Form/API boundary

Public enquiry and visit forms continue to post to:

```text
POST /api/enquiry
```

Data remains file-backed under protected `storage/` because DB/Redis are intentionally not enabled yet.

## Transport data pipeline

```text
Sanitized XLSX source
        ↓
Build/import transformation
        ↓
content/transport.json
        ↓
PHP SSR route directory
        ↓
TypeScript local search/filter
```

The transport module intentionally does not require a database or Redis. Stop names are not geocoded automatically; a future map view should use verified coordinates only.

## v0.4.0 Campus Academics Engine

Campus academic content remains data-driven and DB-free:

```text
Campus PDF source documents
        ↓
reviewed / curated structured JSON
        ↓
content/academics.json
        ↓
PHP SSR campus Academics views
        ↓
TypeScript filters + local Family Lens preference
        ↓
responsive calendar / assessment / holiday UI
        ↓
optional .ics calendar export
```

The PDFs are not parsed at runtime. Source inconsistencies are recorded in the structured content and audit notes rather than silently corrected.
