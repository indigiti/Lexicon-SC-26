<?php
$transport = content('transport');
$schools = content('schools');
$campuses = $transport['campuses'] ?? [];
$selectedSlug = $schoolSlug ?? '';
$selected = $selectedSlug !== '' ? ($campuses[$selectedSlug] ?? null) : null;
$schoolMap = [];
foreach ($schools as $s) { $schoolMap[$s['slug']] = $s; }
$isGroup = $selectedSlug === '';
$totalRoutes = 0; $totalStops = 0;
foreach ($campuses as $campus) { $totalRoutes += (int)($campus['route_count'] ?? 0); $totalStops += (int)($campus['stop_count'] ?? 0); }
$titleCampus = $isGroup ? 'The Lexicon Schools' : ($schoolMap[$selectedSlug]['name'] ?? $selected['school'] ?? 'The Lexicon Schools');
$shortCampus = $isGroup ? 'All three campuses' : ($schoolMap[$selectedSlug]['short'] ?? ucfirst($selectedSlug));
$heroRoutes = $isGroup ? $totalRoutes : (int)($selected['route_count'] ?? 0);
$heroStops = $isGroup ? $totalStops : (int)($selected['stop_count'] ?? 0);
$visibleCampuses = $isGroup ? $campuses : [$selectedSlug => $selected];
?>
<section class="transport-hero">
  <div class="container transport-hero-grid">
    <div class="transport-hero-copy">
      <div class="eyebrow">Transport Routes · <?= e($transport['academic_year'] ?? '2026–27') ?></div>
      <h1><?= $isGroup ? 'Find the school route that serves your area.' : 'Find your stop. Understand your route.' ?></h1>
      <p><?= $isGroup ? 'Search the current sanitized route directory across Wagholi, Kalyani Nagar and Hadapsar. Choose a campus or search by locality, society or stop name.' : 'Search the current ' . e($shortCampus) . ' route directory by locality, society or stop name. Route availability is indicative and should be confirmed with the campus transport team.' ?></p>
      <div class="transport-kpis" aria-label="Transport summary"><div><strong><?= e($heroRoutes) ?></strong><span>Routes</span></div><div><strong><?= e($heroStops) ?></strong><span>Stops</span></div><div><strong><?= e($transport['academic_year'] ?? '2026–27') ?></strong><span>Academic Year</span></div></div>
    </div>
    <div class="transport-hero-panel">
      <span class="transport-panel-label">Quick Finder</span>
      <label for="transport-search">Search locality, society or stop</label>
      <div class="transport-search-wrap"><svg aria-hidden="true" viewBox="0 0 24 24"><path d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg><input id="transport-search" type="search" placeholder="e.g. Kharadi, Dhanori, Pride World City" autocomplete="off" data-transport-search></div>
      <div class="transport-filter-grid">
        <?php if ($isGroup): ?><label>Campus<select data-transport-campus-filter><option value="all">All campuses</option><?php foreach ($schools as $s): ?><option value="<?= e($s['slug']) ?>"><?= e($s['short']) ?></option><?php endforeach; ?></select></label><?php endif; ?>
        <label>Route<select data-transport-route-filter><option value="all">All routes</option><?php foreach ($visibleCampuses as $slug => $campus): foreach (($campus['routes'] ?? []) as $route): ?><option value="<?= e($slug . '||' . $route['name']) ?>"><?= $isGroup ? e(($schoolMap[$slug]['short'] ?? $slug) . ' · ') : '' ?><?= e($route['name']) ?></option><?php endforeach; endforeach; ?></select></label>
      </div>
      <div class="transport-search-status"><strong data-transport-count><?= e($heroStops) ?></strong><span> matching stops</span></div>
    </div>
  </div>
</section>

<?php if ($isGroup): ?>
<section class="transport-campus-choice"><div class="container">
  <div class="section-head-row"><div><div class="eyebrow">Choose a Campus</div><h2 class="section-title">Three school communities. One route directory.</h2></div><p class="section-intro">Open a campus-specific transport page for a focused route list and campus enquiry action.</p></div>
  <div class="transport-campus-grid"><?php foreach ($schools as $school): $slug=$school['slug']; $campus=$campuses[$slug] ?? []; ?><a href="/schools/<?= e($slug) ?>/transport"><img src="<?= e($school['image']) ?>" alt="<?= e($school['name']) ?>" loading="lazy"><div><small><?= e($school['board']) ?> · Transport 2026–27</small><h3><?= e($school['short']) ?></h3><p><?= e($campus['route_count'] ?? 0) ?> routes · <?= e($campus['stop_count'] ?? 0) ?> stops</p><span>Explore routes →</span></div></a><?php endforeach; ?></div>
</div></section>
<?php endif; ?>

<section class="transport-directory" id="routes"><div class="container">
  <div class="section-head-row"><div><div class="eyebrow"><?= $isGroup ? 'Route Directory' : e($shortCampus) . ' Route Directory' ?></div><h2 class="section-title">Search first, then expand the route you need.</h2></div><p class="section-intro">Stop order follows the sanitized 2026–27 source workbook. Vehicle registration numbers and departure times are intentionally not published.</p></div>
  <div class="transport-empty" data-transport-empty hidden><strong>No matching stops found.</strong><span>Try a broader locality, society name, route number or another campus.</span></div>
  <div class="transport-campus-groups">
  <?php foreach ($visibleCampuses as $slug => $campus): if (!$campus) continue; $school=$schoolMap[$slug] ?? []; ?>
    <section class="transport-campus-group" data-transport-campus="<?= e($slug) ?>">
      <div class="transport-campus-heading"><div><span><?= e($school['name'] ?? $campus['school'] ?? '') ?></span><h3><?= e($campus['route_count'] ?? 0) ?> routes · <?= e($campus['stop_count'] ?? 0) ?> stops</h3></div><?php if ($isGroup): ?><a href="/schools/<?= e($slug) ?>/transport">Campus transport page →</a><?php else: ?><a href="/enquire?campus=<?= e($slug) ?>&amp;reason=transport">Ask about transport →</a><?php endif; ?></div>
      <div class="transport-route-list">
      <?php foreach (($campus['routes'] ?? []) as $route): ?>
        <details class="transport-route" data-transport-route data-campus="<?= e($slug) ?>" data-route="<?= e($route['name']) ?>">
          <summary><div><span><?= e($route['name']) ?></span><small><?= e($route['stop_count']) ?> stops</small></div><span class="transport-route-open" aria-hidden="true">+</span></summary>
          <div class="transport-route-body">
            <div class="transport-table-wrap" role="region" tabindex="0" aria-label="<?= e(($school['short'] ?? $slug) . ' ' . $route['name'] . ' stops') ?>"><table><thead><tr><th>Stop No.</th><th>Stop / Locality</th><th>Service</th></tr></thead><tbody>
            <?php foreach (($route['stops'] ?? []) as $stop): $searchText=trim(($stop['name'] ?? '') . ' ' . ($stop['service_note'] ?? '') . ' ' . $route['name'] . ' ' . ($school['short'] ?? '')); ?><tr data-transport-stop data-search="<?= e($searchText) ?>"><td><?= e(str_pad((string)$stop['sequence'],2,'0',STR_PAD_LEFT)) ?></td><td><strong><?= e($stop['name']) ?></strong></td><td><?php if (!empty($stop['service_note'])): ?><span class="transport-badge"><?= e($stop['service_note']) ?></span><?php else: ?><span class="transport-muted">—</span><?php endif; ?></td></tr><?php endforeach; ?>
            </tbody></table></div>
            <div class="transport-stop-cards"><?php foreach (($route['stops'] ?? []) as $stop): $searchText=trim(($stop['name'] ?? '') . ' ' . ($stop['service_note'] ?? '') . ' ' . $route['name'] . ' ' . ($school['short'] ?? '')); ?><article data-transport-stop data-search="<?= e($searchText) ?>"><span><?= e(str_pad((string)$stop['sequence'],2,'0',STR_PAD_LEFT)) ?></span><div><strong><?= e($stop['name']) ?></strong><?php if (!empty($stop['service_note'])): ?><small class="transport-badge"><?= e($stop['service_note']) ?></small><?php endif; ?></div></article><?php endforeach; ?></div>
          </div>
        </details>
      <?php endforeach; ?>
      </div>
    </section>
  <?php endforeach; ?>
  </div>
</div></section>

<section class="transport-note-section"><div class="container transport-note-grid">
  <div><div class="eyebrow">Important Transport Note</div><h2 class="section-title">Routes are indicative, not a guarantee of service.</h2><p>Stops and routes may change based on enrolment, road conditions, transport operations and school policy. Confirm the current stop, route and availability with the relevant campus before making travel arrangements.</p></div>
  <aside><strong>Can't find your area?</strong><p>Send the campus team your locality and child's class. They can confirm the closest current option or advise whether transport is available.</p><div class="card-actions"><?php if ($isGroup): ?><a class="btn btn-lexicon" href="/enquire?reason=transport">Enquire About Transport</a><a class="btn btn-outline-lexicon" href="/contact">Contact Campuses</a><?php else: ?><a class="btn btn-lexicon" href="/enquire?campus=<?= e($selectedSlug) ?>&amp;reason=transport">Enquire About Transport</a><a class="btn btn-outline-lexicon" href="/book-a-visit?campus=<?= e($selectedSlug) ?>">Book a Visit</a><?php endif; ?></div></aside>
</div></section>
