/* Optional progressive enhancement; the page remains complete without JavaScript. */
(() => {
  const page = document.querySelector('.page-apply-now');
  if (!page || !('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  const targets = [...page.querySelectorAll('[data-apply-reveal]')];
  if (!targets.length) return;
  const observer = new IntersectionObserver((entries) => {
    for (const entry of entries) {
      if (!entry.isIntersecting) continue;
      entry.target.classList.add('is-visible');
      observer.unobserve(entry.target);
    }
  }, { rootMargin: '0px 0px -24px 0px', threshold: 0.06 });
  targets.forEach((element) => observer.observe(element));
  document.documentElement.classList.add('apply-motion-ready');
})();
