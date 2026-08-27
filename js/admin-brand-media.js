/**
 * Brand edit screen — media picker for the detail hero image.
 *
 * Stores the attachment ID so the front end can render with
 * wp_get_attachment_image(), which pulls alt text from the Media Library.
 */
(function ($) {
    'use strict';

    $(document).on('click', '.tf-media-choose', function (event) {
        event.preventDefault();

        var $button = $(this);
        var $field = $button.closest('.tf-media-field');
        var frame = $field.data('tfFrame');

        if (!frame) {
            frame = wp.media({
                title: $button.data('title') || 'Select image',
                button: { text: $button.data('button') || 'Use this image' },
                library: { type: 'image' },
                multiple: false
            });

            frame.on('select', function () {
                var attachment = frame.state().get('selection').first().toJSON();
                var preview = attachment.sizes && attachment.sizes.medium
                    ? attachment.sizes.medium.url
                    : attachment.url;

                $field.find('.tf-media-id').val(attachment.id);
                $field.find('.tf-media-preview').html(
                    $('<img>', { src: preview, alt: attachment.alt || '' })
                );
                $field.find('.tf-media-remove').show();
                $field.find('.tf-media-alt-note').text(
                    attachment.alt
                        ? 'Alt text: "' + attachment.alt + '"'
                        : 'This image has no alt text set in the Media Library.'
                );
            });

            $field.data('tfFrame', frame);
        }

        frame.open();
    });

    $(document).on('click', '.tf-media-remove', function (event) {
        event.preventDefault();

        var $field = $(this).closest('.tf-media-field');

        $field.find('.tf-media-id').val('');
        $field.find('.tf-media-preview').empty();
        $field.find('.tf-media-alt-note').text('');
        $(this).hide();
    });
}(jQuery));
