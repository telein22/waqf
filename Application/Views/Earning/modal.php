<?php

use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');
?>

<!-- Modal -->
<div class="modal fade" id="earnings-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel"><?= $lang('profits_withdrawal_request') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ">
<!--                <p class="text-ar-right alert alert-info">--><?php //= $lang('profits_withdrawal_note2')?><!--</p>-->
                <form id="withdrawal-form" enctype="multipart/form-data">
                    <div class="text-ar-right">
                        <div class="form-group required">
                        <label><?= $lang('withdrawal_amount') ?></label>
                        <input class="form-control mb-3" type="number" name="withdrawal_amount" id="withdrawal-amount" value="<?= $wallet['balance']?>" max="<?= $wallet['balance']?>" min="<?= $minimumWithdrawalAmount ?>" required />
                        </div>

                        <?php if (!$userIsEntity): ?>
                            <div class="form-group required">
                                <label><?= $lang('freelance_document') ?></label>
                                <?php if ($freelanceDocumentUploaded): ?>
                                    <a id="freelance_doc_download" href="<?= URL::full('/earnings/freelance-document')?>" target="_blank"><?= $lang('to_review_your_freelance_doc')?></a>
                                <?php endif; ?>
                                <input class="form-control mb-3" type="file" id="freelance-document" name="freelance_document" />
                                <a href="https://freelance.sa/" target="_blank"><?= $lang('to_issue_freelance_doc') ?></a>
                            </div>
                        <?php endif; ?>

                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title"><?= $lang('bank_details'); ?></h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="form-group required">
                                <label for="b1"><?= $lang('enter_beneficiary_name') ?></label>
                                <input type="text" class="form-control" id="beneficiary-name" name="beneficiary_name" value="<?= $beneficiaryName ?>">
                            </div>
                            <div class="form-group required">
                                <label for="b2"><?= $lang('enter_account_number') ?></label>
                                <input type="text" class="form-control" id="iban" name="iban" value="<?= $iban ?>">
                            </div>
                            <div class="form-group required">
                                <label for="b3"><?= $lang('enter_bank_name') ?></label>
                                <input type="text" class="form-control" id="bank-name" name="bank_name" value="<?= $bankName ?>">
                            </div>

                            <div class="form-group">
                                <a href="<?= URL::full('/settings')?>"><?= $lang('contact_with_us')?></a>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary" id="btn-withdraw" ><?= $lang('withdraw')?></button>
                </form>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<define footer_js>
    <script>
        $('#btn-withdraw').on('click', (e) => {
            e.preventDefault();
            var currentBalance = Math.floor("<?= $wallet['balance'] ?>"),
                minimumWithdrawalAmount = Math.floor("<?= $minimumWithdrawalAmount ?>"),
                withdrawAmount = Math.floor($('#withdrawal-amount').val()),
                fileInput = $('#freelance-document')[0],
                beneficiaryName = $('#beneficiary-name'),
                iban = $('#iban'),
                bankName = $('#bank-name');

            if (withdrawAmount < minimumWithdrawalAmount ) {
                toast('danger', "<?= $lang('profits_withdrawal_note1', ['min' => $minimumWithdrawalAmount]) ?>");
            } else if ("<?= $userIsEntity ?>" == false &&  "<?= $freelanceDocumentUploaded  ?>" == false && fileInput.files.length === 0) {
                toast('danger', "<?= $lang('freelance_document_required') ?>");
            } else if (currentBalance < minimumWithdrawalAmount) {
                toast('danger', "<?= $lang('profits_below_threshold')?>");
            } else if(withdrawAmount > currentBalance) {
                toast('danger', "<?= $lang('balance_exceeded')?>");
            } else if(beneficiaryName.val().trim() === "" || iban.val().trim() === "" || bankName.val().trim() === "") {
                toast('danger', "<?= $lang('enter_bank_info')?>");
            } else {
                var formData = new FormData($("#withdrawal-form")[0]);
                callApi(URLS.apply_for_withdrawal_request, formData);
            }
        })
        
        function callApi(url, data, resolve = null, reject = null) {
            $('#loader').removeClass('d-none');
            $('#btn-withdraw').addClass('disabled');

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    $('#loader').addClass('d-none');
                    $('#btn-withdraw').removeClass('disabled');

                    if (data.info !== 'success') {
                        toast('danger', '<?= $lang('error') ?>', data.payload);
                        return;
                    }
                    toast('success', '<?= $lang('success') ?>', data.payload);

                    if (resolve !== null) {
                        resolve(data);
                    }

                    $('#earnings-modal').modal('hide');
                    $('#withdrawal-requests-table').DataTable().ajax.reload();
                },
                error: function(data) {
                    $('#loader').addClass('d-none');
                    toast('danger', '<?= $lang('error') ?>', data.info);

                    if (reject != null) {
                        reject(data)
                    }
                }
            });
        }

    </script>
</define>