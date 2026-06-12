import gsap from 'gsap';

const FOCUSABLE =
  'a[href], button:not([disabled]), input:not([disabled]), textarea:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])';

const getFocusable = (root) =>
  [...root.querySelectorAll(FOCUSABLE)].filter(
    (el) => !el.hasAttribute('disabled') && el.getAttribute('aria-hidden') !== 'true',
  );

const prefersReducedMotion = () =>
  window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const initBurgerMenu = (root) => {
  if (root.dataset.burgerReady === 'true') {
    return;
  }

  root.dataset.burgerReady = 'true';

  const toggle = root.querySelector('[data-stp-burger-toggle]');
  const drawer = root.querySelector('[data-stp-burger-drawer]');
  const backdrop = root.querySelector('[data-stp-burger-backdrop]');
  const label = root.querySelector('[data-stp-burger-label]');
  const navItems = drawer?.querySelectorAll('[data-stp-nav-menu] > [data-stp-nav-item]') ?? [];

  if (!toggle || !drawer || !backdrop || !label) {
    return;
  }

  const openLabel = label.textContent.trim();
  const closeLabel = drawer.dataset.closeLabel || 'Close menu';
  let previousFocus = null;
  let isOpen = false;
  let timeline = null;

  const setDrawerInteractive = (interactive) => {
    drawer.style.pointerEvents = interactive ? 'auto' : 'none';
    backdrop.style.pointerEvents = interactive ? 'auto' : 'none';
  };

  const setA11yState = (open) => {
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    label.textContent = open ? closeLabel : openLabel;
    drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
    backdrop.setAttribute('aria-hidden', open ? 'false' : 'true');
    document.documentElement.classList.toggle('overflow-hidden', open);

    if (open) {
      drawer.removeAttribute('inert');
    } else {
      drawer.setAttribute('inert', '');
    }
  };

  const hideNavItems = () => {
    if (prefersReducedMotion() || navItems.length === 0) {
      gsap.set(navItems, { clearProps: 'all' });

      return;
    }

    gsap.set(navItems, { opacity: 0, x: -20 });
  };

  const resetMotionState = () => {
    timeline?.kill();

    gsap.set(drawer, { xPercent: -100 });
    gsap.set(backdrop, { opacity: 0 });
    hideNavItems();
    setDrawerInteractive(false);
  };

  resetMotionState();
  setA11yState(false);

  const restoreFocus = () => {
    if (previousFocus instanceof HTMLElement) {
      previousFocus.focus();
    } else {
      toggle.focus();
    }
  };

  const open = () => {
    if (isOpen) {
      return;
    }

    isOpen = true;
    previousFocus = document.activeElement;
    setA11yState(true);

    timeline?.kill();

    if (prefersReducedMotion()) {
      gsap.set(drawer, { xPercent: 0 });
      gsap.set(backdrop, { opacity: 1 });
      gsap.set(navItems, { clearProps: 'all' });
      setDrawerInteractive(true);
    } else {
      hideNavItems();

      timeline = gsap.timeline({
        onStart: () => setDrawerInteractive(true),
      });

      timeline
        .to(backdrop, { opacity: 1, duration: 0.25, ease: 'power2.out' }, 0)
        .to(drawer, { xPercent: 0, duration: 0.4, ease: 'power3.out' }, 0);

      if (navItems.length > 0) {
        timeline.to(
          navItems,
          {
            opacity: 1,
            x: 0,
            duration: 0.35,
            stagger: 0.07,
            ease: 'power3.out',
          },
          0.15,
        );
      }
    }

    const firstNavLink = drawer.querySelector('[data-stp-nav-menu] a');

    if (firstNavLink instanceof HTMLElement) {
      firstNavLink.focus();
    } else {
      const focusables = getFocusable(drawer);

      if (focusables.length > 0) {
        focusables[0].focus();
      } else {
        toggle.focus();
      }
    }
  };

  const close = () => {
    if (!isOpen) {
      return;
    }

    timeline?.kill();

    const finishClose = () => {
      isOpen = false;
      setA11yState(false);
      resetMotionState();
      restoreFocus();
    };

    if (prefersReducedMotion()) {
      finishClose();

      return;
    }

    timeline = gsap.timeline({
      onComplete: finishClose,
    });

    if (navItems.length > 0) {
      timeline.to(
        navItems,
        {
          opacity: 0,
          x: -12,
          duration: 0.18,
          stagger: 0.04,
          ease: 'power2.in',
        },
        0,
      );
    }

    timeline
      .to(drawer, { xPercent: -100, duration: 0.32, ease: 'power3.in' }, navItems.length > 0 ? 0.08 : 0)
      .to(
        backdrop,
        {
          opacity: 0,
          duration: 0.22,
          ease: 'power2.in',
          onComplete: () => setDrawerInteractive(false),
        },
        navItems.length > 0 ? 0.12 : 0.06,
      );
  };

  toggle.addEventListener('click', () => {
    if (isOpen) {
      close();
    } else {
      open();
    }
  });

  backdrop.addEventListener('click', close);

  drawer.querySelector('[data-stp-burger-close]')?.addEventListener('click', close);

  drawer.addEventListener('click', (event) => {
    if (event.target instanceof HTMLAnchorElement) {
      close();
    }
  });

  root.addEventListener('keydown', (event) => {
    if (!isOpen) {
      return;
    }

    if (event.key === 'Escape') {
      event.preventDefault();
      close();

      return;
    }

    if (event.key !== 'Tab') {
      return;
    }

    const focusables = getFocusable(drawer);

    if (focusables.length === 0) {
      event.preventDefault();
      toggle.focus();

      return;
    }

    const first = focusables[0];
    const last = focusables[focusables.length - 1];
    const active = document.activeElement;

    if (event.shiftKey && active === first) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && active === last) {
      event.preventDefault();
      first.focus();
    }
  });
};

export const initBurgerMenus = () => {
  document.querySelectorAll('[data-stp-burger]').forEach(initBurgerMenu);
};
