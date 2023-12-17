<?php
use System\Core\Model;

$lang = Model::get('\Application\Models\Language');

?>
<div class="modal fade" id="workshop-capacity" tabindex="-1" role="dialog" aria-labelledby="workshop-capacity-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="workshop-capacity-label"><?= $lang('participants') ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body custom-label-align">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $lang('close') ?></button>
      </div>
    </div>
  </div>
</div>
<define footer_js>
    <script>
        function showModal( id )
        {
            var $modal = $('#workshop-capacity');
            var $body = $modal.find('.modal-body');

            $.ajax({
                url: URLS.participant_list,
                beforeSend: function() {
                    $body.html('<?= $lang('loading'); ?>');
                    $modal.modal('show');
                },
                data: {
                    eId: id,
                    eType: 'workshop'
                },
                complete: function() {
                    $modal.modal('handleUpdate');
                },
                success: function( data ) {
                    var list = data.payload;
                    if ( list.length <= 0 ) {
                        $body.html('<?= $lang('no_data'); ?>');
                        return;
                    }

                    $body.html('');
                    var html = '<ul class="media-story m-0 p-0">';
                    for( var i = 0; i < list.length; i++ ) {
                        var d = list[i].user;
                        html += '<li class="d-flex mb-4 align-items-center">';
                        html += '<img src="' + d.avatar + '" class="rounded-circle img-fluid">';
                        html += '<div class="stories-data ml-3">';
                        html += '<h5>' + toText(d.name) + '</h5>';
                        html += '<p class="mb-0">' + list[i].participated_at + '</p>';
                        html += '</div>';
                        html += '</li>';
                    }
                    html += "</ul>";

                    $body.html(html);
                }
            });     
        }
    </script>
</define>