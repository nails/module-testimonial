<div class="group-testimonials edit">
    <?=form_open()?>
    <fieldset id="edit-testimonials-meta">
        <legend><?=lang('testimonials_edit_legend')?></legend>
        <?php


            $field             = array();
            $field['key']      = 'quote';
            $field['label']    = lang('testimonials_edit_field_quote');
            $field['required'] = true;
            $field['default']  = isset($testimonial->quote) ? $testimonial->quote : '';

            echo form_field_wysiwyg($field);

            // --------------------------------------------------------------------------

            $field             = array();
            $field['key']      = 'quote_by';
            $field['label']    = lang('testimonials_edit_field_quote_by');
            $field['required'] = true;
            $field['default']  = isset($testimonial->quote_by) ? $testimonial->quote_by : '';

            echo form_field($field);

            // --------------------------------------------------------------------------

            $field             = array();
            $field['key']      = 'quote_dated';
            $field['label']    = lang('testimonials_edit_field_quote_dated');
            $field['default']  = isset($testimonial->quote_dated) ? $testimonial->quote_dated : '';

            echo form_field_date($field);

        ?>
    </fieldset>
    <p>
        <?=form_submit('submit', lang('action_save_changes'), 'class="btn btn-primary"');?>
    </p>
    <?=form_close();?>
</div>