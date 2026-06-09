import {
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
  useBlockProps,
} from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';
import { Button, PanelBody, TextControl } from '@wordpress/components';
import { Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { ServerSideRender } from '@wordpress/server-side-render';
import metadata from './block.json';

const { render, $schema, ...blockSettings } = metadata;

const emptySupporter = () => ({
  name: '',
  url: '',
  logoId: 0,
});

const updateSupporter = (supporters, index, field, value) =>
  supporters.map((item, itemIndex) =>
    itemIndex === index ? { ...item, [field]: value } : item,
  );

const Edit = ({ attributes, setAttributes }) => {
  const blockProps = useBlockProps();
  const supporters = attributes.supporters ?? [];

  return (
    <Fragment>
      <InspectorControls>
        <PanelBody title={__('Contenu', 'sage')} initialOpen>
          <TextControl
            label={__('Titre', 'sage')}
            value={attributes.title}
            onChange={(value) => setAttributes({ title: value })}
          />
        </PanelBody>

        {supporters.map((supporter, index) => (
          <PanelBody
            key={index}
            title={`${__('Soutien', 'sage')} ${index + 1}`}
            initialOpen={index === 0}
          >
            <MediaUploadCheck>
              <MediaUpload
                onSelect={(media) =>
                  setAttributes({
                    supporters: updateSupporter(
                      supporters,
                      index,
                      'logoId',
                      media.id,
                    ),
                  })
                }
                allowedTypes={['image']}
                value={supporter.logoId}
                render={({ open }) => (
                  <Button onClick={open} variant="secondary">
                    {supporter.logoId
                      ? __('Changer le logo', 'sage')
                      : __('Choisir un logo', 'sage')}
                  </Button>
                )}
              />
            </MediaUploadCheck>

            <TextControl
              label={__('Nom', 'sage')}
              value={supporter.name}
              onChange={(value) =>
                setAttributes({
                  supporters: updateSupporter(supporters, index, 'name', value),
                })
              }
            />

            <TextControl
              label={__('URL', 'sage')}
              value={supporter.url}
              onChange={(value) =>
                setAttributes({
                  supporters: updateSupporter(supporters, index, 'url', value),
                })
              }
              type="url"
            />

            <Button
              isDestructive
              variant="secondary"
              onClick={() =>
                setAttributes({
                  supporters: supporters.filter(
                    (_, itemIndex) => itemIndex !== index,
                  ),
                })
              }
            >
              {__('Supprimer', 'sage')}
            </Button>
          </PanelBody>
        ))}

        <PanelBody
          title={__('Soutiens', 'sage')}
          initialOpen={supporters.length === 0}
        >
          <Button
            variant="primary"
            onClick={() =>
              setAttributes({
                supporters: [...supporters, emptySupporter()],
              })
            }
          >
            {__('Ajouter un soutien', 'sage')}
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
