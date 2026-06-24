/* global jQuery, wp */
(function ($) {
  function serializeSocial(container) {
    const links = [];

    container.find('.stp-customizer-social-row').each(function () {
      const row = $(this);
      const label = row.find('.stp-social-label').val().trim();
      const url = row.find('.stp-social-url').val().trim();
      const platform = row.find('.stp-social-platform').val();

      if (label === '' || url === '') {
        return;
      }

      links.push({ label, url, platform });
    });

    return JSON.stringify(links);
  }

  function syncSocial(container) {
    const inputId = container.data('input-id');
    const value = serializeSocial(container);
    const input = $('#' + inputId);

    input.val(value).trigger('change');
  }

  function bindSocialContainer(container) {
    container.on('input change', '.stp-social-label, .stp-social-url, .stp-social-platform', function () {
      syncSocial(container);
    });

    container.on('click', '.stp-social-add', function (event) {
      event.preventDefault();

      const row = $(`
        <div class="stp-customizer-social-row" style="margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #dcdcde;">
          <p>
            <label>Label (accessibilité)</label>
            <input type="text" class="widefat stp-social-label" value="" />
          </p>
          <p>
            <label>URL</label>
            <input type="url" class="widefat stp-social-url" value="" />
          </p>
          <p>
            <label>Plateforme</label>
            <select class="widefat stp-social-platform">
              <option value=""></option>
              <option value="instagram">Instagram</option>
              <option value="twitter">X / Twitter</option>
              <option value="facebook">Facebook</option>
              <option value="linkedin">LinkedIn</option>
              <option value="youtube">YouTube</option>
              <option value="tiktok">TikTok</option>
              <option value="mastodon">Mastodon</option>
            </select>
          </p>
          <button type="button" class="button stp-social-remove">Supprimer</button>
        </div>
      `);

      container.find('.stp-social-add').before(row);
    });

    container.on('click', '.stp-social-remove', function (event) {
      event.preventDefault();

      const rows = container.find('.stp-customizer-social-row');

      if (rows.length <= 1) {
        rows.find('input, select').val('');
        syncSocial(container);

        return;
      }

      $(this).closest('.stp-customizer-social-row').remove();
      syncSocial(container);
    });
  }

  wp.customize.bind('ready', function () {
    $('.stp-customizer-social').each(function () {
      bindSocialContainer($(this));
    });
  });
})(jQuery);
