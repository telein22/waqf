<?php

use Application\Helpers\ConversationHelper;
use Application\Helpers\ServiceHelper;
use Application\Helpers\WorkshopHelper;
use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\Order;
use Application\Models\Workshop;
use System\Core\Model;
use System\Helpers\URL;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');

?>
<!-- Main content -->
<section class="content billing">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div >
                <div class="card">
                    <div class="card-header ">
                        <form action="<?= URL::current() ?>">
                            <div class="filter-wrapper">
                                <div class="col-lg-3  custom-flex-space">
                                    <label class="mr-2" for="from"><?= $lang('from') ?></label>
                                    <input type="date" required value="<?= !empty($from) && $from != '' ? date('Y-m-d', $from) : '' ?>" class="form-control" id="from" name="from">
                                </div>
                                <div class="col-lg-3  custom-flex-space">
                                    <label class="mr-2" for="to"><?= $lang('to') ?></label>
                                    <input type="date" required value="<?= !empty($to) && $to != '' ? date('Y-m-d', $to) : ''  ?>" class="form-control" id="to" name="to">
                                </div>
                            </div>
                            <div class="filter-wrapper mt-3">
                                <div class="col-lg-3 col-sm-12 custom-flex-space">
                                    <label class="mr-2" for="from"><?= $lang('order_status') ?></label>
                                    <select name="status" class="form-control" id="status">
                                        <option value=""><?= $lang('all') ?></option>
                                        <!-- <option <?php // if (Order::STATUS_APPROVED == $status) echo 'selected' ?> value="<?php // echo Order::STATUS_APPROVED ?>"><?php // echo $lang(Order::STATUS_APPROVED) ?></option> -->
                                        <option <?php if (Order::STATUS_COMPLETED == $status) echo 'selected' ?> value="<?= Order::STATUS_COMPLETED ?>"><?= $lang(Order::STATUS_COMPLETED) ?></option>
                                        <option <?php if (Order::STATUS_PENDING == $status) echo 'selected' ?> value="<?= Order::STATUS_PENDING ?>"><?= $lang(Order::STATUS_PENDING) ?></option>
                                        <option <?php if (Order::STATUS_INCOMPLETE == $status) echo 'selected' ?> value="<?= Order::STATUS_INCOMPLETE ?>"><?= $lang(Order::STATUS_INCOMPLETE) ?></option>
                                        <option <?php if (Order::STATUS_CANCELED == $status) echo 'selected' ?> value="<?= Order::STATUS_CANCELED ?>"><?= $lang(Order::STATUS_CANCELED) ?></option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-sm-12 custom-flex-space">
                                    <label class="mr-2" for="from"><?= $lang('service_type') ?></label>
                                    <select name="type" class="form-control" id="type">
                                        <option value=""><?= $lang('all') ?></option>
                                        <option <?php if (Workshop::ENTITY_TYPE == $type) echo 'selected' ?> value="<?= Workshop::ENTITY_TYPE ?>"><?= $lang(Workshop::ENTITY_TYPE) ?></option>
                                        <option <?php if (Call::ENTITY_TYPE  == $type) echo 'selected' ?> value="<?= Call::ENTITY_TYPE ?>"><?= $lang(Call::ENTITY_TYPE) ?></option>
                                        <option <?php if (Conversation::ENTITY_TYPE == $type) echo 'selected' ?> value="<?= Conversation::ENTITY_TYPE ?>"><?= $lang(Conversation::ENTITY_TYPE) ?></option>
                                    </select>
                                </div>
                                <div class="align-items-center col-sm-12 mobile-mt-3">
                                    <button type="submit" class="btn-sm btn btn-primary mt-0"><?= $lang('filter') ?></button>
                                    <!-- <a href="<?= URL::current() ?>" class="btn-sm btn btn-secondary">Reset Filter</a> -->
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- <div class="card-header">
                        <h3 class="card-title">DataTable with default features</h3>
                    </div> -->
                    <!-- /.card-header -->
                    <div class="card-body">
                        <a href="<?= URL::full('entities/billing-csv?from=' . $from . '&to=' . $to . '&status=' . $status . '&type=' . $type) ?>" target="_blank" class="btn btn-primary"><?= $lang('export_csv') ?></a>
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <!--                                    <th>--><?php //echo $lang('action') ?><!--</th>-->
                                <th><?= $lang('owner') ?></th>
                                <th><?= $lang('user') ?></th>
                                <th><?= $lang('service_type') ?></th>
                                <th><?= $lang('service_name') ?></th>
                                <th><?= $lang('order_status') ?></th>
                                <th><?= $lang('service_status') ?></th>
                                <th><?= $lang('payment_status') ?></th>
                                <th><?= $lang('charity_advisor_amount') ?></th>
<!--                                <th>--><?//= $lang('admin_amount') ?><!--</th>-->
<!--                                <th>--><?//= $lang('final_amount') ?><!--</th>-->
<!--                                <th>--><?//= $lang('gateway_amount') ?><!--</th>-->
<!--                                <th>--><?//= $lang('total_amount') ?><!--</th>-->
                                <th><?= $lang('coupon_discount') ?></th>
                                <th><?= $lang('id') ?></th>
                                <th><?= $lang('ref') ?></th>
                                <th><?= $lang('entity_id') ?></th>
                                <th><?= $lang('charities') ?></th>
                                <th><?= $lang('vat_full') ?></th>
                                <th><?= $lang('hold') ?></th>
                                <th><?= $lang('is_expired') ?></th>
                                <th><?= $lang('created_at') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($orders as $order) : ?>
                                <tr>

                                    <!--                                        <td>-->
                                    <!--                                            <a class="btn btn-primary show-details mt-1" href="#!" data-id="--><?php //echo $order['id'] ?><!--">-->
                                    <!--                                                --><?php //echo $lang('more') ?>
                                    <!--                                            </a>-->
                                    <!--                                            --><?php //if ($order['status'] == Order::STATUS_APPROVED) : ?>
                                    <!--                                                --><?php //// if ($order['in_hold'] == 0) : ?>
                                    <!--                                                    <a class="btn btn-primary cancel-status mt-1" href="#!" data-id="--><?php //echo $order['id'] ?><!--">-->
                                    <!--                                                        --><?php //echo $lang('cancel') ?>
                                    <!--                                                    </a>-->
                                    <!--                                                    -->
                                    <!--                                                --><?php //// endif; ?><!--                                                                                                -->
                                    <!--                                            --><?php //endif; ?>
                                    <!--                                            --><?php //if ( $order['in_hold'] == 0 ):  ?>
                                    <!--                                            <a class="btn btn-primary hold-status mt-1" href="#!" data-id="--><?php //echo $order['id'] ?><!--" data-value="1">-->
                                    <!--                                                --><?php //echo $lang('hold') ?>
                                    <!--                                            </a>-->
                                    <!--                                            --><?php //else: ?>
                                    <!--                                            <a class="btn btn-primary hold-status mt-1" href="#!" data-id="--><?php //echo $order['id'] ?><!--" data-value="0">-->
                                    <!--                                                --><?php //echo $lang('unhold') ?>
                                    <!--                                            </a>-->
                                    <!--                                            --><?php //endif; ?>
                                    <!-- <a target="_blank" class="btn btn-primary mt-1" href="<?= URL::full('admin/transfers/' . $order['id']) ?>">
                                                Transfers
                                            </a> -->
                                    <!--                                        </td>-->
                                    <td>
                                        <a target="_blank" href="<?= URL::full('profile/' . (int) $order['entity_owner']['id']) ?>">
                                            <?= htmlentities($order['entity_owner']['name']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a target="_blank" href="<?= URL::full('profile/' . (int) $order['user']['id']) ?>">
                                            <?= htmlentities($order['user']['name']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?= htmlentities($order['entity_type'] == Conversation::ENTITY_TYPE ? $lang('messages') : $lang($order['entity_type'])) ?>
                                    </td>
                                    <td>
                                        <?= htmlentities($order['entity']['name']) ?>
                                    </td>
                                    <td>
                                        <?php if ($order['status'] == Order::STATUS_CANCELED && !empty($order['remark'])) : ?>
                                            <?= $lang(htmlentities($order['status'])) . '(' . $lang($order['remark']) . ')' ?>
                                        <?php else : ?>
                                            <?= $lang(htmlentities($order['status'])) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= $lang($order['entity']['status']) ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($order['payment'])) : ?>
                                            <?= $lang($order['payment']['status']) ?>
                                        <?php else : ?>
                                            <?= $lang('not_paid') ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= htmlentities($order['advisor_amount']) ?>SR
                                    </td>
<!--                                    <td>-->
<!--                                        --><?//= htmlentities($order['admin_amount']) ?><!--SR-->
<!--                                    </td>-->
<!--                                    <td>-->
<!--                                        --><?//= htmlentities($order['final_amount']) ?><!--SR-->
<!--                                    </td>-->
<!--                                    <td>-->
<!--                                        --><?//= htmlentities($order['payable']- $order['final_amount']) ?><!--SR-->
<!--                                    </td>-->
<!--                                    <td>-->
<!--                                        --><?//= htmlentities($order['payable']) ?><!--SR-->
<!--                                    </td>-->
                                    <td>
                                        <?= htmlentities($order['used_coupon']) ?>
                                    </td>
                                    <td>
                                        <?= $order['id'] ?>
                                    </td>

                                    <td>
                                        <?= ServiceHelper::generateRef($order['user']['id'], $order['entity']['id'], $order['entity_type']);  ?>
                                    </td>
                                    <td>
                                        e-<?= $order['entity_type'] === Call::ENTITY_TYPE ? $order['entity']['slot_id'] : $order['entity']['id']  ?>
                                    </td>


                                    <td>
                                        <?php
                                        if ($order['charities'] != 'NA') : ?>
                                            Amount will go to charity( <a href="#!" onclick="showModal('<?= $order['id']; ?>')">Click to view</a> )
                                        <?php else : ?>
                                            NA
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= htmlentities($order['vat']) ?>SR
                                    </td>
                                    <td>
                                        <?= $order['in_hold'] == 1 ? $lang('yes') : $lang('no'); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $isExpired = false;
                                        switch( $order['entity_type'] )
                                        {
                                            case Workshop::ENTITY_TYPE:
                                                $isExpired = (
                                                    WorkshopHelper::isExpired($order['entity']['date'], $order['entity']['duration']) &&
                                                    $order['entity']['status'] == Workshop::STATUS_NOT_STARTED
                                                );
                                                break;
                                            case Call::ENTITY_TYPE:
                                                $isExpired = (
                                                    WorkshopHelper::isExpired($order['entity']['date'],  $order['entity']['duration']) &&
                                                    $order['entity']['status'] == Call::STATUS_NOT_STARTED
                                                );
                                            case Conversation::ENTITY_TYPE:
                                                $isExpired = (
                                                    ConversationHelper::isExpired($order['entity']['created_at']) &&
                                                    $order['entity']['status'] == Conversation::STATUS_CURRENT
                                                );
                                        }
                                        if ( $isExpired ) echo $lang('expired');
                                        else echo '-';
                                        ?>
                                    </td>
                                    <td>
                                        <?= date('d-m-Y H:i', htmlentities($order['created_at'])) ?>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <h5 class="modal-title" id="exampleModalLabel"><?php // echo $lang('header')
                ?></h5> -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="charities" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?= $lang('charities') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="show-status" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?= $lang('change_status') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<define footer_js>
    <script>
        function toText(string) {
            var elm = document.createElement('div');
            elm.innerText = string;
            return elm.innerText;
        }

        function showModal(id) {
            var $modal = $('#charities');
            var $body = $modal.find('.modal-body');

            $.ajax({
                url: '<?= URL::full('/ajax/admin/show-charities-list'); ?>',
                beforeSend: function() {
                    $body.html('<?= $lang('loading'); ?>');
                    $modal.modal('show');
                },
                type: 'POST',
                data: {
                    eId: id,
                    eType: 'workshop'
                },
                complete: function() {
                    $modal.modal('handleUpdate');
                },
                success: function(data) {
                    var list = data.payload;
                    if (list.length <= 0) {
                        $body.html('<?= $lang('no_data'); ?>');
                        return;
                    }

                    $body.html('');
                    var html = '<ul class="media-story m-0 p-0">';
                    for (var i = 0; i < list.length; i++) {
                        var name = '<?= $lang->current(); ?>' + '_name';

                        var d = list[i];
                        console.log(d);
                        html += '<li class="d-flex mb-4 align-items-center">';
                        html += '<img  src="<?= URL::full('Application/Uploads/') ?>' + d.img + '" class="rounded-circle-charity img-fluid">';
                        html += '<div class="stories-data ml-3">';
                        html += '<h5>' + toText(d[name]) + '</h5>';
                        html += '</div>';
                        html += '</li><hr>';
                    }
                    html += "</ul>";

                    console.log(html);
                    $body.html(html);
                }
            });
        }

        $(".show-details").on('click', function(e) {
            e.preventDefault();

            var id = $(this).data('id');

            $.ajax({
                url: '<?= URL::full('/ajax/admin/show-billings-details'); ?>',
                type: 'POST',
                dataType: 'JSON',
                accepts: 'JSON',
                data: {
                    id: id
                },
                success: function(data) {
                    $("#modal").modal('show');

                    $(".modal-body").html(data.payload);
                },
                complete: function() {

                }
            });
        })

        $(".show-transaction").on('click', function(e) {
            e.preventDefault();

            var id = $(this).data('id');

            $.ajax({
                url: '<?= URL::full('/ajax/admin/show-transfer-info'); ?>',
                type: 'POST',
                dataType: 'JSON',
                accepts: 'JSON',
                data: {
                    id: id
                },
                success: function(data) {
                    $("#modal").modal('show');

                    $(".modal-body").html(data.payload);
                },
                complete: function() {

                }
            });
        })

        $(function() {
            $("#table").DataTable({
                // "dom": '<"top"i>rt<"bottom"flp><"clear">',
                "order": [
                    [10, "desc"]
                ],
                "responsive": false,
                "lengthChange": false,
                "autoWidth": false,
            })
        });

        $('.cancel-status').on('click', function(e) {
            e.preventDefault();

            var id = $(this).data('id');

            cConfirm("<?= $lang('are_you_sure') ?>", function() {
                $("#show-status").modal('hide');
                var dialog = bootbox.dialog({
                    message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> <?= $lang('please_wait') ?></p>',
                    closeButton: false,
                    centerVertical: true,
                });

                $.ajax({
                    url: URLS.order_cancel,
                    data: {
                        id: id
                    },
                    type: 'POST',
                    accepts: 'JSON',
                    dataType: 'JSON',
                    beforeSend: function() {
                        isBusy = true;
                    },
                    success: function(data) {
                        if (data.info !== 'success') return;

                        window.location.reload();
                    },
                    complete: function() {
                        isBusy = false;
                        // do something in the background
                        dialog.modal('hide');
                    }
                });
            });
        });

        $('.hold-status').on('click', function(e) {
            e.preventDefault();

            var id = $(this).data('id');
            var value = $(this).data('value');

            cConfirm("<?= $lang('are_you_sure') ?>", function() {
                $("#show-status").modal('hide');
                var dialog = bootbox.dialog({
                    message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> <?= $lang('please_wait') ?></p>',
                    closeButton: false,
                    centerVertical: true,
                });

                $.ajax({
                    url: URLS.order_hold,
                    data: {
                        id: id,
                        value: value
                    },
                    type: 'POST',
                    accepts: 'JSON',
                    dataType: 'JSON',
                    beforeSend: function() {
                        isBusy = true;
                    },
                    success: function(data) {
                        if (data.info !== 'success') return;

                        window.location.reload();
                    },
                    complete: function() {
                        isBusy = false;
                        // do something in the background
                        dialog.modal('hide');
                    }
                });
            });
        });
    </script>
</define>