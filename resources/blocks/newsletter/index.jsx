import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';
import { PanelBody, SelectControl } from '@wordpress/components';
import { Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { ServerSideRender } from '@wordpress/server-side-render';
import metadata from './block.json';

const { render, $schema, ...blockSettings } = metadata;

const colorOptions = [
  { label: __('Rose', 'sage'), value: 'pink' },
  { label: __('Bleu', 'sage'), value: 'blue' },
  { label: __('Beige', 'sage'), value: 'beige' },
];

const Edit = ({ attributes, setAttributes }) => {
  const blockProps = useBlockProps();

  return (
    <Fragment>
      <InspectorControls>
        <PanelBody title={__('Apparence', 'sage')} initialOpen>
          <SelectControl
            label={__('Couleur du bouton', 'sage')}
            value={attributes.buttonColor}
            options={colorOptions}
            onChange={(value) => setAttributes({ buttonColor: value })}
          />
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
