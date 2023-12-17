<?php

use System\Core\Model;
use System\Helpers\URL;
use System\Libs\FormValidator;

$formValidator = FormValidator::instance("verify");

/**
 * @var \System\Models\Language
 */
$lang = Model::get('\Application\Models\Language');

?>
<div class="register-page  pt-5 pb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="register">                    
                    <h2 class="t-36 mb-4"><?= $lang('account_currently_blocked') ?></h2>
                    <h6><?= $lang('please_contact', array('mail' => '<a class="text-primary" href="mailto:Customer.service@telein.net">Customer.service@telein.net</a>')) ?></h6>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-right">
                <img src="<?= URL::asset('Application/Assets/Outer/images/Messaging-pana.svg'); ?>" class="login-img mt-5" alt="">
            </div>
        </div>
    </div>
</div>