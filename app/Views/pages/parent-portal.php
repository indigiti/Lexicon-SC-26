<?php $schools=content('schools'); ?>
<section class="parent-portal-hero"><div class="container">
  <div class="eyebrow">Existing Families</div><h1>Parent ERP Access</h1>
  <p>Choose your school to access the official parent portal or request login assistance from your campus. Login credentials are provided only by the school.</p>
</div></section>
<section class="parent-portal-section"><div class="container"><div class="parent-portal-grid">
<?php foreach ($schools as $school): $erp=(string)($school['erpUrl'] ?? ''); $hasPortal=(bool)(filter_var($erp,FILTER_VALIDATE_URL) && str_starts_with($erp,'https://')); ?>
<article class="parent-portal-card"><img src="<?= e($school['image']) ?>" loading="lazy" alt="<?= e($school['name']) ?> campus">
<div><h2><?= e($school['name']) ?></h2>
  <p>For parents of currently enrolled students. Use the credentials supplied by your school; do not submit passwords through enquiry forms.</p>
  <?php if ($hasPortal): ?><a class="btn btn-lexicon" href="<?= e($erp) ?>" target="_blank" rel="noopener noreferrer">Open Parent ERP ↗</a><?php else: ?><a class="btn btn-outline-lexicon" href="/contact#<?= e($school['slug']) ?>">Request ERP Login Details →</a><?php endif; ?>
</div></article>
<?php endforeach; ?>
</div><p class="parent-portal-note">The portal links are published only after each campus confirms its official ERP address. The Lexicon Schools group website does not collect ERP passwords.</p></div></section>
