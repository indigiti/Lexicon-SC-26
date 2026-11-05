<?php
$academicsAll = content('academics');
$campus = $academicsAll['campuses'][$schoolSlug] ?? [];
$schools = content('schools');
$school = [];
foreach ($schools as $candidate) {
    if (($candidate['slug'] ?? '') === $schoolSlug) { $school = $candidate; break; }
}
$mode = $academicsMode ?? 'hub';
$base = '/schools/' . $schoolSlug . '/academics';
$events = $campus['events'] ?? [];
usort($events, static fn(array $a, array $b): int => strcmp((string)($a['date'] ?? ''), (string)($b['date'] ?? '')));
$holidays = $campus['holidays'] ?? [];
usort($holidays, static fn(array $a, array $b): int => strcmp((string)($a['date'] ?? ''), (string)($b['date'] ?? '')));
$today = new DateTimeImmutable('today');
$academicStart = new DateTimeImmutable('2026-04-01');
$academicEnd = new DateTimeImmutable('2027-04-30');
$referenceDate = $today < $academicStart ? $academicStart : ($today > $academicEnd ? $academicStart : $today);

$fmt = static function (string $date, string $format = 'd M Y'): string {
    try { return (new DateTimeImmutable($date))->format($format); } catch (Throwable) { return $date; }
};
$catLabel = static fn(string $key): string => (string)($academicsAll['categories'][$key]['label'] ?? ucfirst($key));
$upcoming = array_values(array_filter($events, static fn(array $e): bool => ($e['date'] ?? '') >= $referenceDate->format('Y-m-d')));
$upcoming = array_slice($upcoming, 0, 5);
$nextHoliday = null;
foreach ($holidays as $holiday) {
    if (($holiday['date'] ?? '') >= $referenceDate->format('Y-m-d')) { $nextHoliday = $holiday; break; }
}
if (!$nextHoliday && $holidays) $nextHoliday = $holidays[0];

$monthStart = new DateTimeImmutable('2026-04-01');
$monthEnd = new DateTimeImmutable('2027-04-01');
$months = [];
for ($m = $monthStart; $m <= $monthEnd; $m = $m->modify('+1 month')) $months[] = $m;
$currentMonthKey = $referenceDate->format('Y-m');
if ($referenceDate < $monthStart || $referenceDate > $monthEnd->modify('last day of this month')) $currentMonthKey = '2026-04';

$doc = $campus['documents'] ?? [];
$schoolName = $campus['name'] ?? ($school['name'] ?? ucfirst($schoolSlug));
$short = $school['short'] ?? ucfirst(str_replace('-', ' ', $schoolSlug));
?>
<section class="academics-hero">
  <div class="container academics-hero-grid">
    <div class="academics-hero-copy">
      <div class="eyebrow"><?= e($short) ?> · Academic Year <?= e($campus['academic_year'] ?? '2026–27') ?></div>
      <h1><?php if ($mode==='calendar'): ?>The school year, made easier to follow.<?php elseif ($mode==='assessment'): ?>Assessment, explained clearly.<?php elseif ($mode==='holidays'): ?>Plan around holidays and breaks.<?php else: ?>Learning has a rhythm. See the year at a glance.<?php endif; ?></h1>
      <p><?= e($campus['intro'] ?? '') ?></p>
      <div class="hero-actions">
        <?php if (!empty($doc['planner'])): ?><a class="btn btn-lexicon" href="<?= e($doc['planner']) ?>" target="_blank" rel="noopener">Official Planner PDF ↗</a><?php endif; ?>
        <a class="btn btn-outline-lexicon" href="<?= e($base) ?>/calendar">Open Academic Calendar</a>
      </div>
    </div>
    <aside class="academic-pulse-card" aria-label="Academic pulse">
      <span class="academic-pulse-label">Academic Pulse</span>
      <strong><?= e($fmt($referenceDate->format('Y-m-d'),'l, d F')) ?></strong>
      <small><?= e($schoolName) ?></small>
      <div class="academic-pulse-list">
        <?php foreach (array_slice($upcoming,0,3) as $event): ?>
          <a href="<?= e($base) ?>/calendar?month=<?= e(substr((string)$event['date'],0,7)) ?>">
            <time datetime="<?= e($event['date']) ?>"><b><?= e($fmt($event['date'],'d')) ?></b><span><?= e(strtoupper($fmt($event['date'],'M'))) ?></span></time>
            <span><em><?= e($catLabel((string)($event['category'] ?? 'event'))) ?></em><strong><?= e($event['title'] ?? '') ?></strong></span>
          </a>
        <?php endforeach; ?>
      </div>
    </aside>
  </div>
</section>

<nav class="academic-subnav" aria-label="Academic resources"><div class="container">
  <a class="<?= $mode==='hub'?'active':'' ?>" href="<?= e($base) ?>">Academics Hub</a>
  <a class="<?= $mode==='calendar'?'active':'' ?>" href="<?= e($base) ?>/calendar">Academic Planner</a>
  <a class="<?= $mode==='assessment'?'active':'' ?>" href="<?= e($base) ?>/assessment">Assessment</a>
  <a class="<?= $mode==='holidays'?'active':'' ?>" href="<?= e($base) ?>/holidays">Holidays</a>
</div></nav>

<?php if ($mode === 'hub'): ?>
<section class="academic-hub-section"><div class="container">
  <div class="section-head-row"><div><div class="eyebrow">Your Academic Dashboard</div><h2 class="section-title">One place for the dates families check most.</h2></div><p class="section-intro">The website translates long PDF planners into a responsive parent view while retaining links to the supplied source documents.</p></div>
  <div class="academic-dashboard-grid">
    <article class="academic-dashboard-card academic-dashboard-feature">
      <span>Next up</span>
      <?php $first=$upcoming[0]??null; if($first): ?>
      <time datetime="<?= e($first['date']) ?>"><?= e($fmt($first['date'],'D · d M')) ?></time>
      <h3><?= e($first['title'] ?? '') ?></h3>
      <p><?= e($first['description'] ?? 'A key date from the campus academic planner.') ?></p>
      <a href="<?= e($base) ?>/calendar?month=<?= e(substr((string)$first['date'],0,7)) ?>">View in planner →</a>
      <?php endif; ?>
    </article>
    <article class="academic-dashboard-card">
      <span>Next holiday</span>
      <?php if($nextHoliday): ?><time datetime="<?= e($nextHoliday['date']) ?>"><?= e($fmt($nextHoliday['date'],'d M Y')) ?></time><h3><?= e($nextHoliday['title']) ?></h3><?php endif; ?>
      <a href="<?= e($base) ?>/holidays">View holiday calendar →</a>
    </article>
    <article class="academic-dashboard-card">
      <span>Assessment system</span><h3>Know what counts.</h3><p>Term structure, internal assessment, portfolio, subject enrichment and Senior Secondary patterns.</p><a href="<?= e($base) ?>/assessment">Understand assessment →</a>
    </article>
    <article class="academic-dashboard-card">
      <span>Calendar sync</span><h3>Take key dates with you.</h3><p>Download the curated campus calendar as an .ics file for Apple Calendar, Google Calendar or Outlook.</p><a href="/api/calendar/<?= e($schoolSlug) ?>.ics">Download calendar (.ics) ↓</a>
    </article>
  </div>
</div></section>

<section class="academic-family-section"><div class="container academic-family-grid">
  <div>
    <div class="eyebrow">Family Lens</div><h2 class="section-title">Show me what matters to my child.</h2><p>Select a learning stage. The planner remembers the preference on this device and prioritises matching dates.</p>
    <div class="family-filter" data-family-filter-group>
      <button type="button" data-family-filter="all" class="active">All School</button>
      <button type="button" data-family-filter="pre-primary">Early Years</button>
      <button type="button" data-family-filter="primary">Classes I–V</button>
      <button type="button" data-family-filter="middle">Classes VI–VIII</button>
      <button type="button" data-family-filter="senior">Classes IX–XII</button>
    </div>
  </div>
  <div class="family-upcoming-list" data-family-event-list>
    <?php foreach (array_slice($upcoming,0,8) as $event): $aud=implode(' ',(array)($event['audience']??['all'])); ?>
      <article data-family-event data-audience="<?= e($aud) ?>"><time><?= e($fmt($event['date'],'d M')) ?></time><div><span><?= e($catLabel($event['category']??'event')) ?></span><h3><?= e($event['title']) ?></h3></div></article>
    <?php endforeach; ?>
  </div>
</div></section>

<section class="academic-pathways"><div class="container"><div class="academic-path-grid">
  <a href="<?= e($base) ?>/calendar"><span>01</span><small>Academic Planner</small><h3>Month view + mobile agenda</h3><p>Assessments, PTMs, events, school openings, breaks and major student experiences.</p><b>Open planner →</b></a>
  <a href="<?= e($base) ?>/assessment"><span>02</span><small>Assessment & Evaluation</small><h3>Understand the pattern</h3><p>Class-group assessment structures, internal marks, portfolio and subject enrichment.</p><b>Explore assessment →</b></a>
  <a href="<?= e($base) ?>/holidays"><span>03</span><small>Holiday Calendar</small><h3>Holidays + major breaks</h3><p>Readable annual list with source-status notes where campus documents need confirmation.</p><b>Plan ahead →</b></a>
  <a href="/schools/<?= e($schoolSlug) ?>/transport"><span>04</span><small>Plan the School Day</small><h3>Transport Route Finder</h3><p>Combine academic dates with the current campus route directory and stop search.</p><b>Find my stop →</b></a>
</div></div></section>

<section class="academic-source-section"><div class="container"><div class="section-head-row"><div><div class="eyebrow">Official Source Documents</div><h2 class="section-title">Web-first, source-linked.</h2></div><p class="section-intro">The responsive experience is derived from campus documents; source PDFs remain available for reference.</p></div><div class="academic-source-grid">
<?php if(!empty($doc['planner'])):?><a href="<?= e($doc['planner']) ?>" target="_blank" rel="noopener"><span>Academic Planner</span><strong>2026–27</strong><b>Open PDF ↗</b></a><?php endif; ?>
<?php if(!empty($doc['assessment'])):?><a href="<?= e($doc['assessment']) ?>" target="_blank" rel="noopener"><span>Assessment Pattern</span><strong>Campus Methodology</strong><b>Open PDF ↗</b></a><?php endif; ?>
<?php if(!empty($doc['holiday'])):?><a href="<?= e($doc['holiday']) ?>" target="_blank" rel="noopener"><span>Holiday List</span><strong>2026–27</strong><b>Open PDF ↗</b></a><?php endif; ?>
</div></div></section>

<?php elseif ($mode === 'calendar'): ?>
<section class="academic-calendar-section" data-academic-calendar><div class="container">
  <div class="academic-tools">
    <div><div class="eyebrow">Interactive Planner</div><h2 class="section-title">Browse the school year.</h2></div>
    <div class="academic-controls">
      <label>Month<select data-academic-month-select><?php foreach($months as $month): $key=$month->format('Y-m'); ?><option value="<?= e($key) ?>" <?= $key===$currentMonthKey?'selected':'' ?>><?= e($month->format('F Y')) ?></option><?php endforeach; ?></select></label>
      <label>Type<select data-academic-category-select><option value="all">All events</option><?php foreach(($academicsAll['categories']??[]) as $key=>$meta): ?><option value="<?= e($key) ?>"><?= e($meta['label']??ucfirst($key)) ?></option><?php endforeach; ?></select></label>
      <label>Family<select data-academic-audience-select><option value="all">All school</option><option value="pre-primary">Early Years</option><option value="primary">Classes I–V</option><option value="middle">Classes VI–VIII</option><option value="senior">Classes IX–XII</option></select></label>
    </div>
  </div>
  <div class="calendar-actions"><a href="/api/calendar/<?= e($schoolSlug) ?>.ics" class="btn btn-outline-lexicon">Download .ics Calendar ↓</a><?php if(!empty($doc['planner'])):?><a href="<?= e($doc['planner']) ?>" target="_blank" rel="noopener" class="text-link">Open Official Planner PDF ↗</a><?php endif; ?></div>

  <?php foreach($months as $month): $monthKey=$month->format('Y-m'); $monthEvents=array_values(array_filter($events,fn($e)=>str_starts_with((string)$e['date'],$monthKey))); $byDate=[]; foreach($monthEvents as $e){$byDate[$e['date']][]=$e;} ?>
  <section class="academic-month-panel" data-academic-month-panel="<?= e($monthKey) ?>" <?= $monthKey!==$currentMonthKey?'hidden':'' ?>>
    <div class="academic-month-head"><h3><?= e($month->format('F Y')) ?></h3><span><?= count($monthEvents) ?> key dates</span></div>
    <div class="academic-month-grid" aria-label="<?= e($month->format('F Y')) ?> calendar">
      <?php foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day): ?><div class="academic-weekday"><?= e($day) ?></div><?php endforeach; ?>
      <?php $firstDow=(int)$month->format('w'); for($i=0;$i<$firstDow;$i++): ?><div class="academic-day empty" aria-hidden="true"></div><?php endfor; ?>
      <?php $days=(int)$month->format('t'); for($d=1;$d<=$days;$d++): $date=$month->format('Y-m-').str_pad((string)$d,2,'0',STR_PAD_LEFT); $dayEvents=$byDate[$date]??[]; ?>
        <div class="academic-day <?= $date===$referenceDate->format('Y-m-d')?'today':'' ?>"><span class="academic-day-num"><?= $d ?></span><div class="academic-day-events">
          <?php foreach($dayEvents as $event): $aud=implode(' ',(array)($event['audience']??['all'])); ?><article class="academic-mini-event cat-<?= e($event['category']??'event') ?>" data-academic-event data-category="<?= e($event['category']??'event') ?>" data-audience="<?= e($aud) ?>"><span><?= e($event['title']) ?></span></article><?php endforeach; ?>
        </div></div>
      <?php endfor; ?>
    </div>
    <div class="academic-agenda-list">
      <?php foreach($monthEvents as $event): $aud=implode(' ',(array)($event['audience']??['all'])); ?><article data-academic-event data-category="<?= e($event['category']??'event') ?>" data-audience="<?= e($aud) ?>"><time datetime="<?= e($event['date']) ?>"><b><?= e($fmt($event['date'],'d')) ?></b><span><?= e($fmt($event['date'],'D')) ?></span></time><div><small><?= e($catLabel($event['category']??'event')) ?></small><h4><?= e($event['title']) ?></h4><?php if(!empty($event['description'])):?><p><?= e($event['description']) ?></p><?php endif; ?></div></article><?php endforeach; ?>
    </div>
  </section>
  <?php endforeach; ?>
  <div class="academic-filter-empty" data-academic-filter-empty hidden>No planner entries match the selected filters for this month.</div>
</div></section>

<?php elseif ($mode === 'assessment'): $assessment=$campus['assessment']??[]; ?>
<section class="assessment-section"><div class="container">
  <div class="section-head-row"><div><div class="eyebrow">Assessment & Evaluation</div><h2 class="section-title">How progress is observed and reported.</h2></div><p class="section-intro"><?= e($assessment['overview']??'') ?></p></div>
  <div class="assessment-tabs" data-assessment-tabs>
    <?php foreach(($assessment['groups']??[]) as $i=>$group): ?><button type="button" data-assessment-tab="<?= e($group['key']) ?>" class="<?= $i===0?'active':'' ?>"><?= e($group['label']) ?></button><?php endforeach; ?>
  </div>
  <div class="assessment-panels">
    <?php foreach(($assessment['groups']??[]) as $i=>$group): ?><article data-assessment-panel="<?= e($group['key']) ?>" <?= $i!==0?'hidden':'' ?>><div><span>Grading / approach</span><h3><?= e($group['grading']??'') ?></h3></div><div class="assessment-term-grid"><section><small>Term / Cycle 1</small><p><?= e($group['term_1']??'') ?></p></section><section><small>Term / Cycle 2</small><p><?= e($group['term_2']??'') ?></p></section></div></article><?php endforeach; ?>
  </div>
  <div class="assessment-detail-grid">
    <article><div class="eyebrow">Internal Assessment</div><h3>20-mark learning evidence</h3><div class="assessment-mark-grid"><?php foreach(($assessment['internal']??[]) as $item): ?><div><strong><?= e($item['marks']) ?></strong><span><?= e($item['label']) ?></span></div><?php endforeach; ?></div></article>
    <article><div class="eyebrow">Portfolio</div><h3>How the 5 marks are built</h3><div class="assessment-mark-grid"><?php foreach(($assessment['portfolio']??[]) as $item): ?><div><strong><?= e($item['marks']) ?></strong><span><?= e($item['label']) ?></span></div><?php endforeach; ?></div></article>
  </div>
  <div class="assessment-enrichment"><div><div class="eyebrow">Subject Enrichment</div><h2 class="section-title">Application, not only recall.</h2></div><div class="assessment-enrichment-list"><?php foreach(($assessment['enrichment']??[]) as $item): ?><span><?= e($item) ?></span><?php endforeach; ?></div></div>
  <?php if(!empty($assessment['promotion'])):?><aside class="assessment-policy-note"><strong>Promotion requirement</strong><p><?= e($assessment['promotion']) ?></p></aside><?php endif; ?>
  <?php if(!empty($assessment['grading_tables'])):?><div class="assessment-grading-wrap"><?php foreach($assessment['grading_tables'] as $key=>$rows): ?><article><h3><?= e(strtoupper($key)) ?> Grading Scale</h3><table><thead><tr><th>Marks</th><th>Grade</th><th>Meaning</th></tr></thead><tbody><?php foreach($rows as $row): ?><tr><td><?= e($row['range']??'') ?></td><td><?= e($row['grade']??'') ?></td><td><?= e($row['label']??'') ?></td></tr><?php endforeach; ?></tbody></table></article><?php endforeach; ?></div><?php endif; ?>
  <div class="assessment-footer-grid"><article><span>Co-Scholastic</span><ul><?php foreach(($assessment['co_scholastic']??[]) as $item): ?><li><?= e($item) ?></li><?php endforeach; ?></ul></article><article><span>Senior Secondary</span><ul><?php foreach(($assessment['senior_co_scholastic']??[]) as $item): ?><li><?= e($item) ?></li><?php endforeach; ?></ul></article><article><span>Discipline</span><p><?= e($assessment['discipline']??'') ?></p></article></div>
  <?php if(!empty($doc['assessment'])):?><div class="academic-document-cta"><div><span>Official source</span><strong>Assessment Pattern</strong></div><a href="<?= e($doc['assessment']) ?>" class="btn btn-outline-lexicon" target="_blank" rel="noopener">Open PDF ↗</a></div><?php endif; ?>
</div></section>

<?php elseif ($mode === 'holidays'): ?>
<section class="holiday-section"><div class="container">
  <div class="holiday-next-grid"><div><div class="eyebrow">Holiday Calendar 2026–27</div><h2 class="section-title">Plan ahead without reading a scanned table.</h2><p>Major holidays and school breaks are presented as responsive web content. Source discrepancies are disclosed rather than silently corrected.</p></div><?php if($nextHoliday): ?><aside><span>Next holiday</span><time><?= e($fmt($nextHoliday['date'],'d F Y')) ?></time><strong><?= e($nextHoliday['title']) ?></strong></aside><?php endif; ?></div>
  <div class="holiday-break-grid"><?php foreach(($campus['breaks']??[]) as $break): ?><article><span><?= e($break['status']??'') ?></span><h3><?= e($break['title']) ?></h3><p><?= e($fmt($break['start'],'d M Y')) ?><?php if(($break['end']??'')!==($break['start']??'')): ?> – <?= e($fmt($break['end'],'d M Y')) ?><?php endif; ?></p></article><?php endforeach; ?></div>
  <div class="holiday-list"><div class="holiday-list-head"><span>Date</span><span>Holiday / Observance</span></div><?php foreach($holidays as $holiday): ?><article><time datetime="<?= e($holiday['date']) ?>"><b><?= e($fmt($holiday['date'],'d')) ?></b><span><?= e($fmt($holiday['date'],'M Y')) ?></span></time><strong><?= e($holiday['title']) ?></strong></article><?php endforeach; ?></div>
  <?php if(!empty($campus['source_notes'])):?><aside class="source-integrity-note"><div class="eyebrow">Source Integrity Notes</div><h3>What the website deliberately does not guess.</h3><ul><?php foreach($campus['source_notes'] as $note): ?><li><?= e($note) ?></li><?php endforeach; ?></ul></aside><?php endif; ?>
  <div class="academic-document-cta"><div><span>Source document</span><strong><?= !empty($doc['holiday'])?'Official Holiday List':'Campus Academic Planner' ?></strong></div><?php if(!empty($doc['holiday'])):?><a href="<?= e($doc['holiday']) ?>" class="btn btn-outline-lexicon" target="_blank" rel="noopener">Open Holiday PDF ↗</a><?php elseif(!empty($doc['planner'])):?><a href="<?= e($doc['planner']) ?>" class="btn btn-outline-lexicon" target="_blank" rel="noopener">Open Planner PDF ↗</a><?php endif; ?></div>
</div></section>
<?php endif; ?>

<section class="signature-cta"><div class="container signature-cta-inner"><div><div class="eyebrow light">Plan the School Year</div><h2>Academic dates, transport and admissions — connected.</h2><p>Move from the campus calendar to transport, admissions or a campus visit without leaving the school context.</p></div><div class="signature-cta-actions"><a class="apply-btn" href="/apply-now?campus=<?= e($schoolSlug) ?>">Apply Now ↗</a><a href="/schools/<?= e($schoolSlug) ?>/transport">Transport Routes</a><a href="/book-a-visit?campus=<?= e($schoolSlug) ?>">Book a Visit</a></div></div></section>
