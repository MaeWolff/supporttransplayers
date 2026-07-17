const initLanguageSwitcher = (root) => {
  if (root.dataset.langReady === 'true') {
    return;
  }

  root.dataset.langReady = 'true';

  const toggle = root.querySelector('[data-stp-lang-toggle]');
  const menu = root.querySelector('[data-stp-lang-menu]');

  if (!toggle || !menu) {
    return;
  }

  const isOpen = () => toggle.getAttribute('aria-expanded') === 'true';

  const setOpen = (open) => {
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    menu.hidden = !open;
  };

  setOpen(false);

  toggle.addEventListener('click', (event) => {
    event.stopPropagation();
    setOpen(!isOpen());
  });

  menu.addEventListener('click', (event) => {
    event.stopPropagation();
  });

  document.addEventListener('click', (event) => {
    if (!isOpen()) {
      return;
    }

    if (event.target instanceof Node && root.contains(event.target)) {
      return;
    }

    setOpen(false);
  });

  root.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && isOpen()) {
      event.preventDefault();
      setOpen(false);
      toggle.focus();
    }
  });
};

export const initLanguageSwitchers = () => {
  document.querySelectorAll('[data-stp-lang-switcher]').forEach(initLanguageSwitcher);
};
