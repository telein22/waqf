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
                    <form action="<?= URL::current() ?>">
                        <div class="card-header filter-wrapper">
                            <div class="col-lg-3 custom-flex-space">
                                <label class="mr-2" for="from"><?= $lang('from') ?></label>
                                <input type="date" required value="<?= !empty($from) && $from != '' ? date('Y-m-d', $from) : '' ?>" class="form-control" id="from" name="from">
                            </div>
                            <div class="col-lg-3 custom-flex-space">
                                <label class="mr-2" for="to"><?= $lang('to') ?></label>
                                <input type="date" required value="<?= !empty($to) && $to != '' ? date('Y-m-d', $to) : '' ?>" class="form-control" id="to" name="to">
                            </div>
                            <div class="align-items-center  mobile-mt-3">
                                <button type="submit" class="btn-sm btn btn-primary mt-0"><?= $lang('filter') ?></button>
                                <!-- <a href="<?= URL::current() ?>" class="btn-sm btn btn-secondary">Reset Filter</a> -->
                            </div>
                        </div>
                    </form>
                    <!-- <div class="card-header">
                        <h3 class="card-title">DataTable with default features</h3>
                    </div> -->
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?= $lang('user') ?> </th>
                                    <th><?= $lang('page') ?> </th>
                                    <th><?= $lang('visit') ?> </th>
                                    <th><?= $lang('time') ?> </th>
                                    <th><?= $lang('ip') ?> </th>
                                    <th><?= $lang('device') ?> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($trackers as $tracker) : ?>
                                    <tr>
                                        <td>
                                            <a target="_blank" href="<?= URL::full('profile/' . $tracker['user_id']) ?>">
                                                <?= htmlentities($tracker['name']) . ' / ' . htmlentities($tracker['email']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a target="_blank" href="<?= URL::full($tracker['uri']) ?>"><?= URL::full($tracker['uri']) ?></a>
                                        </td>
                                        <td>
                                            <?= $tracker['count'] ?>
                                        </td>
                                        <td><span style="display: none;"><?= $tracker['time'] ?></span><?= date('d-m-Y H:i', $tracker['time']) ?></td>
                                        <td><?= htmlentities($tracker['ip']) ?></td>
                                        <td><?= htmlentities($tracker['device']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th><?= $lang('user') ?> </th>
                                    <th><?= $lang('page') ?> </th>
                                    <th><?= $lang('visit') ?> </th>
                                    <th><?= $lang('time') ?> </th>
                                    <th><?= $lang('ip') ?> </th>
                                    <th><?= $lang('device') ?> </th>
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
                "order": [[ 3, "desc" ]],
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            })
        });
    </script>
</define>