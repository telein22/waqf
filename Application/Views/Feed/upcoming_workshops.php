<?php

use Application\Helpers\DateHelper;
use Application\Models\Language;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get(Language::class)

?>
<div class="iq-card">
    <div class="iq-card-header d-flex justify-content-between">
        <div class="iq-header-title">
            <h4 class="card-title">Upcoming Workshops</h4>
        </div>
        <!-- <div class="iq-card-header-toolbar d-flex align-items-center">
                        <div class="dropdown">
                            <span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false" role="button">
                                <i class="ri-more-fill"></i>
                            </span>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="">
                                <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                            </div>
                        </div>
                    </div> -->
    </div>
    <div class="iq-card-body">
        <ul class="media-story m-0 p-0">
            <?php if (!empty($aWorkshops)) : ?>
                <li class="heading d-flex align-items-center mb-2 font-weight-bold"><?= $lang('advisor'); ?></li>
                <?php foreach ($aWorkshops as $workshops) : ?>
                    <li class="d-flex mb-2 align-items-center ">
                        <img src="<?= URL::asset('Application/Assets/images/workshop.png') ?>" alt="workshop-img" class="rounded-circle img-fluid">
                        <div class="stories-data ml-3">
                            <h5 class="font-size-16"><?= htmlentities($workshops['name']); ?></h5>
                            <p class="mb-0 text-primary small"><?= DateHelper::butify(strtotime($workshops['date'])); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (!empty($bWorkshops)) : ?>
                <li class="heading d-flex align-items-center mb-2 font-weight-bold"><?= $lang('beneficiary'); ?></li>
                <?php foreach ($bWorkshops as $workshops) : ?>
                    <li class="d-flex mb-2 align-items-center ">
                        <img src="<?= URL::asset('Application/Assets/images/workshop.png') ?>" alt="workshop-img" class="rounded-circle img-fluid">
                        <div class="stories-data ml-3">
                            <h5 class="font-size-16"><?= htmlentities($workshops['name']); ?></h5>
                            <p class="mb-0 text-primary small"><?= DateHelper::butify(strtotime($workshops['date'])); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>

<define header_css>
    <style>
        ul.media-story li>img,
        ul.media-story li>i {
            height: 40px;
            width: 40px;
            line-height: 30px;
            text-align: center;
            border: 2px solid rgba(0, 0, 0, .1);
            padding: 2px;
            border-radius: 50%;
        }
    </style>
</define>