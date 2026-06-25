export const LANGUAGES = ['fr', 'en', 'es'];

export const LOCALE_LABELS = {
  fr: 'FR',
  en: 'EN',
  es: 'ES',
};

export const emptyKitLocale = () => ({
  title: '',
  description: '',
  zipLabel: '',
  items: [],
});

export const emptyResourcesLocale = () => ({
  sectionTitle: '',
  items: [],
});

export const emptyKitLocales = () => ({
  fr: emptyKitLocale(),
  en: emptyKitLocale(),
  es: emptyKitLocale(),
});

export const emptyResourcesLocales = () => ({
  fr: emptyResourcesLocale(),
  en: emptyResourcesLocale(),
  es: emptyResourcesLocale(),
});

export const ensureKitLocales = (attributes) => {
  if (attributes?.locales && typeof attributes.locales === 'object') {
    return {
      locales: {
        fr: { ...emptyKitLocale(), ...(attributes.locales.fr ?? {}) },
        en: { ...emptyKitLocale(), ...(attributes.locales.en ?? {}) },
        es: { ...emptyKitLocale(), ...(attributes.locales.es ?? {}) },
      },
    };
  }

  return {
    locales: {
      fr: {
        title: attributes?.title ?? '',
        description: attributes?.description ?? '',
        zipLabel: attributes?.zipLabel ?? '',
        items: attributes?.items ?? [],
      },
      en: emptyKitLocale(),
      es: emptyKitLocale(),
    },
  };
};

export const ensureResourcesLocales = (attributes) => {
  if (attributes?.locales && typeof attributes.locales === 'object') {
    return {
      locales: {
        fr: { ...emptyResourcesLocale(), ...(attributes.locales.fr ?? {}) },
        en: { ...emptyResourcesLocale(), ...(attributes.locales.en ?? {}) },
        es: { ...emptyResourcesLocale(), ...(attributes.locales.es ?? {}) },
      },
    };
  }

  return {
    locales: {
      fr: {
        sectionTitle: attributes?.sectionTitle ?? '',
        items: attributes?.items ?? [],
      },
      en: emptyResourcesLocale(),
      es: emptyResourcesLocale(),
    },
  };
};

export const updateLocaleField = (locales, locale, field, value) => ({
  ...locales,
  [locale]: {
    ...locales[locale],
    [field]: value,
  },
});
