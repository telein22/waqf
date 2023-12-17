<?php

use Application\Helpers\UserHelper;
use System\Core\Model;
use System\Helpers\URL;

/**
 * @var \Application\Models\Language
 */

$lang = Model::get('\Application\Models\Language');

?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- <div class="card-header">
                        <h3 class="card-title">DataTable with default features</h3>
                    </div> -->
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th><?= $lang('user') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($associates as $associate) : ?>
                                <tr>
                                    <td>
                                        <div class="col-md-3 col-6 pl-4 pr-4 pb-3">
                                            <a href="<?= URL::full('profile/' . $associate['id']); ?>">
                                                <img src="<?= UserHelper::getAvatarUrl('fit:300,300', $associate['id']); ?>" alt="gallary-image" class="img-fluid" />
                                                <h4 class="mt-3 text-center"><?= htmlentities($associate['name']); ?><?php if ($associate['account_verified']) echo '<i class="fas fa-check-circle color-primary"></i>' ?></h4>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th><?= $lang('user') ?></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</section>

<define footer_js>
    <script>
        $(".delete").on('click', function(e) {
            var id = $(this).data('id');
            if (confirm('Are you sure?')) {
                $.ajax({
                    url: '<?= URL::full('/ajax/admin/delete-coupon'); ?>',
                    type: 'POST',
                    dataType: 'JSON',
                    accepts: 'JSON',
                    data: {
                        id: id
                    },
                    success: function(data) {
                        window.location.reload();
                    },
                    complete: function() {

                    }
                });
            }
        })

        $(function() {
            $("#table").DataTable({
                "order": [[ 0, "desc" ]],
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            })
        });
    </script>
</define>