<div class="group-testimonials browse">
    <p>
        <?=lang('testimonials_index_intro')?>
    </p>
    <?php

        echo adminHelper('loadSearch', $search);
        echo adminHelper('loadPagination', $pagination);

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

                                if (userHasPermission('admin:testimonial:testimonial:edit')) :

                                    echo anchor(
                                        'admin/testimonial/testimonial/edit/' . $testimonial->id,
                                        lang('action_edit'),
                                        'class="btn btn-xs btn-primary"'
                                    );

                                endif;

                                if (userHasPermission('admin:testimonial:testimonial:delete')) :

                                    echo anchor(
                                        'admin/testimonial/testimonial/delete/' . $testimonial->id,
                                        lang('action_delete'),
                                        'class="btn btn-xs btn-danger confirm" data-body="You cannot undo this action"'
                                    );

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

        echo adminHelper('loadPagination', $pagination);

    ?>
</div>