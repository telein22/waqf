<?php

use System\Core\Model;
use System\Helpers\URL;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');
?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- <div class="card-header">
                        <h3 class="card-title">DataTable with default features</h3>
                    </div> -->
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?= $lang('owner'); ?></th>
                                    <th><?= $lang('entity_type') ?></th>
                                    <th><?= $lang('entity_name') ?></th>
                                    <th><?= $lang('avg_rating') ?></th>
                                    <th><?= $lang('action') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reviews as $review) : ?>
                                    <tr>
                                        <td>
                                            <a target="_blank" href="<?= URL::full('profile/' . $review['entity_owner_id']) ?>">
                                                <?= htmlentities($review['user']['name']) .' / ' . htmlentities($review['user']['email'])?>
                                            </a>
                                        </td>
                                        <td>
                                            <?= $lang($review['entity_type']); ?>
                                        </td>
                                        <td>
                                            <?php if ( isset($review['entity_url']) ): ?>
                                                <a target="_blank" href="<?= $review['entity_url'] ?>">
                                                    <?= htmlentities($review['entity_name']) ?>
                                                </a>
                                            <?php else: ?>
                                                <?= htmlentities($review['entity_name']) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= number_format($review['avg_star'], 1) ?>
                                        </td>
                                        <td>
                                            <a class="btn" href="<?= URL::full('admin/reviews/view/' . $review['entity_id'] . '/' . $review['entity_type']) ?>">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                <th><?= $lang('owner'); ?></th>
                                    <th><?= $lang('entity_type') ?></th>
                                    <th><?= $lang('entity_name') ?></th>
                                    <th><?= $lang('avg_rating') ?></th>
                                    <th><?= $lang('action') ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</section>


<define footer_js>
    <script>
        $(function() {
            $("#table").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            })
        });
    </script>
</define>