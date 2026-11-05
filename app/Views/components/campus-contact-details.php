<?php
/**
 * Shared verified campus contact directory.
 *
 * Caller supplies $school from content/schools.json. Keep identifiers, mobile
 * numbers and addresses in that data file so campus/contact/admissions pages agree.
 */
$campusPhones = $school['contactPhones'] ?? [];
$campusEmails = $school['contactEmails'] ?? [];
?>
<div class="campus-contact-directory">
  <?php if ($campusPhones): ?>
  <div class="campus-contact-group">
    <div class="campus-contact-group-label">Phone</div>
    <div class="campus-contact-links">
      <?php foreach ($campusPhones as $phone): ?>
      <a href="tel:<?= e($phone['href'] ?? '') ?>" aria-label="Call <?= e($school['short'] ?? 'school') ?> <?= e($phone['label'] ?? 'phone') ?> at <?= e($phone['display'] ?? '') ?>"><?= e($phone['display'] ?? '') ?></a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
  <?php if ($campusEmails): ?>
  <div class="campus-contact-group">
    <div class="campus-contact-group-label">Email</div>
    <div class="campus-contact-emails">
      <?php foreach ($campusEmails as $email): ?>
      <div class="campus-contact-email"><span><?= e($email['label'] ?? 'Email') ?></span><a href="mailto:<?= e($email['address'] ?? '') ?>"><?= e($email['address'] ?? '') ?></a></div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
  <div class="campus-contact-codes" aria-label="School registration details">
    <div><span>CBSE affiliation</span><strong><?= e($school['affiliation'] ?? '') ?></strong></div>
    <div><span>UDISE</span><strong><?= e($school['udise'] ?? '') ?></strong></div>
  </div>
</div>
