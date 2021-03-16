<div id="crop-avatar">    
    <!-- Cropping modal -->
    <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form class="avatar-form" action="<?php echo site_url("crop/image"); ?>" enctype="multipart/form-data" method="post">
            <div class="modal-header">
              <button type="button" class=" btn btn-danger pull-right" data-dismiss="modal">&times;</button>
			  <button type="button" class="load_image pull-right btn btn-default">Load Image</button>
              <h4 class="modal-title" id="avatar-modal-label">Upload Image</h4>
            </div>
            <div class="modal-body">
              <div class="avatar-body">

                <!-- Upload image and data -->
                <div class="avatar-upload">
                  <input type="hidden" class="avatar-src" name="avatar_src">
                  <input type="hidden" class="avatar-data" name="avatar_data">
                  <input type="file" class="avatar-input" id="avatarInput" name="avatar_file">
                </div>

                <!-- Crop and preview -->
                <div id="main_crop_area">
                <div class="row" >
                  <div class="col-md-12">
                    <div class="avatar-wrapper"></div>
                  </div>
                  
                </div>

                <div class="row avatar-btns">
                  <div class="col-md-9">
                    
                  </div>
                  <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-block avatar-save">Done</button>
                  </div>
                </div>
                </div>
              </div>
            </div>
            <!-- <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div> -->
          </form>
        </div>
      </div>
    </div><!-- /.modal -->
    <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
</div>
<link rel="stylesheet" href="<?php echo base_url("third_party/crop/dist/cropper.min.css"); ?>">
<link rel="stylesheet" href="<?php echo base_url("third_party/crop/css/main.css"); ?>">    
<script src="<?php echo base_url("third_party/crop/dist/cropper.min.js"); ?>"></script>
<script src="<?php echo base_url("third_party/crop/js/main.js"); ?>"></script>