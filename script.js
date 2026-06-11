// Anchors — smooth scroll to section
document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
  anchor.addEventListener('click', function(event) {
    event.preventDefault();
    const targetId = this.getAttribute('href');
    const targetElement = document.querySelector(targetId);
    if (targetElement) {
      targetElement.scrollIntoView({ behavior: 'smooth' });
    }
  });
});

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
const barEl = document.getElementById('bar');
window.addEventListener('scroll', () => {
  if (!barEl) return;
  const h = document.documentElement;
  barEl.style.width =
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

// form submit
const subBtn = document.getElementById('sub');
if (subBtn) subBtn.addEventListener('click', async function () {
  const consent = document.getElementById('consent');
  if (consent && !consent.checked) {
    consent.closest('.consent-wrap').style.outline = '2px solid #e03030';
    return;
  }

  const fields = { name: '', surname: '', email: '', service: '', message: '' };
  const form   = subBtn.closest('.form-box');
  if (form) {
    Object.keys(fields).forEach(key => {
      const el = form.querySelector('[name="' + key + '"]');
      if (el) fields[key] = el.value.trim();
    });
  }

  if (!fields.name || !fields.email || !fields.message) {
    Object.keys(fields).forEach(key => {
      if (!fields[key] && key !== 'surname' && key !== 'service') {
        const el = form && form.querySelector('[name="' + key + '"]');
        if (el) el.style.outline = '2px solid #e03030';
      }
    });
    return;
  }

  const btn = this;
  btn.disabled  = true;
  btn.textContent = '...';

  try {
    const body = new URLSearchParams(fields);
    const res  = await fetch('contact.php', { method: 'POST', body });
    const json = await res.json();

    if (json.success) {
      btn.textContent = 'Отправлено ✓';
      btn.style.background = 'linear-gradient(135deg,#16a34a,#15803d)';
      if (form) form.querySelectorAll('input,textarea,select').forEach(el => { el.value = ''; el.style.outline = ''; });
      if (consent) consent.checked = false;
      setTimeout(() => {
        btn.textContent = 'Отправить сообщение';
        btn.style.background = '';
        btn.disabled = false;
      }, 4000);
    } else {
      btn.textContent = json.error || 'Ошибка';
      btn.style.background = 'linear-gradient(135deg,#e03030,#b02020)';
      setTimeout(() => { btn.textContent = 'Отправить сообщение'; btn.style.background = ''; btn.disabled = false; }, 3000);
    }
  } catch {
    btn.textContent = 'Ошибка соединения';
    btn.style.background = 'linear-gradient(135deg,#e03030,#b02020)';
    setTimeout(() => { btn.textContent = 'Отправить сообщение'; btn.style.background = ''; btn.disabled = false; }, 3000);
  }
});
