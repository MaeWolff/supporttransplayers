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
  emptyResourcesLocale,
  ensureResourcesLocales,
  updateLocaleField,
} from '../shared/locales';
import metadata from './block.json';

const { render, $schema, ...blockSettings } = metadata;

const emptyItem = () => ({
  title: '',
  description: '',
  attachmentId: 0,
});

const updateItem = (items, index, field, value) =>
  items.map((item, itemIndex) =>
    itemIndex === index ? { ...item, [field]: value } : item,
  );

const Edit = ({ attributes, setAttributes }) => {
  const blockProps = useBlockProps();
  const [activeLocale, setActiveLocale] = useState('fr');
  const { locales } = useMemo(
    () => ensureResourcesLocales(attributes),
    [attributes],
  );
  const localeData = locales[activeLocale] ?? emptyResourcesLocale();
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
    () => ensureResourcesLocales(attributes),
    [attributes],
  );

  return (
    <Fragment>
      <InspectorControls>
        <PanelBody title={__('Ressources multilingues', 'sage')} initialOpen>
          <Notice status="info" isDismissible={false}>
            {__(
              'Renseignez le contenu pour chaque langue. L’enregistrement de la page française synchronise les traductions Polylang.',
              'sage',
            )}
          </Notice>

          <LocaleTabs activeLocale={activeLocale} onChange={setActiveLocale} />

          <TextControl
            label={__('Titre de section', 'sage')}
            value={localeData.sectionTitle}
            onChange={(value) =>
              setAttributes({
                locales: updateLocaleField(
                  locales,
                  activeLocale,
                  'sectionTitle',
                  value,
                ),
              })
            }
          />
        </PanelBody>

        {items.map((item, index) => (
          <PanelBody
            key={`${activeLocale}-${index}`}
            title={`${__('Ressource', 'sage')} ${index + 1} (${activeLocale.toUpperCase()})`}
            initialOpen={index === 0}
          >
            <TextControl
              label={__('Titre', 'sage')}
              value={item.title}
              onChange={(value) =>
                setLocaleData({
                  items: updateItem(items, index, 'title', value),
                })
              }
            />

            <TextareaControl
              label={__('Description', 'sage')}
              value={item.description}
              onChange={(value) =>
                setLocaleData({
                  items: updateItem(items, index, 'description', value),
                })
              }
            />

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
          </PanelBody>
        ))}

        <PanelBody
          title={__('Ressources', 'sage')}
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
            {__('Ajouter une ressource', 'sage')}
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
