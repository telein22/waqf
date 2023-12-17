<?php

use Application\Models\Call;
use Application\Models\User;
use Application\Models\Workshop;
use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;

$lang = Model::get('\Application\Models\Language');

$userM = Model::get(User::class);
?>

<div class="container">
    <div class="row mt-5 mb-2">
        <div class="col-md-12">
            <div class="iq-card">
                <div class="iq-card-body p-0">
                    <div class="user-tabing">
                        <ul class="nav nav-pills d-flex align-items-center justify-content-center profile-feed-items p-0 m-0">
                            <li class="col-sm-6 p-0">
                                <a class="<?php if (!$isAdvisor) echo 'active' ?> nav-link timeline-tab-nav" href="<?= URL::full('/calls/b') ?>">
                                    <?= $lang('my_calls') ?>
                                </a>
                            </li>
                            <?php if ($userM->canCreateWorkshop()) : ?>
                                <li class="col-sm-4 p-0 d-none">
                                    <a class="<?php if ($isAdvisor) echo 'active' ?> nav-link about-tab-nav" href="<?= URL::full('/calls/a') ?>">
                                        <?= $lang('set_my_call') ?>
                                    </a>
                                </li>
                                <li class="col-sm-3 p-0 d-none">
                                    <a class="nav-link about-tab-nav" data-toggle="modal" data-target="" href="#">
                                        <?= $lang('create_call') ?>
                                    </a>
                                </li>
                                <li class="col-sm-6 p-0">
                                    <a class="nav-link about-tab-nav" href="<?= URL::full('calls/manage') ?>">
                                        <?= $lang('manage_calls') ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-12">
            <?php if ($isAdvisor) : ?>
                <div class="">
                    <a href="<?= URL::full('dashboard') ?>" class="text-bold text-secondary pull-right"><i class="ri-arrow-left-s-line"></i> <?= $lang('back_to_home') ?></a>
                </div>
            <?php else : ?>
                <div class="">
                    <a href="<?= URL::full('dashboard') ?>" class="text-bold text-secondary pull-right"><i class="ri-arrow-left-s-line"></i> <?= $lang('back_to_home') ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <form method="POST" action="<?= URL::current(); ?>">
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="iq-card">
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <?php if ($isAdvisor) : ?>
                                        <label for="id"><?= $lang('beneficiary') ?></label>
                                    <?php else : ?>
                                        <label for="id"><?= $lang('advisor') ?></label>
                                    <?php endif; ?>
                                    <select class="form-control call-name-finder" id="id" multiple name="id[]">
                                        <?php if (!empty($selectedUsers)) : ?>
                                            <?php foreach ($selectedUsers as $su) : ?>
                                                <option value="<?= $su['id'] ?>" selected><?= htmlentities($su['name']); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="request"><?= $lang('no_of_request') ?></label>
                                    <input type="number" class="form-control" name="request" id="request" value="<?= $limit ?>" min="8" step="4" max="80">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status"><?= $lang('status') ?></label>
                                    <select class="form-control" id="status" name="status">
                                        <option value=""><?= $lang('all') ?></option>
                                        <option value="<?= Call::STATUS_NOT_STARTED ?>" <?= !empty($query['status']) && $query['status'] == Call::STATUS_NOT_STARTED ? 'selected' : ''; ?>><?= $lang(Call::STATUS_NOT_STARTED); ?></option>
                                        <option value="<?= Call::STATUS_CURRENT ?>" <?= !empty($query['status']) && $query['status'] == Call::STATUS_CURRENT ? 'selected' : ''; ?>><?= $lang(Call::STATUS_CURRENT); ?></option>
                                        <option value="<?= Call::STATUS_CANCELED ?>" <?= !empty($query['status']) && $query['status'] == Call::STATUS_CANCELED ? 'selected' : ''; ?>><?= $lang(Call::STATUS_CANCELED); ?></option>
                                        <option value="<?= Call::STATUS_COMPLETED ?>" <?= !empty($query['status']) && $query['status'] == Call::STATUS_COMPLETED ? 'selected' : ''; ?>><?= $lang(Call::STATUS_COMPLETED); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date"><?= $lang('date') ?></label>
                                    <input type="date" class="form-control" name="date" id="date" value="<?= !empty($query['date']) ? $query['date'] : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-12 text-ar-right">
                                <button class="btn btn-primary"><?= $lang('filter') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12">
            <div class="iq-card  iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <?php if ($isAdvisor) : ?>
                            <h4 class="card-title"><?= $lang('set_my_call'); ?></h4>
                        <?php else : ?>
                            <h4 class="card-title"><?= $lang('my_calls'); ?></h4>
                        <?php endif; ?>
                    </div>
                    <div class="iq-card-header-toolbar d-flex align-items-center">
                        <?php if ($isAdvisor) : ?>
                            <!-- <p class="m-0"><a href="<?php // echo URL::full('calls/manage') ?>"><i class="ri-edit-line"></i> <?php // echo $lang('manage_calls') ?></a></p> -->
                        <?php endif; ?>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="container">
                        <?php if (!empty($calls)) : ?>
                            <div class="row call-list">
                                <?php foreach ($calls as $call) : ?>
                                    <div class="col-md-12 mb-4">
                                        <?php View::include('Calls/call', ['call' => $call]); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if (count($calls) == $limit) : ?>
                                <a href="#" class="more-call-btn">More ></a>
                            <?php endif; ?>
                        <?php else : ?>
                            <div class="row justify-content-center">
                                <div class="col-md-5 mb-5 text-center">
                                    <img src="<?= URL::asset('Application/Assets/images/empty_workshop.png'); ?>" alt="No calls" class="img-fluid" />
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<define header_css>
    <style>
        .call-list .col-md-12 {
            border-bottom: 1px solid var(--iq-border-light);
        }

        .call-list .col-md-12:last-child {
            border-bottom: none;
        }
    </style>
</define>
<define footer_js>
    <script>
        $('.call-name-finder').select2({
            ajax: {
                url: URLS.call_search,
                type: 'POST',
                data: function(param) {
                    param.type = 'b';
                    return param;
                },
                processResults: function(data) {

                    var final = {
                        results: []
                    };
                    for (var i = 0; i < data.payload.length; i++) {
                        final.results.push({
                            id: data.payload[i].id,
                            text: data.payload[i].name
                        });
                    }

                    return final;
                }
            }
        });

        var callSkip = <?= $limit; ?>;
        var callLimit = <?= $limit; ?>;
        var callIsBusy = false;

        $('.more-call-btn').on('click', function(e) {
            e.preventDefault();

            var $self = $(this);

            $.ajax({
                url: URLS.more_call,
                beforeSend: function() {
                    callIsBusy = true;
                },
                data: {
                    skip: callSkip,
                    limit: callLimit,
                    <?php if (!$isAdvisor) : ?> type: 'b',
                    <?php endif; ?>
                    query: <?= json_encode($query); ?>
                },
                complete: function() {
                    callIsBusy = false;
                },
                success: function(data) {
                    var d = data.payload;
                    callSkip = d.skip;
                    var w = d.calls;

                    if (!d.dataAvl) $self.remove();

                    for (var i = 0; i < w.length; i++) {
                        $('.call-list').append('<div class="col-md-12 mb-4">' + w[i] + '</div>');
                    }
                }
            });
        });
    </script>
</define>