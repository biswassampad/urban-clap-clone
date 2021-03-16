<script src="<?php echo base_url("theme/bower_components/jquery/dist/jquery.min.js"); ?>"></script>
<!-- Bootstrap 3.3.7 -->

<script src="<?php echo base_url("theme/bower_components/bootstrap/dist/js/bootstrap.min.js"); ?>"></script>
<!-- PACE -->
<script src="<?php echo base_url("theme/bower_components/PACE/pace.min.js"); ?>"></script>
<!-- DataTables -->
<script src="<?php echo base_url("theme/bower_components/datatables.net/js/jquery.dataTables.min.js"); ?>"></script>
<script src="<?php echo base_url("theme/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"); ?>"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url("theme/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"); ?>"></script>
<!-- FastClick -->
<script src="<?php echo base_url("theme/bower_components/fastclick/lib/fastclick.js"); ?>"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url("theme/dist/js/adminlte.min.js"); ?>"></script>
<script src="<?php echo base_url("theme/dist/js/common.js"); ?>"></script>
<!-- page script -->
<script type="text/javascript">
  // To make Pace works on Ajax calls
  $(document).ajaxStart(function () {
    Pace.restart()
  });
</script>
<script>
    $(function() {
             $.validate({
                lang: 'en',
                modules : 'security'
            });
    });
</script>
