<?php

use Application\Models\Conversation;
use Application\Models\User;
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
                            <li class="col-sm-4 p-0">
                                <a class="<?php if (!$isAdvisor) echo 'active' ?> nav-link timeline-tab-nav" href="<?= URL::full('/messaging/b') ?>">
                                    <?= $lang('my_messages') ?>
                                </a>
                            </li>
                            <?php if ($userM->canCreateWorkshop()) : ?>
                                <li class="col-sm-4 p-0">
                                    <a class="<?php if ($isAdvisor) echo 'active' ?> nav-link about-tab-nav" href="<?= URL::full('/messaging/a') ?>">
                                        <?= $lang('my_received_messages') ?>
                                    </a>
                                </li>
                                <li class="col-sm-4 p-0">
                                    <a class="nav-link about-tab-nav" href="<?= URL::full('messaging/manage') ?>">
                                        <?= $lang('manage_message_price') ?>
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
                                        <label for="ids"><?= $lang('beneficiary') ?></label>
                                    <?php else : ?>
                                        <label for="ids"><?= $lang('advisor') ?></label>
                                    <?php endif; ?>
                                    <select class="form-control conversation-name-finder" id="ids" multiple name="ids[]">
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
                                        <option value="<?= Conversation::STATUS_CURRENT ?>" <?= !empty($query['status']) && $query['status'] == Conversation::STATUS_CURRENT ? 'selected' : ''; ?>><?= $lang(Conversation::STATUS_CURRENT); ?></option>
                                        <option value="<?= Conversation::STATUS_CANCELED ?>" <?= !empty($query['status']) && $query['status'] == Conversation::STATUS_CANCELED ? 'selected' : ''; ?>><?= $lang(Conversation::STATUS_CANCELED); ?></option>
                                        <option value="<?= Conversation::STATUS_COMPLETED ?>" <?= !empty($query['status']) && $query['status'] == Conversation::STATUS_COMPLETED ? 'selected' : ''; ?>><?= $lang(Conversation::STATUS_COMPLETED); ?></option>
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
                            <h4 class="card-title"><?= $lang('my_received_messages'); ?></h4>
                        <?php else : ?>
                            <h4 class="card-title"><?= $lang('my_messages'); ?></h4>
                        <?php endif; ?>
                    </div>
                    <div class="iq-card-header-toolbar d-flex align-items-center">
                        <?php if ($isAdvisor) : ?>
                            <!-- <p class="m-0"><a href="<?php // echo URL::full('messaging/manage') ?>"><i class="ri-edit-line"></i> <?php // echo $lang('manage_message_price') ?></a></p> -->
                        <?php endif; ?>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="container">
                        <?php if (!empty($conversations)) : ?>
                            <div class="row conversation-list">
                                <?php foreach ($conversations as $conversation) : ?>
                                    <div class="col-md-12 mb-4">
                                        <?php View::include('Messaging/conversation_card', ['conversation' => $conversation, 'isAdvisor' => $isAdvisor]); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if (count($conversations) == $limit) : ?>
                                <a href="#" class="more-conversation-btn">More ></a>
                            <?php endif; ?>
                        <?php else : ?>
                            <div class="row justify-content-center">
                                <div class="col-md-5 mb-5 text-center">
                                    <img src="<?= URL::asset('Application/Assets/images/empty_workshop.png'); ?>" alt="No conversations" class="img-fluid" />
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
        /* .conversation-card {
            text-align: center;
        } */

        .conversation-card img {
            /* width: 100px;
            height: 100px; */
            border-radius: 50%;
        }
    </style>
</define>

<define footer_js>
    <script>
        $('.conversation-name-finder').select2({
            ajax: {
                url: URLS.conversation_search,
                type: 'POST',
                data: function(param) {
                    <?php if (!$isAdvisor) : ?> param.type = 'b';
                    <?php endif; ?>
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

        var conversationSkip = <?= $limit; ?>;
        var conversationLimit = <?= $limit; ?>;
        var conversationIsBusy = false;

        $('.more-conversation-btn').on('click', function(e) {
            e.preventDefault();

            var $self = $(this);

            $.ajax({
                url: URLS.more_conversations,
                beforeSend: function() {
                    conversationIsBusy = true;
                },
                data: {
                    skip: conversationSkip,
                    limit: conversationLimit,
                    <?php if (!$isAdvisor) : ?> type: 'b',
                    <?php endif; ?>
                    query: <?= json_encode($query); ?>
                },
                complete: function() {
                    conversationIsBusy = false;
                },
                success: function(data) {
                    var d = data.payload;
                    conversationSkip = d.skip;
                    var w = d.conversations;

                    if (!d.dataAvl) $self.remove();

                    for (var i = 0; i < w.length; i++) {
                        $('.conversation-list').append('<div class="col-md-3 mb-5">' + w[i] + '</div>');
                    }
                }
            });
        });
    </script>
</define>