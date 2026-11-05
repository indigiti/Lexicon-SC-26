<?php $nav = content('navigation'); $current = path_only(); ?>
<header class="site-header" data-site-header>
  <div class="container header-inner">
    <a class="brand" href="/" aria-label="The Lexicon Schools home"><img src="/assets/images/brand/lexicon-schools-light.png" alt="The Lexicon Schools — Believe in Yourself" width="800" height="434" decoding="async" fetchpriority="high"></a>
    <nav class="main-nav" aria-label="Primary navigation">
      <?php foreach ($nav as $item): $root=parse_url($item['href'],PHP_URL_PATH) ?: '/'; $label=(string)($item['label']??''); $active=match($label){ 'Our Schools'=>(str_starts_with($current,'/schools') || $current==='/campus-tour' || $current==='/virtual-tour'), 'Admissions'=>(str_starts_with($current,'/admissions') || $current==='/apply-now'), 'The Lexicon Difference'=>str_starts_with($current,'/lex360'), 'About Us'=>str_starts_with($current,'/about'), 'Achievements'=>str_starts_with($current,'/achievements'), 'Alumni'=>str_starts_with($current,'/alumni'), 'Contact'=>(str_starts_with($current,'/contact') || $current==='/enquire' || $current==='/book-a-visit'), default=>($root==='/' ? $current==='/' : str_starts_with($current,$root)) }; ?>
      <div class="nav-item has-menu <?= $active?'active':'' ?>">
        <a class="nav-link" href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a>
        <?php if (!empty($item['items'])): ?><div class="nav-dropdown" role="menu"><?php foreach ($item['items'] as $sub): ?><a role="menuitem" href="<?= e($sub['href']) ?>"><?= e($sub['label']) ?></a><?php endforeach; ?></div><?php endif; ?>
      </div><?php endforeach; ?>
    </nav>
    <a class="header-search" href="/contact" aria-label="Search / contact">⌕</a>
    <a class="header-cta" href="/enquire">Enquire Now <span aria-hidden="true">↗</span></a>
    <button class="mobile-toggle" type="button" aria-label="Open menu" aria-expanded="false" data-menu-toggle><span></span><span></span><span></span></button>
  </div>
  <div class="mobile-panel" data-mobile-panel hidden>
    <div class="mobile-panel-inner"><?php foreach ($nav as $item): ?><details><summary><?= e($item['label']) ?></summary><a class="mobile-root-link" href="<?= e($item['href']) ?>">Overview</a><?php foreach (($item['items']??[]) as $sub): ?><a href="<?= e($sub['href']) ?>"><?= e($sub['label']) ?></a><?php endforeach; ?></details><?php endforeach; ?><div class="mobile-quick"><a href="/apply-now">Apply Now</a><a href="/enquire">Enquire</a><a href="/book-a-visit">Book a Visit</a></div></div>
  </div>
</header>
