<?php $schools = content('schools'); ?>
<section class="editorial-hero contact-hero"><div class="container editorial-hero-grid">
  <div class="editorial-hero-copy"><div class="eyebrow">Get in Touch</div><h1>Visit. Enquire.<br>Start a conversation.</h1><p>Whether you are exploring admissions, planning a campus visit or looking for the right school community, our three Pune campuses are ready to help.</p><div class="hero-actions"><a class="btn btn-lexicon" href="/enquire">Enquire Now ↗</a><a class="btn btn-outline-lexicon" href="#schools">Find a School</a></div></div>
  <div class="contact-hero-schools"><?php foreach($schools as $school): ?><a href="#<?= e($school['slug']) ?>"><img src="<?= e($school['image']) ?>" alt="<?= e($school['name']) ?>"><span><b><?= e($school['short']) ?></b><small><?= e($school['grades']) ?> · <?= e($school['board']) ?></small></span></a><?php endforeach; ?></div>
</div></section>

<nav class="section-jump" aria-label="Contact sections"><div class="container"><a href="/enquire">Enquire</a><a href="/book-a-visit">Book a Visit</a><a href="/campus-tour">Campus Tour</a><a href="#schools">School Contacts &amp; Maps</a></div></nav>

<section class="contact-enquiry" id="enquire"><div class="container contact-grid">
  <article class="info-card contact-form-card"><div class="eyebrow">Enquire Now</div><h2>Tell us how we can help.</h2><p class="form-intro">Share the basics and the relevant school team can continue the conversation.</p><form class="form-grid" data-enquiry-form>
    <label>Parent / Guardian name<input name="parent_name" autocomplete="name" required></label>
    <label>Phone<input name="phone" autocomplete="tel" inputmode="tel" required></label>
    <label>Email<input name="email" type="email" autocomplete="email"></label>
    <label>Campus<select name="campus"><option value="">Choose a campus</option><?php foreach($schools as $school): ?><option><?= e($school['short']) ?></option><?php endforeach; ?></select></label>
    <label>Grade<input name="grade" placeholder="e.g. Grade I"></label>
    <label>Reason<select name="reason"><option>Admissions enquiry</option><option>Book a campus visit</option><option>General information</option><option>Alumni network</option><option>Careers</option></select></label>
    <label class="full">Message<textarea name="message" placeholder="Tell us about your enquiry"></textarea></label>
    <div class="full form-action-row"><button class="header-cta" type="submit">Send Enquiry ↗</button><span class="form-status" data-form-status aria-live="polite"></span></div>
  </form></article>
  <aside class="contact-side" id="visit"><article class="contact-side-card"><div class="eyebrow">Book a Visit</div><h2>Experience the school in person.</h2><p>Select “Book a campus visit” in the form, choose your preferred school and share a suitable date or timing in the message.</p><a class="text-link" href="#schools">Compare campus locations →</a></article><article class="contact-side-card"><div class="eyebrow">Admissions</div><h2>Need application information?</h2><p>Review the admissions process, age and grade eligibility, documents and FAQs before you apply.</p><a class="text-link" href="/admissions">Go to Admissions →</a></article></aside>
</div></section>

<section class="school-map-section" id="schools"><div class="container"><div class="section-head-row"><div><div class="eyebrow">Our Schools</div><h2 class="section-title">Three campuses across Pune.</h2></div><p class="section-intro">Use the maps for directions or contact the campus directly. Map embeds are loaded lazily to protect page speed.</p></div>
  <div class="map-campus-stack"><?php foreach($schools as $school): $q=urlencode($school['mapQuery']); ?><article class="map-campus-card" id="<?= e($school['slug']) ?>"><div class="map-campus-info"><small><?= e($school['board']) ?> · <?= e($school['grades']) ?></small><h3><?= e($school['name']) ?></h3><address><?= e($school['address']) ?></address><a class="campus-phone" href="tel:<?= e($school['phoneHref']) ?>"><?= e($school['phone']) ?></a><div class="map-actions"><a class="btn btn-lexicon" href="https://www.google.com/maps/search/?api=1&amp;query=<?= e($q) ?>" target="_blank" rel="noopener noreferrer">Get Directions ↗</a><a class="btn btn-outline-lexicon" href="/schools/<?= e($school['slug']) ?>">Explore School</a></div></div><div class="map-frame"><iframe title="Map for <?= e($school['name']) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=<?= e($q) ?>&amp;output=embed" allowfullscreen></iframe></div></article><?php endforeach; ?></div>
</div></section>

<section class="signature-cta contact-cta"><div class="container signature-cta-inner"><div><div class="eyebrow light">Admissions 2026–27</div><h2>The best way to understand Lexicon is to experience it.</h2><p>Visit a campus, ask the questions that matter to your family, and explore the learning environment for yourself.</p></div><div class="signature-cta-actions"><a class="apply-btn" href="/apply-now">Apply Now ↗</a><a href="#enquire">Enquire Now</a></div></div></section>
