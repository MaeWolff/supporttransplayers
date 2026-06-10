import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';
import {
  Button,
  PanelBody,
  SelectControl,
  TextareaControl,
  TextControl,
} from '@wordpress/components';
import { Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { ServerSideRender } from '@wordpress/server-side-render';
import metadata from './block.json';

const { render, $schema, ...blockSettings } = metadata;

const colorOptions = [
  { label: __('Rose', 'sage'), value: 'pink' },
  { label: __('Bleu', 'sage'), value: 'blue' },
  { label: __('Beige', 'sage'), value: 'beige' },
  { label: __('Blanc', 'sage'), value: 'white' },
];

const sizeOptions = [
  { label: __('Grande', 'sage'), value: 'large' },
  { label: __('Moyenne', 'sage'), value: 'medium' },
  { label: __('Petite', 'sage'), value: 'small' },
];

const emptyItem = () => ({
  title: '',
  body: '',
  url: '',
  linkLabel: '',
  color: 'pink',
  size: 'medium',
});

const updateItem = (items, index, field, value) =>
  items.map((item, itemIndex) =>
    itemIndex === index ? { ...item, [field]: value } : item,
  );

const Edit = ({ attributes, setAttributes }) => {
  const blockProps = useBlockProps();
  const items = attributes.items ?? [];

  return (
    <Fragment>
      <InspectorControls>
        <PanelBody title={__('Section', 'sage')} initialOpen>
          <TextControl
            label={__('Titre de section', 'sage')}
            value={attributes.sectionTitle}
            onChange={(value) => setAttributes({ sectionTitle: value })}
          />
        </PanelBody>

        {items.map((item, index) => (
          <PanelBody
            key={index}
            title={`${__('Tuile', 'sage')} ${index + 1}`}
            initialOpen={index === 0}
          >
            <TextControl
              label={__('Titre', 'sage')}
              value={item.title}
              onChange={(value) =>
                setAttributes({
                  items: updateItem(items, index, 'title', value),
                })
              }
            />

            <TextareaControl
              label={__('Texte', 'sage')}
              value={item.body}
              onChange={(value) =>
                setAttributes({
                  items: updateItem(items, index, 'body', value),
                })
              }
              rows={4}
            />

            <TextControl
              label={__('URL', 'sage')}
              value={item.url}
              onChange={(value) =>
                setAttributes({
                  items: updateItem(items, index, 'url', value),
                })
              }
              type="url"
            />

            <TextControl
              label={__('Label du lien', 'sage')}
              value={item.linkLabel}
              onChange={(value) =>
                setAttributes({
                  items: updateItem(items, index, 'linkLabel', value),
                })
              }
              help={__('Laisser vide pour « En savoir plus ».', 'sage')}
            />

            <SelectControl
              label={__('Couleur', 'sage')}
              value={item.color}
              options={colorOptions}
              onChange={(value) =>
                setAttributes({
                  items: updateItem(items, index, 'color', value),
                })
              }
            />

            <SelectControl
              label={__('Taille', 'sage')}
              value={item.size}
              options={sizeOptions}
              onChange={(value) =>
                setAttributes({
                  items: updateItem(items, index, 'size', value),
                })
              }
            />

            <Button
              isDestructive
              variant="secondary"
              onClick={() =>
                setAttributes({
                  items: items.filter((_, itemIndex) => itemIndex !== index),
                })
              }
            >
              {__('Supprimer', 'sage')}
            </Button>
          </PanelBody>
        ))}

        <PanelBody
          title={__('Tuiles', 'sage')}
          initialOpen={items.length === 0}
        >
          <Button
            variant="primary"
            onClick={() =>
              setAttributes({
                items: [...items, emptyItem()],
              })
            }
          >
            {__('Ajouter une tuile', 'sage')}
          </Button>
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <ServerSideRender block={metadata.name} attributes={attributes} />
      </div>
    </Fragment>
  );
};

registerBlockType(metadata.name, {
  ...blockSettings,
  edit: Edit,
  save: () => null,
});
