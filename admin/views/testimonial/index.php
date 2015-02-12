<div class="group-testimonials browse">
    <p>
        <?=lang('testimonials_index_intro')?>
    </p>
    <?php

        echo \Nails\Admin\Helper::loadSearch($search);
        echo \Nails\Admin\Helper::loadPagination($pagination);

    ?>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th class="quote"><?=lang('testimonials_index_th_quote')?></th>
                    <th class="actions"><?=lang('testimonials_index_th_actions')?></th>
                </tr>
            </thead>
            <tbody>
                <?php

                if ($testimonials) {

                    foreach ($testimonials as $testimonial) {

                        echo '<tr>';
                            echo '<td class="quote">';
                                echo $testimonial->quote_by;
                                echo '<small>' . word_limiter(strip_tags($testimonial->quote), 50) . '</small>';
                            echo '</td>';
                            echo '<td class="actions">';

                                if (userHasPermission('admin.testimonial:0.can_edit_objects')) :

                                    echo anchor('admin/testimonial/testimonial/edit/' . $testimonial->id, lang('action_edit'), 'class="awesome small"');

                                endif;

                                if (userHasPermission('admin.testimonial:0.can_delete_objects')) :

                                    echo anchor('admin/testimonial/testimonial/delete/' . $testimonial->id, lang('action_delete'), 'class="awesome red small confirm" data-title="Are you sure?" data-body="You cannot undo this action"');

                                endif;

                            echo '</td>';
                        echo '<tr>';
                    }

                } else {

                    ?>
                    <tr>
                        <td colspan="2" class="no-data"><?=lang('testimonials_index_no_testimonials')?></td>
                    </tr>
                    <?php
                }

                ?>
            </tbody>
        </table>
    </div>
    <?php

        echo \Nails\Admin\Helper::loadPagination($pagination);

    ?>
</div>