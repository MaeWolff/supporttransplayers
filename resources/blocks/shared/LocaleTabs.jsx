import { Button } from '@wordpress/components';
import { LANGUAGES, LOCALE_LABELS } from './locales';

export const LocaleTabs = ({ activeLocale, onChange }) => (
  <div style={{ display: 'flex', gap: '8px', marginBottom: '16px', flexWrap: 'wrap' }}>
    {LANGUAGES.map((locale) => (
      <Button
        key={locale}
        variant={activeLocale === locale ? 'primary' : 'secondary'}
        onClick={() => onChange(locale)}
      >
        {LOCALE_LABELS[locale]}
      </Button>
    ))}
  </div>
);
