import {
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
  useBlockProps,
} from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';
import {
  Button,
  Notice,
  PanelBody,
  TextControl,
  TextareaControl,
} from '@wordpress/components';
import { Fragment, useMemo, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { ServerSideRender } from '@wordpress/server-side-render';
import { LocaleTabs } from '../shared/LocaleTabs';
import {
  emptyKitLocale,
  ensureKitLocales,
  updateLocaleField,
} from '../shared/locales';
import metadata from './block.json';

const { render, $schema, ...blockSettings } = metadata;

const emptyItem = () => ({
  attachmentId: 0,
  alt: '',
});

const updateItem = (items, index, field, value) =>
  items.map((item, itemIndex) =>
    itemIndex === index ? { ...item, [field]: value } : item,
  );

const moveItem = (items, index, direction) => {
  const targetIndex = index + direction;

  if (targetIndex < 0 || targetIndex >= items.length) {
    return items;
  }

  const next = [...items];
  const [moved] = next.splice(index, 1);
  next.splice(targetIndex, 0, moved);

  return next;
};

const Edit = ({ attributes, setAttributes }) => {
  const blockProps = useBlockProps();
  const [activeLocale, setActiveLocale] = useState('fr');
  const { locales } = useMemo(() => ensureKitLocales(attributes), [attributes]);
  const localeData = locales[activeLocale] ?? emptyKitLocale();
  const items = localeData.items ?? [];

  const setLocaleData = (patch) => {
    setAttributes({
      locales: {
        ...locales,
        [activeLocale]: {
          ...localeData,
          ...patch,
        },
      },
    });
  };

  const renderAttributes = useMemo(
    () => ensureKitLocales(attributes),
    [attributes],
  );

  return (
    <Fragment>
      <InspectorControls>
        <PanelBody title={__('Kit multilingue', 'sage')} initialOpen>
          <Notice status="info" isDismissible={false}>
            {__(
              'Renseignez le contenu pour chaque langue. L’enregistrement de la page française synchronise les traductions Polylang.',
              'sage',
            )}
          </Notice>

          <LocaleTabs activeLocale={activeLocale} onChange={setActiveLocale} />

          <TextControl
            label={__('Titre', 'sage')}
            value={localeData.title}
            onChange={(value) =>
              setAttributes({
                locales: updateLocaleField(locales, activeLocale, 'title', value),
              })
            }
          />
          <TextareaControl
            label={__('Description', 'sage')}
            value={localeData.description}
            onChange={(value) =>
              setAttributes({
                locales: updateLocaleField(
                  locales,
                  activeLocale,
                  'description',
                  value,
                ),
              })
            }
          />
          <TextControl
            label={__('Libellé du bouton ZIP', 'sage')}
            value={localeData.zipLabel}
            onChange={(value) =>
              setAttributes({
                locales: updateLocaleField(
                  locales,
                  activeLocale,
                  'zipLabel',
                  value,
                ),
              })
            }
          />
        </PanelBody>

        {items.map((item, index) => (
          <PanelBody
            key={`${activeLocale}-${index}`}
            title={`${__('Fichier', 'sage')} ${index + 1} (${activeLocale.toUpperCase()})`}
            initialOpen={index === 0}
          >
            <MediaUploadCheck>
              <MediaUpload
                onSelect={(media) =>
                  setLocaleData({
                    items: updateItem(items, index, 'attachmentId', media.id),
                  })
                }
                value={item.attachmentId}
                render={({ open }) => (
                  <Button onClick={open} variant="secondary">
                    {item.attachmentId
                      ? __('Changer le fichier', 'sage')
                      : __('Choisir un fichier', 'sage')}
                  </Button>
                )}
              />
            </MediaUploadCheck>

            <TextControl
              label={__('Texte alternatif (aperçu)', 'sage')}
              value={item.alt}
              onChange={(value) =>
                setLocaleData({
                  items: updateItem(items, index, 'alt', value),
                })
              }
            />

            <div style={{ display: 'flex', gap: '8px', flexWrap: 'wrap' }}>
              <Button
                variant="secondary"
                disabled={index === 0}
                onClick={() =>
                  setLocaleData({ items: moveItem(items, index, -1) })
                }
              >
                {__('Monter', 'sage')}
              </Button>
              <Button
                variant="secondary"
                disabled={index === items.length - 1}
                onClick={() =>
                  setLocaleData({ items: moveItem(items, index, 1) })
                }
              >
                {__('Descendre', 'sage')}
              </Button>
              <Button
                isDestructive
                variant="secondary"
                onClick={() =>
                  setLocaleData({
                    items: items.filter((_, itemIndex) => itemIndex !== index),
                  })
                }
              >
                {__('Supprimer', 'sage')}
              </Button>
            </div>
          </PanelBody>
        ))}

        <PanelBody
          title={__('Fichiers du kit', 'sage')}
          initialOpen={items.length === 0}
        >
          <Button
            variant="primary"
            onClick={() =>
              setLocaleData({
                items: [...items, emptyItem()],
              })
            }
          >
            {__('Ajouter un fichier', 'sage')}
          </Button>
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <ServerSideRender
          block={metadata.name}
          attributes={renderAttributes}
        />
      </div>
    </Fragment>
  );
};

registerBlockType(metadata.name, {
  ...blockSettings,
  edit: Edit,
  save: () => null,
});
