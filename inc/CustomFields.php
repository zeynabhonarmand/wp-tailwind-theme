<?php
class CustomFields
{
    static function add_datetime_picker($fieldname, $title, $postType = 'post')
    {
        add_action('admin_enqueue_scripts', function () use ($fieldname) {
            // Check if we're on the 'event' post type page
            $screen = get_current_screen();
            if (isset($screen->post_type) && $screen->post_type === 'event') {
                wp_enqueue_style('jalali-datepicker-css', 'https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css');
                wp_enqueue_script('jalali-datepicker-js', 'https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js', array('jquery'), null, true);
            }
        });

        add_action('add_meta_boxes', function () use ($fieldname, $postType, $title) {
            // Add a custom meta box
            add_meta_box(
                'jalali_datepicker_metabox_' . $fieldname, // Unique ID based on field name
                __($title, 'text-domain'), // Box title, change 'text-domain' to your theme/plugin text domain
                function ($post) use ($fieldname) {
                    // Use nonce for verification, unique per field
                    wp_nonce_field('save_' . $fieldname, $fieldname . '_nonce');

                    $field_value = get_post_meta($post->ID, $fieldname, true);

                    // Display the field for the Jalali date picker
                    echo '<input style="direction:ltr" data-jdp type="text" id="' . esc_attr($fieldname) . '" name="' . esc_attr($fieldname) . '" value="' . esc_attr($field_value) . '" />';
                    echo '<script>
      
                        jQuery(document).ready(function($) {
                          jalaliDatepicker.startWatch({time:true});
      
                        });
                    </script>';
                },
                $postType, // Post type
                'normal', // Context
                'high' // Priority
            );
        });

        add_action('save_post', function ($post_id) use ($fieldname) {
            // Check if the nonce field is set and verify it.
            if (!isset($_POST[$fieldname . '_nonce']) || !wp_verify_nonce($_POST[$fieldname . '_nonce'], 'save_' . $fieldname)) {
                return;
            }

            // Check user's permission.
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }

            // Sanitize and save the input.
            if (isset($_POST[$fieldname])) {
                update_post_meta($post_id, $fieldname, sanitize_text_field($_POST[$fieldname]));
            }
        });
    }

    static function add_text_field($fieldName, $title, $postType = 'post')
    {
        add_action('add_meta_boxes', function () use ($postType, $fieldName, $title) {
            add_meta_box(
                "{$fieldName}_details",
                __($title),
                function ($post) use ($fieldName, $title, $postType) {
                    // Add a nonce field so we can check for it later.
                    wp_nonce_field("{$fieldName}_details_save", "{$fieldName}_details_nonce");

                    $value = get_post_meta($post->ID, $fieldName, true);

                    // echo '<label for="'.$fieldName.'_field">' . __($title) . '</label>';
                    echo '<input type="text"  style="width:100%;" id="' . $fieldName . '_field" name="' . $fieldName . '_field" value="' . esc_attr($value) . '" />';
                },
                $postType,
                'normal',
                'high'
            );
        });
        add_action(
            'save_post',
            function ($post_id) use ($fieldName) {
                // Check if our nonce is set.
                if (!isset($_POST[$fieldName . '_details_nonce'])) {
                    return;
                }

                // Verify that the nonce is valid.
                if (!wp_verify_nonce($_POST[$fieldName . '_details_nonce'], $fieldName . '_details_save')) {
                    return;
                }

                // If this is an autosave, our form has not been submitted, so we don't want to do anything.
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                    return;
                }

                // Check the user's permissions.
                if (!current_user_can('edit_post', $post_id)) {
                    return;
                }

                // Make sure that it is set.
                if (!isset($_POST[$fieldName . '_field'])) {
                    return;
                }

                // Sanitize user input.
                $value = sanitize_text_field($_POST[$fieldName . '_field']);

                // Update the meta field in the database.
                update_post_meta($post_id, $fieldName, $value);
            }
        );
    }
    static function add_textarea_field($fieldName, $title, $postType = 'post')
    {
        add_action('add_meta_boxes', function () use ($postType, $fieldName, $title) {
            add_meta_box(
                "{$fieldName}_details",
                __($title),
                function ($post) use ($fieldName, $title, $postType) {
                    // Add a nonce field so we can check for it later.
                    wp_nonce_field("{$fieldName}_details_save", "{$fieldName}_details_nonce");

                    $value = get_post_meta($post->ID, $fieldName, true);

                    // echo '<label for="'.$fieldName.'_field">' . __($title) . '</label>';
                    echo '<textarea rows="4" style="width:100%;" type="text" id="' . $fieldName . '_field" name="' . $fieldName . '_field">' . esc_attr($value) . '</textarea>';
                },
                $postType,
                'normal',
                'high'
            );
        });
        add_action(
            'save_post',
            function ($post_id) use ($fieldName) {
                // Check if our nonce is set.
                if (!isset($_POST[$fieldName . '_details_nonce'])) {
                    return;
                }

                // Verify that the nonce is valid.
                if (!wp_verify_nonce($_POST[$fieldName . '_details_nonce'], $fieldName . '_details_save')) {
                    return;
                }

                // If this is an autosave, our form has not been submitted, so we don't want to do anything.
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                    return;
                }

                // Check the user's permissions.
                if (!current_user_can('edit_post', $post_id)) {
                    return;
                }

                // Make sure that it is set.
                if (!isset($_POST[$fieldName . '_field'])) {
                    return;
                }

                // Sanitize user input.
                $value = sanitize_text_field($_POST[$fieldName . '_field']);

                // Update the meta field in the database.
                update_post_meta($post_id, $fieldName, $value);
            }
        );
    }
    static function add_select_field($fieldName, $title, $options, $postType = 'post',)
    {
        add_action('add_meta_boxes', function () use ($postType, $fieldName, $title, $options) {
            add_meta_box(
                "{$fieldName}_details",
                __($title),
                function ($post) use ($fieldName, $title, $postType, $options) {
                    // Add a nonce field so we can check for it later.
                    wp_nonce_field("{$fieldName}_save", "{$fieldName}_nonce");

                    $value = get_post_meta($post->ID, $fieldName, true);

                    // echo '<label for="'.$fieldName.'_field">' . __($title) . '</label>';
                    echo '<select id="' . $fieldName . '_field" name="' . $fieldName . '_field" >';
                    echo "<option value='' >{$title}</option>";
                    foreach ($options as $option) {
                        $selected = $value == $option['value'] ? "selected" : "";
                        echo "<option value='{$option['value']}' {$selected}>{$option['name']}</option>";
                    }
                    echo "</select>";
                },
                $postType,
                'normal',
                'high'
            );
        });
        add_action(
            'save_post',
            function ($post_id) use ($fieldName) {
                // Check if our nonce is set.
                if (!isset($_POST[$fieldName . '_nonce'])) {
                    return;
                }

                // Verify that the nonce is valid.
                if (!wp_verify_nonce($_POST[$fieldName . '_nonce'], $fieldName . '_save')) {
                    return;
                }

                // If this is an autosave, our form has not been submitted, so we don't want to do anything.
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                    return;
                }

                // Check the user's permissions.
                if (!current_user_can('edit_post', $post_id)) {
                    return;
                }

                // Make sure that it is set.
                if (!isset($_POST[$fieldName . '_field'])) {
                    return;
                }

                // Sanitize user input.
                $value = sanitize_text_field($_POST[$fieldName . '_field']);

                // Update the meta field in the database.
                update_post_meta($post_id, $fieldName, $value);
            }
        );
    }
    static function add_checkbox_field($fieldName, $title, $postType = 'post')
    {
        add_action('add_meta_boxes', function () use ($postType, $fieldName, $title) {
            add_meta_box(
                "{$fieldName}_details",
                __($title),
                function ($post) use ($fieldName, $title, $postType) {
                    // Add a nonce field so we can check for it later.
                    wp_nonce_field("{$fieldName}_details_save", "{$fieldName}_details_nonce");

                    $value = get_post_meta($post->ID, $fieldName, true);
                    $checked = $value == "yes" ? 'checked' : '';

                    echo '<input type="checkbox" id="' . $fieldName . '_field" name="' . $fieldName . '_field" ' . $checked . ' size="25" />';
                },
                $postType,
                'normal',
                'high'
            );
        });
        add_action(
            'save_post',
            function ($post_id) use ($fieldName) {
                // Check if our nonce is set.
                if (!isset($_POST[$fieldName . '_details_nonce'])) {
                    return;
                }

                // Verify that the nonce is valid.
                if (!wp_verify_nonce($_POST[$fieldName . '_details_nonce'], $fieldName . '_details_save')) {
                    return;
                }

                // If this is an autosave, our form has not been submitted, so we don't want to do anything.
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                    return;
                }

                // Check the user's permissions.
                if (!current_user_can('edit_post', $post_id)) {
                    return;
                }

                // Sanitize user input.
                $new_value = isset($_POST[$fieldName . '_field']) ? 'yes' : 'no';

                // Update the meta field in the database.
                update_post_meta($post_id, $fieldName, $new_value);
            }
        );
    }

    static function add_attachment_field($fieldName, $title, $type = "image", $postType = 'post')
    {
        add_action('add_meta_boxes', function () use ($postType, $fieldName, $title, $type) {
            add_meta_box(
                "{$fieldName}_details",
                __($title),
                function ($post) use ($postType, $fieldName, $title, $type) {
                    global $_wp_additional_image_sizes;

                    $attachmentURL = get_post_meta($post->ID, $fieldName, true);

                    self::_add_attachment($fieldName, $title, $type, $attachmentURL);
                },
                $postType,
                'side',
                'high'
            );
        });

        add_action('save_post', function ($post_id) use ($fieldName) {
            if (isset($_POST[$fieldName])) {
                $imageId = $_POST[$fieldName];
                update_post_meta($post_id, $fieldName, $imageId);
            }
        }, 10, 1);
    }
    static function add_category_attachment_field($fieldName, $title, $type = "image", $content_width = 254)
    {
        add_action(
            'category_add_form_fields',
            function () use ($fieldName, $title, $type, $content_width) {
                echo "<div class='form-field'>
                <label>$title</label>";
                self::_add_attachment($fieldName, $title, $type, null, $content_width);
                echo '</div>';
            }
        );
        add_action(
            'category_edit_form_fields',
            function ($term) use ($fieldName, $title, $type, $content_width) {
                $attachmentURL = get_term_meta($term->term_id, $fieldName, true);
                echo "<tr><th scope='row' valign='top'> 
                <label>$title</label></th><td>";
                self::_add_attachment($fieldName, $title, $type, $attachmentURL, $content_width);
                echo '</td></tr>';
            }
        );

        add_action('edited_category', function ($term_id) use ($fieldName) {
            self::save_category_field($fieldName, $term_id);
        });

        // Save custom field data when category is created
        add_action('create_category', function ($term_id) use ($fieldName) {
            self::save_category_field($fieldName, $term_id);
        });
    }
    static function save_category_field($fieldName, $term_id)
    {
        if (isset($_POST[$fieldName])) {
            update_term_meta($term_id, $fieldName, ($_POST[$fieldName]));
        }
    }

    static function _add_attachment($fieldName, $title, $type = "image", $attachmentURL = null, $content_width = 254)
    {
        global $_wp_additional_image_sizes;

        // Backward compatibility
        if ($attachmentURL && is_numeric($attachmentURL)) {
            $attachmentData = get_post($attachmentURL);
            $attachmentURL = $attachmentData->guid;
        }
        // $metaData = wp_get_attachment_metadata($attachmentId);
        // print_r([$attachmentId, $attachmentData]);
        // return;

        if ($type == "image") {
            if ($attachmentURL)

                $thumbnail_html = "<img src='$attachmentURL' style='width:{$content_width}px;height:auto;border:0;' />";
            else
                $thumbnail_html = "<img style='width:{$content_width}px;height:auto;border:0;display:none;' />";
        } elseif ($type == "file") {
            if ($attachmentURL)
                $thumbnail_html = "<a class='file-link' href='$attachmentURL'>" . basename($attachmentURL) . "</a>";
            else
                $thumbnail_html = "<a class='file-link' style='display:none'></a>";
        } elseif ($type == "video") {
            if ($attachmentURL)
                $thumbnail_html = "<video class='video-player' ><source src='{$attachmentURL}' /></video>";
            else
                $thumbnail_html = "";
        }


        $content = $thumbnail_html . "<br/>";
        $content .= '<button type="button" style="border:1px solid #00F;border-radius:5px;padding: 5px;float:right;margin-top:10px; ' . (!$attachmentURL ? "display:none" : "") . '" class="remove_button" >remove</button>';
        $content .= '<button type="button" style="border:1px solid #00F;border-radius:5px;padding: 5px;float:left;margin-top:10px; " class="upload_button">Set ' . $type . '</button>';
        $content .= '<input type="hidden" id="' . $fieldName . '"  name="' . $fieldName . '" value="' . esc_attr($attachmentURL) . '" />';

        echo "<div style='position:relative; padding-bottom:50px;' id='{$fieldName}_container'>" . $content, "</div>";
?>
        <script>
            jQuery(document).ready(function($) {

                // Uploading files
                var fileFrame;
                var containerId = '<?= $fieldName ?>_container';
                var fieldId = '<?= $fieldName ?>';
                var fieldType = '<?= $type ?>';

                $.fn.upload_<?= $fieldName ?> = function() {


                    // If the media frame already exists, reopen it.
                    if (fileFrame) {
                        fileFrame.open();
                        return;
                    }

                    let options = {
                        title: '<?= $title ?>',
                        multiple: false,
                    }
                    if (fieldType == "image") {
                        options.library = {
                            type: ['image']
                        }
                    }
                    if (fieldType == "video") {
                        options.library = {
                            type: ['video']
                        }
                    }
                    // Create the media frame.
                    fileFrame = wp.media.frames.fileFrame = wp.media(options);

                    // When an image is selected, run a callback.
                    fileFrame.on('select', function() {
                        var attachment = fileFrame.state().get('selection').first().toJSON();
                        $("#" + fieldId).val(attachment.url);
                        $("#" + containerId + " .remove_button").show();

                        // console.log(attachment);
                        if (fieldType == "file")
                            $("#" + containerId + " a").attr("href", attachment.url).html(attachment.url.replace(/^.*[\\/]/, '')).show();
                        else if (fieldType == "image")
                            $("#" + containerId + " img").attr('src', attachment.url).show();
                        else if (fieldType == "video") {
                            $("#" + containerId + " video").remove();
                            $("#" + containerId).prepend("<video class='video-player' ><source src=" + attachment.url + " /></video>");
                            // $("#" + containerId + " video").show();
                        }

                    });

                    // Finally, open the modal
                    fileFrame.open();
                };

                $("#" + containerId).on('click', '.upload_button', function(event) {
                    event.preventDefault();
                    $.fn.upload_<?= $fieldName ?>();
                });

                $("#" + containerId).on('click', '.remove_button', function(event) {
                    event.preventDefault();
                    $("#" + fieldId).val("");
                    $("#" + containerId + " .remove_button").hide();
                    if (fieldType == "image")
                        $("#" + containerId + " img").attr('src', '').hide();
                    else if (fieldType == "file")
                        $("#" + containerId + " a").hide();
                    else if (fieldType == "video")
                        $("#" + containerId + " video").remove();
                });

            });
        </script>
        <?php

    }

}
