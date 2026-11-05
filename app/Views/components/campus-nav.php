<?php
$currentPath = path_only();
if (!preg_match('~^/schools/(wagholi|kalyani-nagar|hadapsar)(?:/|$)~', $currentPath, $m)) { return; }
$campusSlug = $m[1];
$schoolPages = content('school-pages');
$schools = content('schools');
$campus = $schoolPages[$campusSlug] ?? [];
$school = [];
foreach ($schools as $s) { if (($s['slug'] ?? '') === $campusSlug) { $school = $s; break; } }
$logoMap = [
  'wagholi' => '/assets/images/brand/campuses/wagholi-light.png',
  'kalyani-nagar' => '/assets/images/brand/campuses/kalyani-nagar-light.png',
  'hadapsar' => '/assets/images/brand/campuses/hadapsar-light.png',
];
$campusName = $campus['name'] ?? ($school['name'] ?? ucfirst(str_replace('-', ' ', $campusSlug)));
$campusShort = $campus['short'] ?? $school['short'] ?? ucfirst(str_replace('-', ' ', $campusSlug));
$base = '/schools/' . $campusSlug;
$links = [
  ['label'=>'Overview','href'=>$base],
  ['label'=>'Admissions','href'=>$base . '/admissions'],
  ['label'=>'Class XI','href'=>$base . '/admissions/class-xi'],
  ['label'=>'Learning','href'=>$base . '/academics'],
  ['label'=>'Campus','href'=>$base . '#campus'],
  ['label'=>'LEX360','href'=>$base . '#lex360'],
  ['label'=>'Transport','href'=>$base . '/transport'],
  ['label'=>'Visit','href'=>$base . '#visit'],
];
?>
<section class="campus-nav campus-nav-floating" data-campus-nav>
  <div class="container campus-nav-shell">
    <div class="campus-nav-card">
      <a class="campus-nav-brand" href="<?= e($base) ?>" aria-label="<?= e($campusName) ?> overview">
        <img src="<?= e($logoMap[$campusSlug]) ?>" alt="<?= e($campusName) ?>" decoding="async">
      </a>

      <nav class="campus-nav-links" data-campus-swipe aria-label="<?= e($campusShort) ?> campus navigation">
        <?php foreach ($links as $link):
          $active = $currentPath === $link['href']
            || ($link['label']==='Admissions' && str_starts_with($currentPath,$base.'/admissions') && !str_ends_with($currentPath,'/class-xi'))
            || ($link['label']==='Class XI' && str_ends_with($currentPath,'/class-xi'))
            || ($link['label']==='Learning' && str_starts_with($currentPath,$base.'/academics'));
        ?>
          <a class="<?= $active?'active':'' ?>" href="<?= e($link['href']) ?>" <?= $active?'aria-current="page"':'' ?>><?= e($link['label']) ?></a>
        <?php endforeach; ?>
      </nav>

      <details class="campus-switcher">
        <summary><span class="campus-switcher-label"><?= e($campusShort) ?></span><span aria-hidden="true">⌄</span></summary>
        <div class="campus-switcher-menu">
          <span>Switch campus</span>
          <?php foreach ($schools as $s): ?>
            <a class="<?= ($s['slug']??'')===$campusSlug?'active':'' ?>" href="/schools/<?= e($s['slug']) ?>">
              <strong><?= e($s['short']) ?></strong><small><?= e($s['name']) ?></small>
            </a>
          <?php endforeach; ?>
        </div>
      </details>

      <a class="campus-nav-cta" href="/apply-now?campus=<?= e($campusSlug) ?>">Apply Now <span aria-hidden="true">↗</span></a>
    </div>
  </div>
</section>
