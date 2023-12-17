<?php

use System\Core\Model;
use System\Helpers\URL;
use Application\Models\WithdrawalRequest;

$lang = Model::get('\Application\Models\Language');
?>

<!-- Modal -->
<div class="modal fade" id="admin-withdrawal-request-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel"><?= $lang('withdrawal_request_processing') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ">
                <img id="loader" class="d-none" src="<?= URL::asset("Application/Assets/images/page-img/ajax-loader.gif"); ?>" alt="loader" style="width: 75px; height: 75px; margin: 0 auto; position: fixed; top:20%; left: 50%; z-index:99999999;" >
                <!--                <p class="text-ar-right alert alert-info">--><?php //= $lang('profits_withdrawal_note2')?><!--</p>-->
                <form action="<?= URL::full('admin/withdrawal-requests/change-status')?>" id="processing-form" method="POST">
                    <div class="text-ar-right">
                        <div class="form-group" id="freelance-document-container">
                            <label><?= $lang('download_freelance_document') ?></label>
                            <a id="freelance_doc_download" href="url" target="_blank"><?= $lang('click_here')?></a>
                        </div>

                        <input type="hidden" id="withdrawal-request-id" name="withdrawal_request_id">
                        <div class="form-group required">
                            <label><?= $lang('change_status') ?></label>
                            <select name="status" class="form-control" id="withdrawal-request-status">
                                <option value="<?= WithdrawalRequest::STATUS_PENDING ?>"><?= $lang('withdrawal_status_pending') ?></option>
                                <option value="<?= WithdrawalRequest::STATUS_APPROVED ?>"><?= $lang('withdrawal_status_approved') ?></option>
                                <option value="<?= WithdrawalRequest::STATUS_PROCESSING ?>"><?= $lang('withdrawal_status_processing') ?></option>
                                <option value="<?= WithdrawalRequest::STATUS_COMPLETED ?>"><?= $lang('withdrawal_status_completed') ?></option>
                                <option value="<?= WithdrawalRequest::STATUS_REJECTED ?>"><?= $lang('withdrawal_status_rejected') ?></option>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-primary" id="btn-save" ><?= $lang('save')?></button>
                </form>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

