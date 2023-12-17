<?php

use Application\Models\User;
use System\Core\Model;
use System\Helpers\URL;
use Application\Models\Language;

$prefix = $userInfo['type'] == User::TYPE_ADMIN ? 'admin' : 'entities';
$lang = Model::get(Language::class);
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
                        <div class="filter-right"><a href="<?= URL::full("{$prefix}/add-commission") ?>"
                                                     class="btn btn-primary"><?= $lang('add') ?></a></div>
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th><?= $lang('entity_name') ?></th>
                                <th><?= $lang('advisor_name') ?></th>
                                <th><?= $lang('entity_commission') ?></th>
                                <th><?= $lang('advisor_commission') ?></th>
                                <th><?= $lang('action') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($commissions as $commission) : ?>
                                <tr>
                                    <td><?= $commission['entity']['name'] ?></td>
                                    <td><?= $commission['advisor']['name'] ?></td>
                                    <td><?= $commission['entity_commission'] ?></td>
                                    <td><?= $commission['advisor_commission'] ?></td>
                                    <td>
                                        <a class="btn primary-btn"
                                           href="<?= URL::full("$prefix/edit-commission/" . $commission['id']) ?>">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th><?= $lang('entity_name') ?></th>
                                <th><?= $lang('advisor_name') ?></th>
                                <th><?= $lang('entity_commission') ?></th>
                                <th><?= $lang('advisor_commission') ?></th>
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
        $(function () {
            $("#table").DataTable({
                "order": [
                    [0, "desc"]
                ],
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            })
        });
    </script>
</define>