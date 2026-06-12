import { initBurgerMenus } from './burger-menu.js';

const initHeroHighlights = () => {
  document.querySelectorAll('.stp-hero').forEach((hero) => {
    if (hero.dataset.heroReady === 'true') {
      return;
    }

    hero.dataset.heroReady = 'true';

    const reveal = () => hero.classList.add('is-visible');

    if (!('IntersectionObserver' in window)) {
      reveal();

      return;
    }

    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          reveal();
          observer.disconnect();
        }
      },
      { threshold: 0.25 },
    );

    observer.observe(hero);

    if (hero.getBoundingClientRect().top < window.innerHeight) {
      reveal();
      observer.disconnect();
    }
  });
};

const initNewsletterForms = () => {
  document.querySelectorAll('.stp-newsletter-form').forEach((form) => {
    if (form.dataset.newsletterReady === 'true') {
      return;
    }

    form.dataset.newsletterReady = 'true';

    form.addEventListener('submit', (event) => {
      event.preventDefault();
    });
  });
};

document.addEventListener('DOMContentLoaded', () => {
  initHeroHighlights();
  initNewsletterForms();
  initBurgerMenus();
});
