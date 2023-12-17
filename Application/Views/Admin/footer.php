<?php

use System\Helpers\URL;
?>
</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
  Copyright 2022 <a href="https://telein.net">teleIN</a> All Rights Reserved.
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<script>
   var URLS = {
    location_get_cities: '<?= URL::full('ajax/location/cities') ?>',
    get_order_status_modal: '<?= URL::full('ajax/admin/order/status/modal') ?>',
    order_cancel: '<?= URL::full('ajax/admin/order/cancel') ?>',
    order_hold: '<?= URL::full('ajax/admin/order/hold') ?>',
   };
</script>

<!-- jQuery -->
<script src="<?= URL::asset('Application/Assets/Admin/plugins/jquery/jquery.min.js') ?>"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= URL::asset('Application/Assets/Admin/plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?= URL::asset('Application/Assets/Admin/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- ChartJS -->
<script src="<?= URL::asset('Application/Assets/Admin/plugins/chart.js/Chart.min.js') ?>"></script>
<!-- Sparkline -->
<script src="<?= URL::asset('Application/Assets/Admin/plugins/sparklines/sparkline.js') ?>"></script>
<!-- JQVMap -->
<script src="<?= URL::asset('Application/Assets/Admin/plugins/jqvmap/jquery.vmap.min.js') ?>"></script>
<script src="<?= URL::asset('Application/Assets/Admin/plugins/jqvmap/maps/jquery.vmap.usa.js') ?>"></script>
<!-- jQuery Knob Chart -->
<script src="<?= URL::asset('Application/Assets/Admin/plugins/jquery-knob/jquery.knob.min.js') ?>"></script>
<!-- daterangepicker -->
<script src="<?= URL::asset('Application/Assets/Admin/plugins/moment/moment.min.js') ?>"></script>
<script src="<?= URL::asset('Application/Assets/Admin/plugins/daterangepicker/daterangepicker.js') ?>"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?= URL::asset('Application/Assets/Admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
<!-- Summernote -->
<script src="<?= URL::asset('Application/Assets/Admin/plugins/summernote/summernote-bs4.min.js') ?>"></script>
<!-- overlayScrollbars -->
<script src="<?= URL::asset('Application/Assets/Admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?= URL::asset('Application/Assets/Admin/js/adminlte.js') ?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= URL::asset('Application/Assets/Admin/js/demo.js') ?>"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?= URL::asset('Application/Assets/Admin/js/pages/dashboard.js') ?>"></script>

<!-- Datatables -->
<script src="<?= URL::asset('Application/Assets/Admin/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= URL::asset('Application/Assets/Admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= URL::asset('Application/Assets/Admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= URL::asset('Application/Assets/Admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= URL::asset('Application/Assets/Admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
<script src="<?= URL::asset('Application/Assets/Admin/plugins/datatables-buttons/js/buttons.html5.min.js') ?>"></script>
<script src="<?= URL::asset('Application/Assets/Admin/plugins/datatables-buttons/js/buttons.print.min.js') ?>"></script>
<script src="<?= URL::asset('Application/Assets/Admin/plugins/datatables-buttons/js/buttons.colVis.min.js') ?>"></script>

<!-- Select2 JavaScript -->
<!--<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>-->
<!-- Select2 JavaScript -->
<script src="<?= URL::asset('Application/Assets/js/select2.min.js'); ?>"></script>

<!-- Boot box -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>

<script src="<?= URL::asset('Application/Assets/Admin/js/custom.js') ?>"></script>

<call footer_js />
</body>

</html>