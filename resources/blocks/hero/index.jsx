import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';
import {
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
  { label: __('Default', 'sage'), value: 'default' },
];

const ButtonFields = ({ title, prefix, attributes, setAttributes }) => {
  const labelKey = `${prefix}Label`;
  const urlKey = `${prefix}Url`;
  const colorKey = `${prefix}Color`;

  return (
    <PanelBody title={title} initialOpen={false}>
      <TextControl
        label={__('Label', 'sage')}
        value={attributes[labelKey]}
        onChange={(value) => setAttributes({ [labelKey]: value })}
      />
      <TextControl
        label={__('URL', 'sage')}
        value={attributes[urlKey]}
        onChange={(value) => setAttributes({ [urlKey]: value })}
        type="url"
      />
      <SelectControl
        label={__('Couleur', 'sage')}
        value={attributes[colorKey]}
        options={colorOptions}
        onChange={(value) => setAttributes({ [colorKey]: value })}
      />
    </PanelBody>
  );
};

const Edit = ({ attributes, setAttributes }) => {
  const blockProps = useBlockProps();

  return (
    <Fragment>
      <InspectorControls>
        <PanelBody title={__('Contenu', 'sage')} initialOpen>
          <TextControl
            label={__('Titre', 'sage')}
            value={attributes.title}
            onChange={(value) => setAttributes({ title: value })}
          />
          <TextareaControl
            label={__('Description', 'sage')}
            value={attributes.description}
            onChange={(value) => setAttributes({ description: value })}
            rows={4}
          />
          <TextControl
            label={__('Mots surlignés', 'sage')}
            help={__(
              'Saisissez les mots ou expressions à surligner en bleu, séparés par des virgules.',
              'sage',
            )}
            value={attributes.highlightWords}
            onChange={(value) => setAttributes({ highlightWords: value })}
          />
        </PanelBody>

        <ButtonFields
          title={__('Bouton 1', 'sage')}
          prefix="button1"
          attributes={attributes}
          setAttributes={setAttributes}
        />

        <ButtonFields
          title={__('Bouton 2', 'sage')}
          prefix="button2"
          attributes={attributes}
          setAttributes={setAttributes}
        />
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
