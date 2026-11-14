<?php
$schools = content('schools');
$schoolPages = content('school-pages');
$campus = $schoolPages[$schoolSlug] ?? [];
$school = null;
foreach ($schools as $candidate) {
    if (($candidate['slug'] ?? '') === $schoolSlug) { $school = $candidate; break; }
}
$school ??= [];
$q = urlencode((string)($school['mapQuery'] ?? $campus['name'] ?? 'The Lexicon Schools Pune'));
$galleryItems = content('campus-galleries')[$schoolSlug] ?? [];
if (!empty($school['image'])) {
    array_unshift($galleryItems, [
        'image' => $school['image'],
        'alt' => ($school['name'] ?? 'Lexicon campus') . ' main entrance',
        'caption' => 'School entrance',
        'approved' => true,
    ]);
}
$galleryItems = array_filter($galleryItems, static fn(array $item): bool => !empty($item['image']) && !empty($item['approved']));
$transportData = content('transport');
$transportCampus = $transportData['campuses'][$schoolSlug] ?? [];
$transportRoutes = (int)($transportCampus['route_count'] ?? 0);
$transportStops = (int)($transportCampus['stop_count'] ?? 0);
$academicsData = content('academics');
$academicCampus = $academicsData['campuses'][$schoolSlug] ?? [];
$academicEvents = $academicCampus['events'] ?? [];
usort($academicEvents, static fn(array $a,array $b): int => strcmp((string)($a['date']??''),(string)($b['date']??'')));
$academicToday = (new DateTimeImmutable('today'))->format('Y-m-d');
$academicUpcoming = array_values(array_filter($academicEvents, static fn(array $e): bool => ($e['date']??'') >= $academicToday));
if (!$academicUpcoming) $academicUpcoming = $academicEvents;
$academicUpcoming = array_slice($academicUpcoming,0,3);
$academicFmt = static function(string $date,string $format='d M'): string { try { return (new DateTimeImmutable($date))->format($format); } catch (Throwable) { return $date; } };
?>
<section class="school-hero">
  <div class="container school-hero-grid">
    <div class="school-hero-copy">
      <div class="eyebrow"><?= e($campus['eyebrow'] ?? 'Our Schools') ?></div>
      <h1><?= e($campus['headline'] ?? $campus['name'] ?? 'The Lexicon Schools') ?></h1>
      <p><?= e($campus['intro'] ?? '') ?></p>
      <div class="hero-actions school-hero-actions">
        <a class="btn btn-lexicon" href="/apply-now?campus=<?= e($schoolSlug) ?>">Apply Now ↗</a>
        <a class="btn btn-outline-lexicon" href="/book-a-visit?campus=<?= e($schoolSlug) ?>">Book a Visit</a>
        <a class="school-official-link" href="<?= e($campus['official_site'] ?? '#') ?>" target="_blank" rel="noopener noreferrer">Official campus site ↗</a>
      </div>
    </div>
    <div class="school-hero-media">
      <img src="<?= e($school['image'] ?? '') ?>" alt="<?= e($campus['name'] ?? $school['name'] ?? 'Lexicon school campus') ?>" fetchpriority="high" decoding="async">
      <div class="school-hero-badge"><span><?= e($campus['short'] ?? '') ?></span><small><?= e($school['board'] ?? 'CBSE') ?> · <?= e($school['grades'] ?? '') ?></small></div>
    </div>
  </div>
</section>

<section class="school-fact-strip" aria-label="Campus facts"><div class="container school-fact-grid">
<?php foreach (($campus['facts'] ?? []) as $fact): ?>
  <div><span><?= e($fact['label'] ?? '') ?></span><strong><?= e($fact['value'] ?? '') ?></strong></div>
<?php endforeach; ?>
</div></section>

<nav class="section-jump school-jump" aria-label="School sections"><div class="container">
  <a href="#overview">Overview</a><a href="#learning">Learning</a><a href="#campus">Campus</a><a href="#gallery">Gallery</a><a href="#transport">Transport</a><a href="#lex360">LEX360</a><a href="#admissions">Admissions</a><a href="#visit">Visit</a>
</div></nav>

<section class="school-overview" id="overview"><div class="container school-overview-grid">
  <div class="school-overview-copy">
    <div class="eyebrow">About the Campus</div>
    <h2 class="section-title"><?= e($campus['story_title'] ?? '') ?></h2>
    <?php foreach (($campus['story'] ?? []) as $paragraph): ?><p><?= e($paragraph) ?></p><?php endforeach; ?>
    <div class="school-inline-links"><a class="text-link" href="/lex360">Discover the Lexicon Difference →</a><a class="text-link" href="/about#philosophy">Vision, Mission &amp; Values →</a></div>
  </div>
  <figure class="school-overview-media"><img src="<?= e($school['image'] ?? '') ?>" alt="<?= e(($campus['short'] ?? '') . ' campus') ?>" loading="lazy"><figcaption><?= e($campus['name'] ?? '') ?></figcaption></figure>
</div></section>

<section class="school-learning" id="learning"><div class="container">
  <div class="section-head-row"><div><div class="eyebrow">Learning at <?= e($campus['short'] ?? '') ?></div><h2 class="section-title">Strong foundations. Wider possibilities.</h2></div><p class="section-intro">The campus programme builds academic competence while creating room for curiosity, confidence, communication and real-world learning.</p></div>
  <div class="school-learning-grid"><?php foreach (($campus['learning'] ?? []) as $item): ?><article><small><?= e($item['kicker'] ?? '') ?></small><h3><?= e($item['title'] ?? '') ?></h3><p><?= e($item['text'] ?? '') ?></p></article><?php endforeach; ?></div>
</div></section>

<section class="school-academic-pulse" id="academics"><div class="container school-academic-pulse-grid">
  <div class="school-academic-pulse-copy"><div class="eyebrow">Academic Year 2026–27</div><h2 class="section-title">The school year, made easier to follow.</h2><p>Open the campus academic hub for the planner, assessment methodology, holidays and calendar sync. Key dates are converted from the supplied campus documents into a responsive parent view.</p><div class="card-actions"><a class="btn btn-lexicon" href="/schools/<?= e($schoolSlug) ?>/academics">Explore Academics →</a><a class="btn btn-outline-lexicon" href="/schools/<?= e($schoolSlug) ?>/academics/calendar">Academic Calendar</a></div></div>
  <div class="school-academic-events"><?php foreach($academicUpcoming as $event): ?><a href="/schools/<?= e($schoolSlug) ?>/academics/calendar?month=<?= e(substr((string)($event['date']??''),0,7)) ?>"><time datetime="<?= e($event['date']??'') ?>"><b><?= e($academicFmt((string)($event['date']??''),'d')) ?></b><span><?= e(strtoupper($academicFmt((string)($event['date']??''),'M'))) ?></span></time><div><small><?= e(ucfirst((string)($event['category']??'event'))) ?></small><strong><?= e($event['title']??'') ?></strong></div></a><?php endforeach; ?></div>
</div></section>

<section class="school-campus" id="campus"><div class="container school-campus-grid">
  <div class="school-campus-copy"><div class="eyebrow">Campus &amp; Facilities</div><h2 class="section-title">Designed for the rhythm of a school day.</h2><p>Academic spaces, technology, safety systems and student services work together to support learning from early years through Senior Secondary.</p>
    <div class="school-facility-grid"><?php foreach (($campus['facilities'] ?? []) as $facility): ?><article><strong><?= e($facility['value'] ?? '') ?></strong><h3><?= e($facility['label'] ?? '') ?></h3><p><?= e($facility['detail'] ?? '') ?></p></article><?php endforeach; ?></div>
  </div>
  <div class="school-campus-visual"><img src="<?= e($school['image'] ?? '') ?>" alt="<?= e(($school['name'] ?? 'Lexicon campus') . ' entrance') ?>" loading="lazy"><div class="campus-address-card"><span>Campus Address</span><address><?= e($school['address'] ?? '') ?></address><?php if (!empty($school['phone'])): ?><a href="tel:<?= e($school['phoneHref'] ?? '') ?>"><?= e($school['phone']) ?></a><?php endif; ?><?php if (!empty($school['email'])): ?><a href="mailto:<?= e($school['email']) ?>"><?= e($school['email']) ?></a><?php endif; ?></div></div>

</div></section>

<?php if (!empty($galleryItems)): ?>
<section class="campus-gallery-section" id="gallery"><div class="container">
  <div class="section-head-row"><div><div class="eyebrow">Campus Gallery</div><h2 class="section-title">Explore <?= e($school['short'] ?? 'our school') ?>.</h2></div></div>
  <div class="campus-gallery-grid"><?php foreach ($galleryItems as $item): ?>
    <figure><img src="<?= e($item['image']) ?>" alt="<?= e($item['alt'] ?? 'Campus view') ?>" loading="lazy" decoding="async"><figcaption><?= e($item['caption'] ?? '') ?></figcaption></figure>
  <?php endforeach; ?></div>
  <p class="campus-gallery-note">Additional facility and activity photographs will be added after school review and image approval.</p>
</div></section>
<?php endif; ?>

<section class="school-transport" id="transport"><div class="container school-transport-grid">
  <div><div class="eyebrow">Transport &amp; Commute · 2026–27</div><h2 class="section-title">Check whether a current route serves your area.</h2><p>Search the sanitized campus transport directory by locality, society or stop name. Route and stop availability remains subject to operational confirmation by the school transport team.</p><div class="school-transport-stats"><div><strong><?= e($transportRoutes) ?></strong><span>Routes</span></div><div><strong><?= e($transportStops) ?></strong><span>Stops</span></div></div><div class="card-actions"><a class="btn btn-lexicon" href="/schools/<?= e($schoolSlug) ?>/transport">Find My Stop →</a><a class="btn btn-outline-lexicon" href="/enquire?campus=<?= e($schoolSlug) ?>&amp;reason=transport">Ask About Transport</a></div></div>
  <aside class="school-transport-card"><span>Transport Route Finder</span><h3>Search before you enquire.</h3><p>See current route numbers and listed stops without publishing vehicle registration numbers or departure times.</p><a href="/schools/<?= e($schoolSlug) ?>/transport">Explore <?= e($campus['short'] ?? '') ?> routes →</a></aside>
</div></section>

<section class="school-lex360" id="lex360"><div class="container">
  <div class="section-head-row school-lex-head"><div><div class="eyebrow light">The Lexicon Difference</div><h2><?= e($campus['experience_title'] ?? 'LEX360') ?></h2></div><p><?= e($campus['experience_text'] ?? '') ?></p></div>
  <div class="school-experience-grid"><?php foreach (($campus['experience_cards'] ?? []) as $card): ?><article><img src="<?= e($card['image'] ?? '') ?>" alt="" loading="lazy"><div><h3><?= e($card['title'] ?? '') ?></h3><p><?= e($card['text'] ?? '') ?></p></div></article><?php endforeach; ?></div>
  <a class="school-lex-link" href="/lex360">Explore LEX360 in depth ↗</a>
</div></section>

<section class="school-voice"><div class="container school-voice-grid"><div><div class="eyebrow">Parent Perspective</div><h2 class="section-title">What families notice.</h2></div><blockquote>“<?= e($campus['voice'] ?? '') ?>”<cite>Paraphrased from parent feedback published by the campus</cite></blockquote></div></section>

<section class="school-admissions" id="admissions"><div class="container school-admissions-grid">
  <div><div class="eyebrow">Admissions 2026–27</div><h2 class="section-title"><?= e($campus['admission_title'] ?? 'Explore admissions.') ?></h2><p><?= e($campus['admission_text'] ?? '') ?></p><div class="school-admission-actions"><a class="btn btn-lexicon" href="/schools/<?= e($schoolSlug) ?>/admissions"><?= e($campus['short'] ?? ucfirst($schoolSlug)) ?> Admissions Guide</a><a class="btn btn-outline-lexicon" href="/schools/<?= e($schoolSlug) ?>/admissions/class-xi">Class XI Admissions</a><a class="btn btn-outline-lexicon" href="<?= e($campus['official_admission'] ?? '#') ?>" target="_blank" rel="noopener noreferrer">Official Campus Admissions ↗</a></div></div>
  <aside class="school-resource-card"><span>Admissions Resources</span><a href="/schools/<?= e($schoolSlug) ?>/admissions">2026–27 Admissions Guide <b>→</b></a><a href="/schools/<?= e($schoolSlug) ?>/admissions/class-xi">Class XI Pathway <b>→</b></a><a href="<?= e($campus['official_site'] ?? '#') ?>" target="_blank" rel="noopener noreferrer">Campus Website <b>↗</b></a><a href="<?= e($campus['official_admission'] ?? '#') ?>" target="_blank" rel="noopener noreferrer">Official Admissions Information <b>↗</b></a><a href="<?= e($campus['official_disclosure'] ?? '#') ?>" target="_blank" rel="noopener noreferrer">Mandatory Public Disclosure <b>↗</b></a><small>Operational details such as fees, seat availability and current notices remain subject to the official campus publications.</small></aside>
</div></section>

<section class="school-visit" id="visit"><div class="container"><div class="section-head-row"><div><div class="eyebrow">Visit <?= e($campus['short'] ?? '') ?></div><h2 class="section-title">See the campus. Ask your questions.</h2></div><p class="section-intro">A campus visit is the best way to understand the learning environment, daily routines and community around your child.</p></div>
  <article class="school-single-map"><div class="map-campus-info"><small><?= e($school['board'] ?? 'CBSE') ?> · Affiliation <?= e($school['affiliation'] ?? '') ?></small><h3><?= e($campus['name'] ?? '') ?></h3><address><?= e($school['address'] ?? '') ?></address><?php require __DIR__ . '/../components/campus-contact-details.php'; ?><div class="map-actions"><a class="btn btn-lexicon" href="https://www.google.com/maps/search/?api=1&amp;query=<?= e($q) ?>" target="_blank" rel="noopener noreferrer">Get Directions ↗</a><a class="btn btn-outline-lexicon" href="/book-a-visit?campus=<?= e($schoolSlug) ?>">Book a Visit</a></div></div><div class="map-frame"><iframe title="Map for <?= e($campus['name'] ?? '') ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=<?= e($q) ?>&amp;output=embed" allowfullscreen></iframe></div></article>
</div></section>

<section class="other-schools"><div class="container"><div class="section-head-row"><div><div class="eyebrow">Explore Our Schools</div><h2 class="section-title">One vision. Three school communities.</h2></div><a class="text-link" href="/about#schools">Compare all schools →</a></div><div class="other-school-grid"><?php foreach ($schools as $other): if (($other['slug'] ?? '') === $schoolSlug) continue; ?><a class="other-school-card" href="/schools/<?= e($other['slug']) ?>"><img src="<?= e($other['image']) ?>" alt="<?= e($other['name']) ?>" loading="lazy"><div><small><?= e($other['board']) ?> · <?= e($other['grades']) ?></small><h3><?= e($other['name']) ?></h3><span>Explore school →</span></div></a><?php endforeach; ?></div></div></section>

<section class="signature-cta school-final-cta"><div class="container signature-cta-inner"><div><div class="eyebrow light">Begin Their Next Chapter</div><h2>The best way to understand Lexicon is to experience it.</h2><p>Explore admissions, visit the campus or start a conversation with the school team.</p></div><div class="signature-cta-actions"><a class="apply-btn" href="/apply-now?campus=<?= e($schoolSlug) ?>">Apply Now ↗</a><a href="/enquire?campus=<?= e($schoolSlug) ?>">Enquire Now</a><a href="/book-a-visit?campus=<?= e($schoolSlug) ?>">Book a Visit</a></div></div></section>
