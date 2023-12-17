<?php

use System\Core\Model;

$lang = Model::get('\Application\Models\Language');
?>

<!-- Modal -->
<div class="modal fade" id="crop-avatar-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel"><?= $lang('cropper_modal_title') ?></h5>
                <button type="button" id="button" class="btn btn-danger"><?= $lang('save')?></button>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img id="image" src="" alt="Picture">
                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>


<define header_css>
    <style>
        .img-container img {
            max-width: 100%;
        }

        .cropper-view-box, .cropper-face {
            border-radius: 50%;
        }
    </style>
</define>