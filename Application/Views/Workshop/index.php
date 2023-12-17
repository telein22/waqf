<?php

use Application\Models\User;
use Application\Models\Workshop;
use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;

$lang = Model::get('\Application\Models\Language');
$userM = Model::get(User::class);


?>
<div class="container">
    <div class="row mb-2">
        <div class="col-md-12">
            <div class="">
                <a href="<?= URL::full('feeds') ?>" class="text-bold text-secondary pull-right"><i class="ri-arrow-left-s-line"></i> <?= $lang('back_to_home') ?></a>
            </div>
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
                                    <label for="name"><?= $lang('search_workshop') ?></label>
                                    <select class="form-control workshop-name-finder" id="name" multiple name="name[]">
                                        <?php if (!empty($query['name'])) : ?>
                                            <?php foreach ($query['name'] as $name) : ?>
                                                <option value="<?= htmlentities($name) ?>" selected><?= htmlentities($name); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="request"><?php  echo $lang('no_of_request'); ?></label>
                                    <input type="number" class="form-control" name="request" id="request" value="<?= $limit; ?>" min="1" max="80" oninvalid="requestLimitValidation(this)" oninput="this.setCustomValidity('')">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status"><?php  echo $lang('status'); ?></label>
                                    <select class="form-control" id="status" name="status">
                                        <option value=""><?php  echo $lang('all'); ?></option>
                                        <option value="<?= Workshop::STATUS_NOT_STARTED ?>" <?= !empty($query['status']) && $query['status'] == Workshop::STATUS_NOT_STARTED ? 'selected' : ''; ?>><?= $lang(Workshop::STATUS_NOT_STARTED); ?></option>
                                        <option value="<?= Workshop::STATUS_CURRENT ?>" <?= !empty($query['status']) && $query['status'] == Workshop::STATUS_CURRENT ? 'selected' : ''; ?>><?= $lang(Workshop::STATUS_CURRENT); ?></option>
                                        <option value="<?= Workshop::STATUS_CANCELED ?>" <?= !empty($query['status']) && $query['status'] == Workshop::STATUS_CANCELED ? 'selected' : ''; ?>><?= $lang(Workshop::STATUS_CANCELED); ?></option>
                                        <option value="<?= Workshop::STATUS_COMPLETED ?>" <?= !empty($query['status']) && $query['status'] == Workshop::STATUS_COMPLETED ? 'selected' : ''; ?>><?= $lang(Workshop::STATUS_COMPLETED); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date"><?php  echo $lang('date'); ?></label>
                                    <input type="date" class="form-control" name="date" id="date" value="<?= !empty($query['date']) ? $query['date'] : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-12 <?php if ($lang->current() == 'ar') echo 'text-left'; else echo 'text-right';?>">
                                <button class="btn btn-primary"><?= $lang('filter_workshop'); ?></button>
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
                        <h4 class="card-title"><?= $lang('my_workshops'); ?></h4>
                    </div>
                    <div class="iq-card-header-toolbar d-flex align-items-center">
                        <p class="m-0"><a href="<?= URL::full('workshops/find'); ?>"><i class="ri-add-line"></i> <?= $lang('find_more_workshops') ?></a></p>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="container">
                        <?php if (!empty($workshops)) : ?>
                            <div class="row workshop-list">
                                <?php foreach ($workshops as $workshop) : ?>
                                    <div class="col-md-12 mb-4">
                                        <?php View::include('Workshop/workshop', ['workshop' => $workshop, 'user' => $user, 'platform_fees' => $platform_fees]); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if (count($workshops) == $limit) : ?>
                                <a href="#" class="more-workshop-btn">More ></a>
                            <?php endif; ?>
                        <?php else : ?>
                            <div class="row justify-content-center">
                                <div class="col-md-5 mb-5 text-center">
                                    <img src="<?= URL::asset('Application/Assets/images/empty_workshop.png'); ?>" alt="No workshop" class="img-fluid" />
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php View::include('Workshop/capacity'); ?>
<?php View::include('Invite/modal', ['title' => $lang('invite_free_users')]); ?>

<define footer_js>
    <script>
        $('.workshop-name-finder').select2({
            ajax: {
                url: URLS.workshop_search,
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
                            id: data.payload[i].name,
                            text: data.payload[i].name
                        });
                    }

                    return final;
                }
            }
        });

        var workshopSkip = <?= $limit; ?>;
        var workshopLimit = <?= $limit; ?>;
        var workshopIsBusy = false;

        $('.more-workshop-btn').on('click', function(e) {
            e.preventDefault();

            var $self = $(this);

            $.ajax({
                url: URLS.more_workshop,
                beforeSend: function() {
                    workshopIsBusy = true;
                },
                data: {
                    skip: workshopSkip,
                    limit: workshopLimit,
                    type: 'b',
                    query: <?= json_encode($query); ?>
                },
                complete: function() {
                    workshopIsBusy = false;
                },
                success: function(data) {
                    var d = data.payload;
                    workshopSkip = d.skip;
                    var w = d.workshops;

                    if (!d.dataAvl) $self.remove();

                    for (var i = 0; i < w.length; i++) {
                        $('.workshop-list').append('<div class="col-md-12 mb-5">' + w[i] + '</div>');
                    }
                }
            });
        });

        function requestLimitValidation(elem) {
            if (elem.value <= 0) {
                elem.setCustomValidity('<?= $lang("value_greater_than", ['value' => 0]); ?>')
            } else if (elem.value > 80) {
                elem.setCustomValidity('<?= $lang("value_less_than", ['value' => 80]); ?>')
            }
        }
    </script>
</define>