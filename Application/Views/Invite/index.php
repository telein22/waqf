<?php

use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\Language;
use Application\Models\Workshop;
use System\Core\Model;
use System\Helpers\URL;
use System\Libs\FormValidator;

$lang = Model::get(Language::class);

$formValidator = FormValidator::instance("invite");
?>

<div class="container">
    <div class="row mt-5 justify-content-center">
        <div class="col-md-8">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title"><?= $lang('invite_free_users'); ?></h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <form action="<?= URL::current() ?>" method="post">
                        <div class="form-group">
                            <?php $oldUsers = $formValidator->getValue('users'); ?>
                            <label for="users"><?= $lang('users') ?></label>
                            <select name="users[]" class="form-control user-select" id="users" multiple>
                                <?php foreach ($users as $user) :; ?>
                                    <option <?= in_array($user, $oldUsers) ? 'selected' : '';  ?>><?= $user ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($formValidator->hasError('users')) : ?>
                                <p class="text-danger"><?= $formValidator->getError('users'); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="type"><?= $lang('type') ?></label>
                            <select name="type" class="form-control" id="type">
                                <option <?php if ($formValidator->getValue('type') == Workshop::ENTITY_TYPE) echo 'selected' ?> value="<?= Workshop::ENTITY_TYPE ?>"><?= $lang(Workshop::ENTITY_TYPE) ?></option>
                                <option <?php if ($formValidator->getValue('type') == Call::ENTITY_TYPE) echo 'selected' ?> value="<?= Call::ENTITY_TYPE ?>"><?= $lang(Call::ENTITY_TYPE) ?></option>
                                <option <?php if ($formValidator->getValue('type') == Conversation::ENTITY_TYPE) echo 'selected' ?> value="<?= Conversation::ENTITY_TYPE ?>"><?= $lang(Conversation::ENTITY_TYPE) ?></option>
                            </select>
                            <?php if ($formValidator->hasError('type')) : ?>
                                <p class="text-danger"><?= $formValidator->getError('type'); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="coupon"><?= $lang('coupon_code') ?></label>
                            <input type="text" name="coupon" value="<?= $formValidator->getValue('coupon') ?>" id="coupon" class="form-control">
                            <?php if ($formValidator->hasError('coupon')) : ?>
                                <p class="text-danger"><?= $formValidator->getError('coupon'); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="text-ar-right">
                            <button class="btn btn-primary" type="submit"><?= $lang('submit') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<define footer_js>
    <script>
        $('.user-select').select2({
            ajax: {
                url: URLS.user_search,
                type: 'POST',
                data: function(param) {
                    return param;
                },
                processResults: function(data) {

                    var final = {
                        results: []
                    };
                    for (var i = 0; i < data.payload.length; i++) {
                        final.results.push({
                            id: data.payload[i].email,
                            text: data.payload[i].name
                        });
                    }

                    return final;
                }
            }
        });
    </script>
</define>