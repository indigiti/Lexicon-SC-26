<?php
$data = content($contentKey);
$schools = content('schools');
$school = [];
foreach ($schools as $s) { if (($s['slug'] ?? '') === ($data['campus_slug'] ?? '')) { $school = $s; break; } }
$slug = $data['campus_slug'];
?>
<section class="ca-hero">
  <div class="container ca-hero-grid">
    <div class="ca-hero-copy">
      <div class="eyebrow"><?= e($data['hero']['eyebrow']) ?></div>
      <h1><?= e($data['hero']['title']) ?></h1>
      <p><?= e($data['hero']['intro']) ?></p>
      <div class="hero-actions">
        <a class="btn btn-lexicon" href="/apply-now?campus=<?= e($slug) ?>">Apply Now ↗</a>
        <a class="btn btn-outline-lexicon" href="/enquire?campus=<?= e($slug) ?>">Enquire</a>
        <a class="btn btn-outline-lexicon" href="/book-a-visit?campus=<?= e($slug) ?>">Book a Visit</a>
      </div>
    </div>
    <div class="ca-hero-side">
      <div class="ca-campus-logo"><img src="<?= e($data['logo']) ?>" alt="<?= e($data['campus']) ?>"></div>
      <div class="ca-year-card"><span>Admissions</span><strong><?= e($data['academic_year']) ?></strong><small><?= e($data['short']) ?> Campus</small></div>
    </div>
  </div>
</section>

<section class="ca-essential-strip"><div class="container ca-essential-grid">
<?php foreach ($data['essentials'] as $item): ?><article><span><?= e($item['label']) ?></span><strong><?= e($item['value']) ?></strong><p><?= e($item['text']) ?></p></article><?php endforeach; ?>
</div></section>

<nav class="section-jump ca-jump" aria-label="Campus admission sections"><div class="container">
  <a href="#how-to-apply">How to Apply</a><a href="#fees">Fees 2026–27</a><a href="#documents">Documents</a><a href="#class-xi">Class XI</a><a href="#policies">Policies</a>
</div></nav>

<section class="ca-section" id="how-to-apply"><div class="container">
  <div class="section-head-row"><div><div class="eyebrow">Application Journey</div><h2 class="section-title">A clear route from interest to confirmation.</h2></div><p class="section-intro">Use the official campus application system for operational steps. This page organises the current information into a parent-friendly sequence.</p></div>
  <div class="ca-step-grid"><?php foreach ($data['application_guidance'] as $step): ?><article><span><?= e($step['step']) ?></span><h3><?= e($step['title']) ?></h3><p><?= e($step['text']) ?></p></article><?php endforeach; ?></div>
</div></section>

<section class="ca-section ca-fees" id="fees"><div class="container">
  <div class="section-head-row"><div><div class="eyebrow">Fee Structure · <?= e($data['academic_year']) ?></div><h2 class="section-title">Current campus fee information.</h2></div><p class="section-intro"><?= e($data['fees']['intro']) ?></p></div>
  <div class="ca-fee-notes"><?php foreach ($data['fees']['admission_notes'] as $note): ?><div><span>i</span><p><?= e($note) ?></p></div><?php endforeach; ?></div>
  <?php foreach ($data['fees']['tables'] as $table): ?>
    <section class="ca-fee-table-card"><div class="ca-fee-title"><h3><?= e($table['title']) ?></h3><?php if (!empty($table['note'])): ?><p><?= e($table['note']) ?></p><?php endif; ?></div>
      <div class="ca-table-scroll" role="region" tabindex="0" aria-label="<?= e($table['title']) ?>">
        <table><thead><tr><th>Particular</th><?php foreach ($table['columns'] as $col): ?><th><?= e($col) ?></th><?php endforeach; ?></tr></thead><tbody>
        <?php foreach ($table['rows'] as $row): ?><tr><th><?= e($row[0]) ?></th><?php foreach (array_slice($row,1) as $cell): ?><td><?= e($cell) ?></td><?php endforeach; ?></tr><?php endforeach; ?>
        </tbody></table>
      </div>
    </section>
  <?php endforeach; ?>
  <div class="ca-fee-disclaimer"><strong>Before payment</strong><p>Use the latest school invoice / official campus communication as the final authority for amount, due date, class availability and any revised terms.</p></div>
</div></section>

<section class="ca-section ca-docs" id="documents"><div class="container ca-doc-grid">
  <div><div class="eyebrow">Documents</div><h2 class="section-title">Prepare the admission file early.</h2><p>Requirements can vary by entry class and admission stage. Keep originals available when the campus asks for verification.</p><div class="wa-doc-list"><?php foreach ($data['documents'] as $doc): ?><div><span>✓</span><?= e($doc) ?></div><?php endforeach; ?></div></div>
  <aside><img src="<?= e($school['image'] ?? '') ?>" alt="<?= e($data['campus']) ?>" loading="lazy"><div><span><?= e($data['short']) ?> Campus</span><h3>Need help before you submit?</h3><p>Use the enquiry or campus-visit route to confirm class availability, current documents and any campus-specific admission step.</p><a class="text-link" href="/enquire?campus=<?= e($slug) ?>">Ask the admissions team →</a></div></aside>
</div></section>

<section class="ca-section ca-xi" id="class-xi"><div class="container ca-xi-grid">
  <div><div class="eyebrow light">Senior Secondary · Class XI</div><h2>Separate process. Current campus-specific streams.</h2><p><?= e($data['class_xi']['intro']) ?></p><div class="hero-actions"><a class="btn ca-light-btn" href="/schools/<?= e($slug) ?>/admissions/class-xi">Explore Class XI →</a><a class="btn ca-ghost-btn" href="/enquire?campus=<?= e($slug) ?>">Ask a Question</a></div></div>
  <div class="ca-xi-cards"><?php foreach ($data['class_xi']['cutoffs'] as $cutoff): ?><article><span><?= e($cutoff['stream']) ?></span><strong><?= e($cutoff['value']) ?></strong><small>published Board-result cut-off</small></article><?php endforeach; ?><article class="wide"><span>Process</span><strong>Application → Counselling → Aptitude Test → Provisional Admission</strong></article></div>
</div></section>

<section class="admissions-transport-cta"><div class="container admissions-transport-inner"><div><div class="eyebrow">Transport &amp; Commute</div><h2>Check the current <?= e($data['short']) ?> route directory.</h2><p>Search 2026–27 route numbers and listed stops by locality or society before contacting the campus transport team.</p></div><div class="card-actions"><a class="btn btn-lexicon" href="/schools/<?= e($slug) ?>/transport">Find My Stop →</a><a class="btn btn-outline-lexicon" href="/enquire?campus=<?= e($slug) ?>&amp;reason=transport">Ask About Transport</a></div></div></section>

<section class="ca-section" id="policies"><div class="container">
  <div class="section-head-row"><div><div class="eyebrow">Important Policies</div><h2 class="section-title">Know the conditions before paying.</h2></div><p class="section-intro">The current campus publication and official school record prevail where operational information changes.</p></div>
  <div class="wa-policy-grid"><?php foreach ($data['policies'] as $policy): ?><details><summary><?= e($policy['title']) ?><span>+</span></summary><p><?= e($policy['text']) ?></p></details><?php endforeach; ?></div>
  <div class="ca-official-links"><a href="<?= e($school['admissionPage'] ?? $school['website'] ?? '#') ?>" target="_blank" rel="noopener noreferrer">Official Campus Admissions ↗</a><a href="/schools/<?= e($slug) ?>">Back to Campus Overview →</a></div>
</div></section>

<section class="signature-cta"><div class="container signature-cta-inner"><div><div class="eyebrow light">Ready for the Next Step?</div><h2>Apply, enquire or experience the campus first.</h2><p>Choose the action that matches where your family is in the admission journey.</p></div><div class="signature-cta-actions"><a class="apply-btn" href="/apply-now?campus=<?= e($slug) ?>">Apply Now ↗</a><a href="/enquire?campus=<?= e($slug) ?>">Enquire</a><a href="/book-a-visit?campus=<?= e($slug) ?>">Book a Visit</a></div></div></section>
