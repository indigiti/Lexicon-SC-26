# Deploying v0.4.0 under `/v/0.4.0/`

Extract the deployment ZIP contents directly into the server directory mapped to:

`https://lexicon.digiti.in/v/0.4.0/`

Do not create another nested `Lexicon-Group-Website-v0.4.0/` directory.

## New academics routes

```text
/v/0.4.0/schools/wagholi/academics
/v/0.4.0/schools/wagholi/academics/calendar
/v/0.4.0/schools/wagholi/academics/assessment
/v/0.4.0/schools/wagholi/academics/holidays

/v/0.4.0/schools/kalyani-nagar/academics
/v/0.4.0/schools/kalyani-nagar/academics/calendar
/v/0.4.0/schools/kalyani-nagar/academics/assessment
/v/0.4.0/schools/kalyani-nagar/academics/holidays

/v/0.4.0/schools/hadapsar/academics
/v/0.4.0/schools/hadapsar/academics/calendar
/v/0.4.0/schools/hadapsar/academics/assessment
/v/0.4.0/schools/hadapsar/academics/holidays
```

## Calendar feeds

```text
/v/0.4.0/api/calendar/wagholi.ics
/v/0.4.0/api/calendar/kalyani-nagar.ics
/v/0.4.0/api/calendar/hadapsar.ics
```

## Health check

`/v/0.4.0/api/health` should return version `0.4.0`.

## Writable path

`storage/` must remain writable by PHP for the current file-backed enquiry endpoint while staying inaccessible from the browser.

## Cache

After replacing an earlier staging build, purge Cloudways/Nginx/CDN/application cache and hard-refresh so `app.css?v=0.4.0` and `app.js?v=0.4.0` are served.
