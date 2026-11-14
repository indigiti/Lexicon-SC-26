<?php $schools = content('schools'); ?>
<section class="editorial-hero alumni-hero">
  <div class="container editorial-hero-grid">
    <div class="editorial-hero-copy">
      <div class="eyebrow">The Lexicon Alumni Network</div>
      <h1>Once a Lexiconite.<br>Always part of the story.</h1>
      <p>School may be where the journey begins, but the Lexicon community travels with you. Reconnect with your campus, share where life has taken you and help inspire the generations that follow.</p>
      <div class="hero-actions"><a class="btn btn-lexicon" href="#join">Join the Alumni Network ↗</a><a class="btn btn-outline-lexicon" href="#stories">Alumni Interviews &amp; Stories</a></div>
    </div>
    <div class="alumni-campus-mosaic" aria-label="The three Lexicon school communities">
      <figure class="alumni-campus-feature"><img src="<?= e(campus_photo('wagholi')) ?>" alt="The Lexicon International School, Wagholi campus"><figcaption>Wagholi</figcaption></figure>
      <figure><img src="<?= e(campus_photo('kalyani-nagar')) ?>" alt="The Lexicon International School, Kalyani Nagar campus"><figcaption>Kalyani Nagar</figcaption></figure>
      <figure><img src="<?= e(campus_photo('hadapsar')) ?>" alt="The Lexicon School, Hadapsar campus"><figcaption>Hadapsar</figcaption></figure>
    </div>
  </div>
</section>

<nav class="section-jump" aria-label="Alumni sections"><div class="container"><a href="#stories">Interviews &amp; Stories</a><a href="#network">Stay Connected</a><a href="#give-back">Give Back</a><a href="#join">Join the Network</a></div></nav>

<?php $alumniContent=content('alumni-profiles'); $profiles=array_values(array_filter($alumniContent['profiles'] ?? [],static fn($p): bool => is_array($p) && !empty($p['approved']) && !empty($p['consent_confirmed']) && !empty($p['name']) && !empty($p['transcript']))); ?>
<section class="story-section" id="stories"><div class="container">
 <div class="section-head-row"><div><div class="eyebrow">Alumni Voices</div><h2 class="section-title">Their journeys, in their own words.</h2></div><p class="section-intro">School-approved alumni interview transcripts, portraits and testimonials will appear here once verified and consented for publication.</p></div>
 <?php if ($profiles): ?><div class="alumni-interview-grid">
 <?php foreach($profiles as $profile): ?>
 <article class="alumni-interview-card">
  <?php if (!empty($profile['image'])): ?><img src="<?= e($profile['image']) ?>" alt="<?= e($profile['name']) ?> (approved alumni photo)" loading="lazy"><?php endif; ?>
  <div><span><?= e($profile['campus'] ?? 'Lexicon Alumni') ?></span><h3><?= e($profile['name']) ?></h3>
  <?php if (!empty($profile['summary'])): ?><p><?= e($profile['summary']) ?></p><?php endif; ?>
  <details><summary>Read interview transcript</summary><p><?= nl2br(e($profile['transcript'])) ?></p></details>
  <?php $videoUrl=(string)($profile['video_url'] ?? ''); if (preg_match('~^https://(?:www\.)?(?:youtube\.com/watch\?v=|youtu\.be/)~',$videoUrl)): ?><a href="<?= e($videoUrl) ?>" target="_blank" rel="noopener noreferrer">Watch interview ↗</a><?php endif; ?>
  </div>
 </article>
 <?php endforeach; ?>
 </div><?php else: ?><div class="verification-note"><b>Alumni interviews are being prepared.</b><span>We will publish only school-approved stories, images and transcripts after the alumni confirm permission.</span></div><?php endif; ?>
</div></section>

<section class="alumni-network-section" id="network"><div class="container two-col-feature">
  <div class="two-col-copy"><div class="eyebrow">The Network</div><h2>Reconnect with the people and places that shaped you.</h2><p>Stay close to school milestones, alumni meets, mentoring opportunities, campus events and stories from across the wider Lexicon community.</p><ul class="feature-list"><li>Alumni meet-ups and school reunions</li><li>Campus news and milestone invitations</li><li>Mentoring and career conversations with students</li><li>Speaker, panel and workshop opportunities</li></ul><a class="text-link" href="#join">Update your alumni profile →</a><?php $alumniGroup=(string)($alumniContent['linkedin_group_url'] ?? ''); if(preg_match('~^https://(?:www\\.)?linkedin\\.com/groups/\\d+~',$alumniGroup)): ?><a class="text-link" href="<?= e($alumniGroup) ?>" target="_blank" rel="noopener noreferrer">Join the official LinkedIn alumni group ↗</a><?php endif; ?></div>
  <div class="campus-ribbon"><?php foreach($schools as $school): ?><a href="/schools/<?= e($school['slug']) ?>"><img src="<?= e($school['image']) ?>" alt="<?= e($school['name']) ?>"><span><?= e($school['short']) ?></span></a><?php endforeach; ?></div>
</div></section>

<section class="give-back-section" id="give-back"><div class="container"><div class="section-head-row"><div><div class="eyebrow">Give Back</div><h2 class="section-title">Experience becomes powerful when it is shared.</h2></div></div><div class="experience-grid"><article><div class="experience-icon">01</div><h3>Mentor</h3><p>Help students understand courses, careers, university pathways and the choices that come after school.</p></article><article><div class="experience-icon">02</div><h3>Speak</h3><p>Return for a classroom conversation, panel, industry interaction, workshop or special school event.</p></article><article><div class="experience-icon">03</div><h3>Collaborate</h3><p>Connect student learning with projects, internships, institutions, communities and real-world problems.</p></article></div></div></section>

<section class="signature-cta alumni-cta" id="join"><div class="container signature-cta-inner"><div><div class="eyebrow light">Join the Alumni Network</div><h2>Your next chapter can inspire someone else's.</h2><p>Reconnect with your campus, update your details or share a story for the alumni team to review.</p></div><div class="signature-cta-actions"><a class="apply-btn" href="/enquire">Join / Update Profile ↗</a><a href="/book-a-visit">Visit Your Campus</a></div></div></section>
