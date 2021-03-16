<!DOCTYPE html>
<html>
<head>
  
  <?php $this->load->view("common/common_css"); ?>
  <script src="<?php echo base_url("theme/plugins/ckeditor/ckeditor.js"); ?>"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php  $this->load->view("common/header"); ?>
  <!-- Left side column. contains the logo and sidebar -->
  <?php  $this->load->view("common/sidebar"); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo _l("Page"); ?>
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url($this->router->fetch_class()); ?>" class="btn btn-sm btn-default"><i class="fa fa-list"></i> <?php echo _l("List"); ?></a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div id="container_message">
        <?php echo $this->session->flashdata("message"); ?>
      </div>  
      <div class="row">
        <div class="col-xs-12">
            <form action="" id="form" method="post" enctype="multipart/form-data">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                                        
                        <input type="hidden" name="id" value="<?php if(!empty($field) && !empty($field->id)){ echo $field->id; } ?>" />
                                        <div class="form-group">
                                            <label class=""><?php echo _l("Name"); ?> : <span class="text-danger">*</span></label>
                                            <input type="text" name="pg_title" class="form-control" value="<?php echo _get_post_back($field,'pg_title'); ?>" placeholder="<?php echo _l("pg_title"); ?>" />
                                        </div>
                                        <div class="form-group">
                                            <label class=""><?php echo _l("Page Description"); ?> : <span class="text-danger">*</span></label>
                                            <textarea class="ckeditor form-control" id="page_media" rows="6" data-error-container="#editor2_error" name="pg_descri"><?php echo _get_post_back($field,'pg_descri'); ?></textarea>
                        					<div id="editor2_error">
                        					</div>
                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4" style="padding-left: 30px;"> 
                                                <div class="form-group"> 
                                                    <div class="radio">
                                                        <label class="text-success">
                                                            <input type="radio" name="pg_status" id="optionsRadios1" value="1"  checked="" />
                                                            <strong><?php echo _l("Active"); ?></strong>
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label class="text-danger">
                                                            <input type="radio" name="pg_status" id="optionsRadios2" value="0" />
                                                            <strong><?php echo _l("Deactive"); ?></strong>
                                                        </label>
                                                    </div>
                                                    <p class="help-block"><?php echo _l("Set Page Status"); ?>.</p>
                                                </div>        
                                            </div>
                                            <div class="col-md-6"> 
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label">Set this page in : </label>
                                                    <div class="col-sm-6">
                                                             <div class="form-group">
                                            <label class="">On Footer : <span class="text-danger">*</span></label>
                                            <select class="text-input form-control" name="pg_foot">
                                                <option value="0"><?php echo _l("No"); ?></option>
                                                <option value="1"><?php echo _l("Yes"); ?></option>
                                            </select>
                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                       
                                   
                                        <input type="submit" class="btn btn-primary" name="addcatg" value="Add" />
										<a href="<?php echo site_url('pageapp'); ?>" class="btn btn-danger">Cancel</a>
              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          </form>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

    <?php $this->load->view("common/footer"); ?>
    
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<?php $this->load->view("common/common_js"); ?>

<?php include 'third_party/crop/main.php'; ?>    
<script src="<?php echo base_url("theme/plugins/ckeditor/ckeditor.js"); ?>" type="text/javascript"></script>
<script type="text/javascript">
            $(function() {
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace('editor1');
                //bootstrap WYSIHTML5 - text editor
                $(".textarea").wysihtml5();
            });
        </script>
         <script>
    
    var loc = window.location.pathname;
var current_dir = loc.substring(0, loc.lastIndexOf('/'));
var current_dir = document.location.origin + current_dir;
// Enable local "bootstrapTabs" plugin from /ckeditorPlugins/bootstrapTabs/ folder.

CKEDITOR.plugins.addExternal( 'bootstrapTabs', 'plugins/bootstrapTabs/', 'plugin.js' );

CKEDITOR.config.imageUploadUrl = '<?php echo base_url("theme/plugins/ckeditor/plugins/imageuploader/imgbrowser.php"); ?>';

CKEDITOR.replace( 'page_media', {
  extraPlugins: 'imageuploader,basewidget,bootstrapTabs,embedbase,embed',
  removePlugins: 'elementspath,save,showblocks,smiley,templates,iframe,pagebreak,preview,flash,language,print,newpage,find,selectall,maximize,about',
  contentsCss: [ 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css' ],
  on: {
    instanceReady: loadBootstrap,
    mode: loadBootstrap,
    focus: loadBootstrap
  }
});
// Add the necessary jQuery and Bootstrap JS source so that tabs are clickable.
// If you're already using Bootstrap JS with your editor instances, then this is not necessary.
function loadBootstrap(event) {
    
    if (event.name == 'mode' && event.editor.mode == 'source')
        return; // Skip loading jQuery and Bootstrap when switching to source mode.

    var jQueryScriptTag = document.createElement('script');
    var bootstrapScriptTag = document.createElement('script');

    jQueryScriptTag.src = 'https://code.jquery.com/jquery-1.11.3.min.js';
    bootstrapScriptTag.src = 'assets/global/plugins/bootstrap/js/bootstrap.min_3.6.js';

    var editorHead = event.editor.document.$.head;

    editorHead.appendChild(jQueryScriptTag);
    jQueryScriptTag.onload = function() {
      editorHead.appendChild(bootstrapScriptTag);
    };
}
    </script>
</body>
</html>

