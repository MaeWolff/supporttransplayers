import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';
import { PanelBody, TextareaControl } from '@wordpress/components';
import { Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { ServerSideRender } from '@wordpress/server-side-render';
import metadata from './block.json';

const { render, $schema, ...blockSettings } = metadata;

const Edit = ({ attributes, setAttributes }) => {
  const blockProps = useBlockProps();

  return (
    <Fragment>
      <InspectorControls>
        <PanelBody title={__('Markdown', 'sage')} initialOpen>
          <TextareaControl
            label={__('Contenu', 'sage')}
            help={__(
              'Syntaxe : # Titre · ## Section · ### Sous-section · [lien](url)',
              'sage',
            )}
            value={attributes.content}
            onChange={(value) => setAttributes({ content: value })}
            rows={24}
            className="stp-legal-text-editor"
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
