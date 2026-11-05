<?php $schools = content('schools'); ?>
<section class="editorial-hero alumni-hero">
  <div class="container editorial-hero-grid">
    <div class="editorial-hero-copy">
      <div class="eyebrow">The Lexicon Alumni Network</div>
      <h1>Once a Lexiconite.<br>Always part of the story.</h1>
      <p>School may be where the journey begins, but the Lexicon community travels with you. Reconnect with your campus, share where life has taken you and help inspire the generations that follow.</p>
      <div class="hero-actions"><a class="btn btn-lexicon" href="#join">Join the Alumni Network ↗</a><a class="btn btn-outline-lexicon" href="#stories">Explore Alumni Stories</a></div>
    </div>
    <div class="alumni-campus-mosaic" aria-label="The three Lexicon school communities">
      <figure class="alumni-campus-feature"><img src="/assets/images/campuses/campus-wagholi.webp" alt="The Lexicon International School, Wagholi campus"><figcaption>Wagholi</figcaption></figure>
      <figure><img src="/assets/images/campuses/campus-kalyani.webp" alt="The Lexicon International School, Kalyani Nagar campus"><figcaption>Kalyani Nagar</figcaption></figure>
      <figure><img src="/assets/images/campuses/campus-hadapsar.webp" alt="The Lexicon School, Hadapsar campus"><figcaption>Hadapsar</figcaption></figure>
    </div>
  </div>
</section>

<nav class="section-jump" aria-label="Alumni sections"><div class="container"><a href="#stories">Meet Our Alumni</a><a href="#network">Stay Connected</a><a href="#give-back">Give Back</a><a href="#join">Join the Network</a></div></nav>

<section class="story-section" id="stories"><div class="container">
  <div class="section-head-row"><div><div class="eyebrow">Meet Our Alumni</div><h2 class="section-title">Every journey becomes part of a larger story.</h2></div><p class="section-intro">We are building a verified alumni story library spanning higher education, entrepreneurship, the professions, creative work, public service and community leadership.</p></div>
  <div class="alumni-path-grid">
    <article><span>01</span><h3>Higher Education</h3><p>Share the universities, programmes, research and learning experiences that became your next chapter.</p></article>
    <article><span>02</span><h3>Professional Journeys</h3><p>Tell current students how school experiences translated into skills, confidence and career choices.</p></article>
    <article><span>03</span><h3>Entrepreneurship &amp; Creativity</h3><p>Show how ideas grew into ventures, creative practice, technology, design, leadership and impact.</p></article>
    <article><span>04</span><h3>Community &amp; Service</h3><p>Celebrate Lexiconites contributing through civic work, volunteering, mentoring and social initiatives.</p></article>
  </div>
  <div class="verification-note"><b>Alumni stories will be published only after verification.</b><span>This keeps names, qualifications, photographs and current roles accurate and consent-led.</span></div>
</div></section>

<section class="alumni-network-section" id="network"><div class="container two-col-feature">
  <div class="two-col-copy"><div class="eyebrow">The Network</div><h2>Reconnect with the people and places that shaped you.</h2><p>Stay close to school milestones, alumni meets, mentoring opportunities, campus events and stories from across the wider Lexicon community.</p><ul class="feature-list"><li>Alumni meet-ups and school reunions</li><li>Campus news and milestone invitations</li><li>Mentoring and career conversations with students</li><li>Speaker, panel and workshop opportunities</li></ul><a class="text-link" href="#join">Update your alumni profile →</a></div>
  <div class="campus-ribbon"><?php foreach($schools as $school): ?><a href="/schools/<?= e($school['slug']) ?>"><img src="<?= e($school['image']) ?>" alt="<?= e($school['name']) ?>"><span><?= e($school['short']) ?></span></a><?php endforeach; ?></div>
</div></section>

<section class="give-back-section" id="give-back"><div class="container"><div class="section-head-row"><div><div class="eyebrow">Give Back</div><h2 class="section-title">Experience becomes powerful when it is shared.</h2></div></div><div class="experience-grid"><article><div class="experience-icon">01</div><h3>Mentor</h3><p>Help students understand courses, careers, university pathways and the choices that come after school.</p></article><article><div class="experience-icon">02</div><h3>Speak</h3><p>Return for a classroom conversation, panel, industry interaction, workshop or special school event.</p></article><article><div class="experience-icon">03</div><h3>Collaborate</h3><p>Connect student learning with projects, internships, institutions, communities and real-world problems.</p></article></div></div></section>

<section class="signature-cta alumni-cta" id="join"><div class="container signature-cta-inner"><div><div class="eyebrow light">Join the Alumni Network</div><h2>Your next chapter can inspire someone else's.</h2><p>Reconnect with your campus, update your details or share a story for the alumni team to review.</p></div><div class="signature-cta-actions"><a class="apply-btn" href="/enquire">Join / Update Profile ↗</a><a href="/book-a-visit">Visit Your Campus</a></div></div></section>
