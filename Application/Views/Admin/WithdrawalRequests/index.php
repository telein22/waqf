<?php

use Application\Models\User;
use Application\Models\WithdrawalRequest;
use System\Core\Model;
use System\Helpers\URL;
use Application\Models\Language;
use System\Models\Session;
use System\Responses\View;

$prefix = $userInfo['type'] == User::TYPE_ADMIN ? 'admin' : 'entities';
$lang = Model::get(Language::class);
$sessionM = Model::get(Session::class);
$changeRequestStatusImpossible = $sessionM->take('changing_status_impossible');
$walletBalanceInsufficient = $sessionM->take('wallet_balance_insufficient');
$sessionM->delete('changing_status_impossible');
$sessionM->delete('wallet_balance_insufficient');

?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header ">
                        <?php if(isset($changeRequestStatusImpossible)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $lang('changing_withdrawal_request_status_impossible')?>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($walletBalanceInsufficient)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $lang('wallet_balance_insufficient')?>
                            </div>
                        <?php endif; ?>
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
                                        <option <?php if (WithdrawalRequest::STATUS_PENDING == $status) echo 'selected' ?> value="<?= WithdrawalRequest::STATUS_PENDING ?>"><?= $lang('withdrawal_status_pending') ?></option>
                                        <option <?php if (WithdrawalRequest::STATUS_APPROVED == $status) echo 'selected' ?> value="<?= WithdrawalRequest::STATUS_APPROVED ?>"><?= $lang('withdrawal_status_approved') ?></option>
                                        <option <?php if (WithdrawalRequest::STATUS_PROCESSING == $status) echo 'selected' ?> value="<?= WithdrawalRequest::STATUS_PROCESSING ?>"><?= $lang('withdrawal_status_processing') ?></option>
                                        <option <?php if (WithdrawalRequest::STATUS_COMPLETED == $status) echo 'selected' ?> value="<?= WithdrawalRequest::STATUS_COMPLETED ?>"><?= $lang('withdrawal_status_completed') ?></option>
                                        <option <?php if (WithdrawalRequest::STATUS_CANCELLED == $status) echo 'selected' ?> value="<?= WithdrawalRequest::STATUS_CANCELLED ?>"><?= $lang('withdrawal_status_cancelled') ?></option>
                                        <option <?php if (WithdrawalRequest::STATUS_REJECTED == $status) echo 'selected' ?> value="<?= WithdrawalRequest::STATUS_REJECTED ?>"><?= $lang('withdrawal_status_rejected') ?></option>
                                    </select>
                                </div>
                                <div class="align-items-center col-sm-12 mobile-mt-3">
                                    <button type="submit" class="btn-sm btn btn-primary mt-0"><?= $lang('filter') ?></button>
                                    <!-- <a href="<?= URL::current() ?>" class="btn-sm btn btn-secondary">Reset Filter</a> -->
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="filter-right"><a href="<?= URL::full("{$prefix}/withdrawal-requests-csv") . '?' . http_build_query(['status' => $status, 'from' => date('Y-m-d', $from), 'to' => date('Y-m-d', $to)]) ?>" target="_blank" class="btn btn-primary"><?= $lang('export_csv') ?></a></div>
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th><?= $lang('id') ?></th>
                                <th><?= $lang('user') ?></th>
                                <th><?= $lang('withdrawal_amount') ?></th>
                                <th><?= $lang('wallet_balance') ?></th>
                                <th><?= $lang('withdrawal_status') ?></th>
                                <th><?= $lang('bank_info') ?></th>
                                <th><?= $lang('created_at') ?></th>
                                <th><?= $lang('updated_at') ?></th>
                                <th><?= $lang('action') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($withdrawalRequests as $withdrawalRequest) : ?>
                                <tr>
                                    <td><?= $withdrawalRequest['id'] ?></td>
                                    <td><a href="<?= URL::full('profile/' . $withdrawalRequest['user_id']) ?>"><?= $withdrawalRequest['name'] . ' (' . $withdrawalRequest['email'] . ')' ?></a></td>
                                    <td><?= $withdrawalRequest['amount'] ?></td>
                                    <td><a href="<?= URL::full("admin/wallets/{$withdrawalRequest['wallet_id']}") ?>"><?= $withdrawalRequest['wallet_balance']?></a></td>
                                    <td><?= $lang("withdrawal_status_{$withdrawalRequest['status']}") ?></td>
                                    <td><?= $withdrawalRequest['bank_info'] ?></td>
                                    <td><?= date('Y-m-d H:i:s', $withdrawalRequest['created_at']) ?></td>
                                    <td><?= date('Y-m-d H:i:s', $withdrawalRequest['updated_at']) ?></td>
                                    <td>
                                        <a class="btn primary-btn" data-toggle="modal" data-target="#admin-withdrawal-request-modal" data-user-type="<?= $withdrawalRequest['type']?>" data-status="<?= $withdrawalRequest['status']?>" data-id="<?= $withdrawalRequest['id']?>" href="#">
                                            <i class="fas fa-cog fa-2x"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th><?= $lang('id') ?></th>
                                <th><?= $lang('user') ?></th>
                                <th><?= $lang('withdrawal_amount') ?></th>
                                <th><?= $lang('wallet_balance') ?></th>
                                <th><?= $lang('withdrawal_status') ?></th>
                                <th><?= $lang('bank_info') ?></th>
                                <th><?= $lang('created_at') ?></th>
                                <th><?= $lang('updated_at') ?></th>
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

<?php View::include ('Admin/WithdrawalRequests/modal', [

]) ?>

<define footer_js>
    <script>
        $('#admin-withdrawal-request-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget), // Button that triggered the modal
                id = button.data('id'), // Extract value from data-id attribute
                url = "<?= URL::full('/admin/withdrawal-requests/?/freelance-document')?>";
                url = url.replace('?', id);

            $('#freelance_doc_download').attr('href', url);
            $('#withdrawal-request-status').val(button.data('status'));
            $('#withdrawal-request-id').val(id)

            // if (button.data('user_type'))
            if (button.data('user-type') == 'entity') {
                $('#freelance-document-container').addClass('d-none');
            } else {
                $('#freelance-document-container').removeClass('d-none');
            }

        });

        $(function() {
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