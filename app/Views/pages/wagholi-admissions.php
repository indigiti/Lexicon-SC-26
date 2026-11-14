<?php
$data = content('wagholi-admissions');
$schools = content('schools');
$wagholi = [];
foreach ($schools as $s) { if (($s['slug'] ?? '') === 'wagholi') { $wagholi = $s; break; } }
?>
<section class="wa-hero">
  <div class="container wa-hero-grid">
    <div class="wa-hero-copy">
      <div class="eyebrow"><?= e($data['hero']['eyebrow']) ?></div>
      <h1><?= e($data['hero']['title']) ?></h1>
      <p><?= e($data['hero']['intro']) ?></p>
      <div class="hero-actions">
        <a class="btn btn-lexicon" href="/apply-now?campus=wagholi">Apply Now ↗</a>
        <a class="btn btn-outline-lexicon" href="/enquire?campus=wagholi">Enquire</a>
        <a class="btn btn-outline-lexicon" href="/book-a-visit?campus=wagholi">Book a Visit</a>
      </div>
    </div>
    <div class="wa-hero-media">
      <img src="/assets/images/admissions/adm-hero.webp" alt="Student at The Lexicon Schools" fetchpriority="high" decoding="async">
      <div class="wa-hero-card"><span>Academic Year</span><strong><?= e($data['academic_year']) ?></strong><small>Wagholi · Nursery to Class XII</small></div>
    </div>
  </div>
</section>

<section class="wa-essential-strip" aria-label="Wagholi admissions essentials"><div class="container wa-essential-grid">
  <?php foreach ($data['essentials'] as $item): ?><article><span><?= e($item['label']) ?></span><strong><?= e($item['value']) ?></strong><p><?= e($item['text']) ?></p></article><?php endforeach; ?>
</div></section>

<nav class="section-jump wa-jump" aria-label="Wagholi admission sections"><div class="container">
  <a href="#eligibility">Eligibility</a><a href="#how-to-apply">How to Apply</a><a href="#selection">Selection</a><a href="#documents">Documents</a><a href="#class-xi">Class XI</a><a href="#policies">Policies</a>
</div></nav>

<section class="wa-intro" id="overview"><div class="container wa-intro-grid">
  <div><div class="eyebrow">Before You Apply</div><h2 class="section-title">Start with the right campus, class and documents.</h2><p>The Wagholi process is campus-specific. Keep all identity records consistent, use one valid application per child, preserve the generated application reference, and monitor the registered email address for school communication.</p></div>
  <aside class="wa-note"><span>Important</span><h3>Application fee and school fees are different.</h3><p>The published online application form fee is <strong><?= e($data['application_fee']) ?></strong> and non-refundable. Annual school fees, admission fees, transport and payment dates are operational information and should be checked against the current official Wagholi publication before payment.</p><a class="text-link" href="<?= e($wagholi['admissionPage'] ?? 'https://wagholi.lexiconedu.in/admission/') ?>" target="_blank" rel="noopener noreferrer">Official Wagholi admissions ↗</a></aside>
</div></section>

<section class="wa-section wa-eligibility" id="eligibility"><div class="container">
  <div class="section-head-row"><div><div class="eyebrow">Age &amp; Grade Eligibility · 2026–27</div><h2 class="section-title">Which class can my child apply for?</h2></div><p class="section-intro">Early-years and Class I eligibility is date-of-birth based. For later grades, previous-grade completion and the applicable school criteria apply.</p></div>
  <div class="wa-table-wrap" role="region" aria-label="Wagholi age and grade eligibility" tabindex="0"><table class="wa-table"><thead><tr><th>Applying for</th><th>Eligibility</th><th>Age / route</th></tr></thead><tbody><?php foreach ($data['age_criteria'] as $row): ?><tr><td><strong><?= e($row['class']) ?></strong></td><td><?= e($row['eligibility']) ?></td><td><?= e($row['age']) ?></td></tr><?php endforeach; ?></tbody></table></div>
  <div class="wa-inline-callout"><strong>Class XI follows a separate Senior Secondary route.</strong><span>Review counselling, aptitude-test, stream and document information before applying.</span><a href="/schools/wagholi/admissions/class-xi">View Class XI Admissions →</a></div>
</div></section>

<section class="wa-section wa-guidelines" id="how-to-apply"><div class="container">
  <div class="section-head-row"><div><div class="eyebrow">Guidelines for the Online Application</div><h2 class="section-title">Complete it once. Complete it carefully.</h2></div><p class="section-intro">The application record should agree with government and previous-school documents. Incorrect or duplicate submissions can delay or invalidate the process.</p></div>
  <ol class="wa-guideline-grid"><?php foreach ($data['guidelines'] as $i => $guide): ?><li><span><?= str_pad((string)($i+1),2,'0',STR_PAD_LEFT) ?></span><p><?= e($guide) ?></p></li><?php endforeach; ?></ol>
</div></section>

<section class="wa-section wa-selection" id="selection"><div class="container">
  <div class="wa-split-heading"><div><div class="eyebrow light">Admission Categories</div><h2>Understand how selection works.</h2></div><p>The school publishes a minority-preference process and, for general applicants, a lucky-draw workflow where applicable.</p></div>
  <div class="wa-selection-grid">
    <article class="wa-minority"><span>Hindi Linguistic Minority</span><h3>Minority preference</h3><ul><?php foreach ($data['minority'] as $item): ?><li><?= e($item) ?></li><?php endforeach; ?></ul></article>
    <div class="wa-process"><span>General Applicants</span><h3>Selection process where a lucky draw applies</h3><div class="wa-process-list"><?php foreach ($data['general_process'] as $step): ?><article><b><?= e($step['step']) ?></b><div><h4><?= e($step['title']) ?></h4><p><?= e($step['text']) ?></p></div></article><?php endforeach; ?></div></div>
  </div>
</div></section>

<section class="wa-section wa-documents" id="documents"><div class="container wa-doc-grid">
  <div><div class="eyebrow">Documents After Selection</div><h2 class="section-title">Keep the admission file ready.</h2><p class="wa-copy">Identity proof of the student and identity/address proof of the parent(s) or legal guardian(s) are required as applicable. In a single-parent / legal-guardian case, the applicable guardian documentation should be provided.</p><div class="wa-doc-list"><?php foreach ($data['documents'] as $doc): ?><div><span>✓</span><?= e($doc) ?></div><?php endforeach; ?></div></div>
  <aside class="wa-doc-aside"><img src="<?= e(campus_photo('wagholi')) ?>" alt="The Lexicon International School, Wagholi" loading="lazy"><div><span>Wagholi Campus</span><h3>Selection comes before final document submission where the published process requires it.</h3><p>Carry originals when the school asks for authentication and follow the campus communication for the exact submission window.</p><a class="text-link" href="/book-a-visit?campus=wagholi">Book a Campus Visit →</a></div></aside>
</div></section>

<section class="wa-section wa-xi-teaser" id="class-xi"><div class="container wa-xi-teaser-grid">
  <div><div class="eyebrow light">Senior Secondary · Class XI</div><h2>A separate pathway for Science, Commerce and Humanities.</h2><p><?= e($data['class_xi']['intro']) ?></p><div class="hero-actions"><a class="btn wa-btn-light" href="/schools/wagholi/admissions/class-xi">Explore Class XI Admissions →</a><a class="btn wa-btn-ghost" href="/enquire?campus=wagholi">Ask the Admissions Team</a></div></div>
  <div class="wa-xi-mini"><?php foreach ($data['class_xi']['streams'] as $stream): ?><article><span><?= e($stream['name']) ?></span><strong><?= count($stream['blocks']) ?></strong><small>subject blocks</small></article><?php endforeach; ?><article class="wide"><span>Process</span><strong>Application → Counselling → Aptitude Test → Provisional Admission</strong></article></div>
</div></section>

<section class="admissions-transport-cta"><div class="container admissions-transport-inner"><div><div class="eyebrow">Transport &amp; Commute</div><h2>Does a current Wagholi route serve your locality?</h2><p>Search 2026–27 routes and listed stops before contacting the transport team.</p></div><div class="card-actions"><a class="btn btn-lexicon" href="/schools/wagholi/transport">Find My Stop →</a><a class="btn btn-outline-lexicon" href="/enquire?campus=wagholi&amp;reason=transport">Ask About Transport</a></div></div></section>

<section class="wa-section wa-policies" id="policies"><div class="container">
  <div class="section-head-row"><div><div class="eyebrow">Admissions Policies</div><h2 class="section-title">Important conditions before you continue.</h2></div><p class="section-intro">These summaries are designed to make the campus process easier to understand. Current official school communication prevails where operational information changes.</p></div>
  <div class="wa-policy-grid"><?php foreach ($data['policies'] as $policy): ?><details><summary><?= e($policy['title']) ?><span>+</span></summary><p><?= e($policy['text']) ?></p></details><?php endforeach; ?></div>
  <div class="wa-disclaimer"><span>Admission disclaimer</span><p>Every effort is made to present the current Wagholi admission journey clearly. Admissions, seat availability, operational dates, fees and school requirements may change. The official school record and current campus communication are final in case of a discrepancy.</p></div>
</div></section>

<section class="signature-cta wa-final-cta"><div class="container signature-cta-inner"><div><div class="eyebrow light">Ready for the Next Step?</div><h2>Choose the action that matches where you are today.</h2><p>Apply, ask a question, or visit Wagholi before making your decision.</p></div><div class="signature-cta-actions"><a class="apply-btn" href="/apply-now?campus=wagholi">Apply Now ↗</a><a href="/enquire?campus=wagholi">Enquire</a><a href="/book-a-visit?campus=wagholi">Book a Visit</a></div></div></section>
