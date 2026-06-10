// mobile nav
const burger = document.getElementById('burger');
const navMobile = document.getElementById('navMobile');
const navEl = burger && burger.closest('nav');
if (burger && navMobile) {
  burger.addEventListener('click', () => {
    const isOpen = navMobile.classList.toggle('open');
    burger.classList.toggle('open');
    navEl && navEl.classList.toggle('menu-open', isOpen);
  });
  navMobile.querySelectorAll('a').forEach(a => {
    a.addEventListener('click', () => {
      burger.classList.remove('open');
      navMobile.classList.remove('open');
      navEl && navEl.classList.remove('menu-open');
    });
  });
}

// scroll progress bar
window.addEventListener('scroll', () => {
  const h = document.documentElement;
  document.getElementById('bar').style.width =
    (h.scrollTop / (h.scrollHeight - h.clientHeight) * 100) + '%';
}, {passive: true});

// cursor spotlight
document.addEventListener('mousemove', e => {
  document.documentElement.style.setProperty('--mx', e.clientX + 'px');
  document.documentElement.style.setProperty('--my', e.clientY + 'px');
}, {passive: true});

// scroll reveal
const io = new IntersectionObserver(es => es.forEach(e => {
  if (e.isIntersecting) { e.target.classList.add('on'); io.unobserve(e.target); }
}), {threshold: .07});
document.querySelectorAll('.r,.r2').forEach(el => io.observe(el));

// 3D card tilt
document.querySelectorAll('.bc').forEach(card => {
  card.addEventListener('mousemove', e => {
    const rect = card.getBoundingClientRect();
    const x = (e.clientX - rect.left) / rect.width - 0.5;
    const y = (e.clientY - rect.top) / rect.height - 0.5;
    card.style.transform =
      `perspective(1200px) rotateY(${x * 7}deg) rotateX(${-y * 7}deg) translateY(-4px) scale(1.01)`;
  });
  card.addEventListener('mouseleave', () => {
    card.style.transform = '';
    card.style.transition = 'transform .5s cubic-bezier(.16,1,.3,1), border-color .3s, box-shadow .3s';
  });
  card.addEventListener('mouseenter', () => {
    card.style.transition = 'transform .1s, border-color .3s, box-shadow .3s';
  });
});

// animated counters
function count(el, to, ms) {
  const s = performance.now();
  const run = t => {
    const p = Math.min((t - s) / ms, 1);
    const ease = 1 - Math.pow(1 - p, 3);
    el.textContent = Math.round(ease * to) + '+';
    if (p < 1) requestAnimationFrame(run);
  };
  requestAnimationFrame(run);
}
const cio = new IntersectionObserver(es => es.forEach(e => {
  if (e.isIntersecting) {
    count(e.target, +e.target.dataset.count, 1400);
    cio.unobserve(e.target);
  }
}), {threshold: .5});
document.querySelectorAll('[data-count]').forEach(el => cio.observe(el));

// form submit feedback
document.getElementById('sub').addEventListener('click', function () {
  this.textContent = 'Отправлено ✓';
  this.style.background = 'linear-gradient(135deg,#16a34a,#15803d)';
  this.style.animation = 'none';
  setTimeout(() => {
    this.textContent = 'Отправить сообщение';
    this.style.background = '';
    this.style.animation = '';
  }, 3000);
});
