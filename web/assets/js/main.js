(() => {
  const menu = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.nav');
  menu?.addEventListener('click', () => {
    const open = nav.classList.toggle('open');
    menu.setAttribute('aria-expanded', String(open));
    menu.textContent = open ? '×' : '☰';
  });
  nav?.querySelectorAll('a').forEach(link => link.addEventListener('click', () => {
    nav.classList.remove('open'); menu?.setAttribute('aria-expanded', 'false'); if (menu) menu.textContent = '☰';
  }));

  // Accessible, touch-friendly hero feature slider.
  const slider = document.querySelector('[data-feature-slider]');
  if (slider) {
    const slides = [...slider.querySelectorAll('.feature-slide')];
    const current = document.querySelector('[data-slide-current]');
    const total = document.querySelector('[data-slide-total]');
    let index = Math.max(0, slides.findIndex(s => s.classList.contains('is-active')));
    let timer = null;
    if (total) total.textContent = String(slides.length).padStart(2, '0');
    const show = next => {
      index = (next + slides.length) % slides.length;
      slides.forEach((slide, i) => { slide.classList.toggle('is-active', i === index); slide.setAttribute('aria-hidden', String(i !== index)); });
      if (current) current.textContent = String(index + 1).padStart(2, '0');
    };
    const restart = () => { if (timer) window.clearInterval(timer); if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) timer = window.setInterval(() => show(index + 1), 6500); };
    document.querySelector('[data-slider-prev]')?.addEventListener('click', () => { show(index - 1); restart(); });
    document.querySelector('[data-slider-next]')?.addEventListener('click', () => { show(index + 1); restart(); });
    let startX = 0;
    slider.addEventListener('touchstart', e => { startX = e.changedTouches[0].screenX; }, {passive:true});
    slider.addEventListener('touchend', e => { const delta = e.changedTouches[0].screenX - startX; if (Math.abs(delta) > 45) { show(index + (delta < 0 ? 1 : -1)); restart(); } }, {passive:true});
    slider.addEventListener('mouseenter', () => timer && window.clearInterval(timer));
    slider.addEventListener('mouseleave', restart);
    show(index); restart();
  }

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  const hasGSAP = !!window.gsap;
  if (hasGSAP) {
    if (window.ScrollTrigger) gsap.registerPlugin(ScrollTrigger);
    gsap.from('.hero .eyebrow, .hero h1, .hero-bottom', {y:28, opacity:0, duration:1, stagger:.12, ease:'power3.out'});
    gsap.from('.hero-photo', {clipPath:'inset(12% 0 12% 0)', opacity:0, duration:1.25, delay:.25, ease:'power3.out'});
    if (window.ScrollTrigger) {
      gsap.utils.toArray('.section-head, .service, .about-copy, .process-grid article, .contact-grid, .faq-list details, .stats-strip>div').forEach((el, i) => {
        gsap.from(el, {y:28, opacity:0, duration:.75, delay:(i % 3) * .05, ease:'power2.out', scrollTrigger:{trigger:el,start:'top 89%',once:true}});
      });
      gsap.utils.toArray('.gallery-card').forEach((el, i) => gsap.from(el, {y:35, opacity:0, duration:.8, ease:'power2.out', scrollTrigger:{trigger:el,start:'top 90%',once:true}}));
    }
  }
  if (window.Lenis && hasGSAP) {
    const lenis = new Lenis({duration:1.05,smoothWheel:true});
    if (window.ScrollTrigger) lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add(time => lenis.raf(time * 1000));
    gsap.ticker.lagSmoothing(0);
  }
})();
