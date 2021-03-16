<!DOCTYPE html>
<html>
	<head>
		<?php

$this->load->view("common/common_css");

?>
			<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
			<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
			<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.1/css/buttons.dataTables.min.css" />
			<!-- daterange picker -->
			<link rel="stylesheet" href="<?php

echo base_url("theme/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css ");

?>" />
			<link rel="stylesheet" href="<?php

echo base_url("theme/plugins/timepicker/bootstrap-timepicker.min.css ");

?>" />
			<!-- bootstrap datepicker -->
			<style>
				@media print {
				.btn-primary {
				display:none;
				}

				.action {
				display:none;
				}

				.pros_assign {
				display:none;
				}

				table table {
				border:none;
				}
				}
			</style>
	</head>
	<body class="hold-transition skin-blue sidebar-mini">
		<div class="wrapper">
			<?php

$this->load->view("common/header");

?>
				<!-- Left side column. contains the logo and sidebar -->
				<?php

$this->load->view("common/sidebar");

?>
					<!-- Content Wrapper. Contains page content -->
					<div class="content-wrapper">
						<!-- Content Header (Page header) -->
						<section class="content-header">
							<h1>
								<?php echo _l("Appointment"); ?>
							</h1>
							<ol class="breadcrumb">
								<li>
									<input type="button" value="Print" onclick="window.print()" class="con_txt2 non-print btn btn-default" />
									<a href="<?php echo site_url('appointment'); ?>" class="btn btn-primary non-print" style="color:white">Back</a>
								</li>
							</ol>
						</section>
						<!-- Main content -->
						<section class="content">
							<div id="container_message">
								<?php echo $this->session->flashdata("message"); ?>
							</div>
							<?php $user_type_id = _get_current_user_type_id($this);
if ($user_type_id == USER_ADMIN && $details->status < STATUS_COMPLETED)
{

?>
								<section class="pros_assign">
									<div class="">
										<div class="">
											<form action="" id="form" method="post" enctype="multipart/form-data">
												<div class="box">
													<!-- /.box-header -->
													<div class="box-body">
														<div class="col-md-6">
															<input type="hidden" name="id" value="<?php

    if (!empty($field) && !empty($field->id))
    {
        echo $field->id;
    }

?>" />
															<div class="form-group">
																<label class="">
																	<?php echo _l("Select Pros"); ?> :
																	<span class="text-danger">
																		*
																	</span>
																</label>
																<select class="form-control" name="pros_id">
																	<?php

    foreach ($pros_list as $cat)
    {
		$select=($details->pros_id==$cat->id)?"selected":"";

?>
																		<option value="<?php echo $cat->id; ?>" <?php echo $select; ?>>
																			<?php echo $cat->pros_name; ?>
																		</option>
																		<?php
    }
?>
																</select>
															</div>
															<input type="submit" class="btn btn-primary" name="addpros" value="<?php echo _l("Add"); ?>" />
														</div>
														<div class="col-md-6  ">
															<div class=" bootstrap-timepicker">
																<label class="">
																	<?php echo _l("Visit At"); ?>:
																	<span class="text-danger">
																		*
																	</span>
																</label>
																<div class="input-group ">
																	<div class="input-group-addon">
																		<i class="fa fa-clock-o">
																		</i>
																	</div>
																	<input type="text" class="form-control timepicker pull-right" name="visit_at" value="<?php

    echo ($details->visit_at != "00:00:00
																	") ? date(TIMEPICKER_FORMATE, strtotime($details->visit_at)) :
        date(TIMEPICKER_FORMATE, strtotime($details->start_time));

?>" id="visit_at" />
																</div>
															</div>
															<style>
																.bootstrap-timepicker .form-control {
																padding:0px;
																}
															</style>
														</div>
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
								<?php

}

?>
									<div class="row">
										<div class="col-xs-12">
                                            																<?php $user_type_id = _get_current_user_type_id($this);
if ($user_type_id == USER_ADMIN)
{
    echo "";
} else
{

?>
<div class="box">
    <div class="box-body">
    
																	<?php

    if ($details->status != STATUS_COMPLETED)
    {

?>
																		
																			<select style="float: right;" class='tgl_checkbox form-control' data-table="appointment" data-status="status" data-idfield="id" data-id="<?php echo $details->id; ?>" id='cb_<?php echo $details->id; ?>'>
																				<?php

        if ($details->status == STATUS_PENDING)
        {

?>
																					<option <?php if ($details->status == STATUS_PENDING){ echo "selected"; } ?> value="
																						<?php

            echo STATUS_PENDING;

?>
																							" ><?php echo _l("Pending"); ?>
																					</option>
																					<option <?php

            if ($details->status == STATUS_ASSIGNED)
            {
                echo "selected";
            }

?> value="
																						<?php

            echo STATUS_ASSIGNED;

?>
																							" ><?php echo _l("Assign"); ?>
																					</option>
																					<?php

        } else
            if ($details->status == STATUS_ASSIGNED)
            {

?>
																						<option <?php

                if ($details->status == STATUS_ASSIGNED)
                {
                    echo "selected";
                }

?> value="
																							<?php

                echo STATUS_ASSIGNED;

?>
																								" ><?php echo _l("Assign"); ?>
																						</option>
																						<option <?php

                if ($details->status == STATUS_STARTED)
                {
                    echo "selected";
                }

?> value="
																							<?php

                echo STATUS_STARTED;

?>
																								" ><?php echo _l("Start"); ?>
																						</option>
																						<?php

            } else
                if ($details->status == STATUS_STARTED)
                {

?>
																							<option <?php

                    if ($details->status == STATUS_STARTED)
                    {
                        echo "selected";
                    }

?> value="
																								<?php

                    echo STATUS_STARTED;

?>
																									" ><?php echo _l("Start"); ?>
																							</option>
																							<option <?php

                    if ($details->status == STATUS_COMPLETED)
                    {
                        echo "selected";
                    }

?> value="
																								<?php

                    echo STATUS_COMPLETED;

?>
																									" ><?php echo _l("Completed"); ?>
																							</option>
																							<?php

                }

?>
																			</select>
																	
																		<?php

    }

?>
</div>
</div>																			<?php

}

?>
											<div class="box">
												<!-- /.box-header -->
												<div class="box-body">
													<?php $this->load->view("appointment/details_table",array("allow_edit"=>true)); ?>
													<br />
													<!-- Job Assign To Pros -->
												</div>
												<!-- /.box-body -->
											</div>
											<!-- /.box -->
										</div>
										<!-- /.col -->
									</div>
									<!-- /.row -->
						</section>
						<!-- /.content -->
					</div>
					<!-- /.content-wrapper -->
					<div class="container">
						<div class="modal fade" id="myModal" role="dialog">
							<div class="modal-dialog">
								<!-- Modal content-->
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">
											&times;
										</button>
										<h4 class="modal-title">
											Add Services
										</h4>
									</div>
									<div class="modal-body">
										<form action="" id="form_services">
											<input type="hidden" name="appointment_id" value="<?php

echo $details->id;

?>" />
											<input type="hidden" value="" name="appointment_services_id"/>
											<div class="form-group">
												<label class="">
													Services :
													<span class="text-danger">
														*
													</span>
												</label>
												<select class="form-control" name="service_id">
													<option>
														----- Select Services -----
													</option>
													<?php

foreach ($services as $ser)
{

?>
														<option value="<?php

    echo $ser->id;

?>">
															<?php

    echo $ser->service_title;

?>
														</option>
														<?php

}

?>
												</select>
											</div>
											<div class="form-group">
												<label class="">
													Service Qty :
													<span class="text-danger">
														*
													</span>
												</label>
												<input type="text" name="service_qty" class="form-control" placeholder="Services Qty" />
											</div>
										</form>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-danger" data-dismiss="modal">
											Cancel
										</button>
										<button type="button" id="btnSave" onclick="save()" class="btn btn-primary">
											Save
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="container">
						<div class="modal fade" id="myModalextra" role="dialog">
							<div class="modal-dialog">
								<!-- Modal content-->
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">
											&times;
										</button>
										<h4 class="modal-title">
											Add Extra
										</h4>
									</div>
									<div class="modal-body">
										<form action="" id="form_extra">
											<input type="hidden" name="appointment_id" value="<?php

echo $details->id;

?>" />
											<input type="hidden" value="" name="appo_extra_id"/>
											<div class="form-group">
												<label class="">
													Title :
													<span class="text-danger">
														*
													</span>
												</label>
												<input type="text" name="title" class="form-control" placeholder="Title" />
											</div>
											<div class="form-group">
												<label class="">
													Charge :
													<span class="text-danger">
														*
													</span>
												</label>
												<input type="text" name="charge" class="form-control" placeholder="Charge" />
											</div>
											<div class="form-group">
												<label class="">
													Extra Qty :
													<span class="text-danger">
														*
													</span>
												</label>
												<input type="text" name="qty" class="form-control" placeholder="Qty" />
											</div>
										</form>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-danger" data-dismiss="modal">
											Cancel
										</button>
										<button type="button" id="btnSave" onclick="extra()" class="btn btn-primary">
											Save
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php

$this->load->view("common/footer");

?>
						<!-- /.control-sidebar -->
						<!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
						<div class="control-sidebar-bg">
						</div>
		</div>
		<!-- ./wrapper -->
		<!-- jQuery 3 -->
		<?php

$this->load->view("common/common_js");

?>
			<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js">
			</script>
			<script src="<?php

echo base_url("theme/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js ");

?>">
			</script>
			<script src="<?php

echo base_url("theme/plugins/timepicker/bootstrap-timepicker.min.js ");

?>">
			</script>
			<script>
				$(function() {
					$('.timepicker').timepicker({
						showInputs: true
					});
					$("body").on("change", ".tgl_checkbox", function() {
						var table = $(this).data("table");
						var status = $(this).data("status");
						var id = $(this).data("id");
						var id_field = $(this).data("idfield");
						var bin = $(this).val();

						$.ajax({
							method: "POST",
							url: "<?php echo site_url("appointment/change_status");?>",
							data: {
								table: table,
								status: status,
								id: id,
								id_field: id_field,
								on_off: bin
							}
						}).done(function(msg) {

						});
					});
				});
			</script>
			<script type="text/javascript">
				$(document).ready(function() {
					$('#table_id').DataTable();
				});
				var save_method; //for save method string
				var table;

				function add_services() {
					save_method = 'add';
					$('#form_services')[0].reset(); // reset form on modals
					$('#modal_form').modal('show'); // show bootstrap modal

				}

				function save() {
					var url;
					if (save_method == 'add') {
						url = "<?php

echo site_url('appointment/service_add');

?>"
					} else {
						url = "<?php

echo site_url('appointment/services_update')

?>";
					}
					// ajax adding data to database
					$.ajax({
						url: url,
						type: "POST",
						data: $('#form_services').serialize(),
						dataType: "JSON",
						success: function(data) {
							//if success close modal and reload ajax table
							// $('#myModal').modal('hide');
							location.reload(); // for reload a page
						},
						error: function(jqXHR, textStatus, errorThrown) {
							alert('Error adding / update data');
						}
					});
				}

				function edit_service(appointment_services_id) {
					save_method = 'update';
					$('#form_services')[0].reset(); // reset form on modals

					//Ajax Load data from ajax
					$.ajax({
						url: "<?php

echo site_url('appointment/ajax_edit/')

?>" + appointment_services_id,
						type: "GET",
						dataType: "JSON",
						success: function(data) {
							$('[name="appointment_services_id"]').val(data.appointment_services_id);
							$('[name="service_id"]').val(data.service_id);
							$('[name="service_qty"]').val(data.service_qty);


							$('#modal_form').modal('show'); // show bootstrap modal when complete loaded
							$('.modal-title').text('Edit Book'); // Set title to Bootstrap modal title

						},
						error: function(jqXHR, textStatus, errorThrown) {
							alert('Error get data from ajax');
						}
					});
				}

				function delete_service(appointment_services_id) {
					if (confirm('Are you sure delete this data?')) {
						// ajax delete data from database
						$.ajax({
							url: "<?php

echo site_url('appointment/services_delete/')

?>" + appointment_services_id,
							type: "POST",
							dataType: "JSON",
							success: function(data) {
								location.reload();
							},
							error: function(jqXHR, textStatus, errorThrown) {
								alert('Error deleting data');
							}
						});

					}
				}

				/*  EXtra Add Code ****/

				function add_extras() {
					save_method = 'extraadd';
					$('#form_extra')[0].reset(); // reset form on modals
					$('#myModalextra').modal('show'); // show bootstrap modal

				}

				function extra() {
					var url;
					if (save_method == 'extraadd') {
						url = "<?php

echo site_url('appointment/extra_add');

?>"
					} else {
						url = "<?php

echo site_url('appointment/extra_update')

?>";
					}
					// ajax adding data to database
					$.ajax({
						url: url,
						type: "POST",
						data: $('#form_extra').serialize(),
						dataType: "JSON",
						success: function(data) {
							//if success close modal and reload ajax table
							// $('#myModal').modal('hide');
							location.reload(); // for reload a page
						},
						error: function(jqXHR, textStatus, errorThrown) {
							alert('Error adding / update data');
						}
					});
				}

				function edit_extra(appo_extra_id) {
					save_method = 'update';
					$('#form_extra')[0].reset(); // reset form on modals

					//Ajax Load data from ajax
					$.ajax({
						url: "<?php

echo site_url('appointment/ajax_edit_extra/')

?>" + appo_extra_id,
						type: "GET",
						dataType: "JSON",
						success: function(data) {
							$('[name="appo_extra_id"]').val(data.appo_extra_id);
							$('[name="title"]').val(data.title);
							$('[name="charge"]').val(data.charge);
							$('[name="qty"]').val(data.qty);


							$('#myModalextra').modal('show'); // show bootstrap modal when complete loaded
							$('.modal-title').text('Edit Book'); // Set title to Bootstrap modal title

						},
						error: function(jqXHR, textStatus, errorThrown) {
							alert('Error get data from ajax');
						}
					});
				}

				function delete_extra(appo_extra_id) {
					if (confirm('Are you sure delete this data?')) {
						// ajax delete data from database
						$.ajax({
							url: "<?php

echo site_url('appointment/extra_delete/')

?>" + appo_extra_id,
							type: "POST",
							dataType: "JSON",
							success: function(data) {
								location.reload();
							},
							error: function(jqXHR, textStatus, errorThrown) {
								alert('Error deleting data');
							}
						});

					}
				}
			</script>
	</body>

</html>