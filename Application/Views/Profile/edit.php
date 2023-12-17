<?php

use System\Core\Model;
use System\Helpers\URL;
use System\Libs\FormValidator;
use System\Responses\View;

$lang = Model::get('\Application\Models\Language');

$formValidator = FormValidator::instance("edit_general");

?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="iq-card">
                <div class="iq-card-body p-0">
                    <div class="iq-edit-list">
                        <ul class="iq-edit-profile d-flex nav nav-pills">
                            <li class="col-md-4 p-0">
                                <a class="nav-link <?= $section == 'general' ? 'active' : ''; ?>" href="<?= URL::full('profile/edit') ?>">
                                    <?= $lang('general_information'); ?>
                                </a>
                            </li>                                
                            <li class="col-md-4 p-0">
                                <a class="nav-link <?= $section == 'social' ? 'active' : ''; ?>" href="<?= URL::full('profile/edit/social') ?>">
                                    <?= $lang('social_links'); ?>
                                </a>
                            </li>
                            <!-- <li class="col-md-3 p-0">
                                <a class="nav-link <?= $section == 'bank' ? 'active' : ''; ?>" href="<?= URL::full('profile/edit/bank') ?>">
                                    <?= $lang('bank_details'); ?>                                       
                                </a>
                            </li> -->
                            <li class="col-md-4 p-0">
                                <a class="nav-link <?= $section == 'change_pwd' ? 'active' : ''; ?>" href="<?= URL::full('profile/edit/change-password') ?>">
                                    <?= $lang('change_password'); ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="iq-edit-list-data">
                <div class="tab-content">
                    <?php View::include('Profile/Edit/' . $section, $data); ?>
                </div>
            </div>
        </div>
    </div>
</div>
