<table class="table table-bordered data_table">
														<thead>
															<tr>

															</tr>
														</thead>
														<tbody>
															<tr>
																<th colspan="4">
																	<h3 style="text-align: center;">
																		<?php echo _l("Job Card"); ?>
																	</h3>
																</th>
															</tr>
															<tr>
																<td colspan="4">
																	<table class="table" style="border: none;">
																		<tr>
																			<td colspan="2">
																				<strong>
																					<?php echo _l("Appointment Number"); ?> :
																				</strong>
																				<?php

echo $details->id;

?>
																			</td>
																			<td colspan="2">
																				<strong>
																					<?php echo _l("Appointment Date"); ?> :
																				</strong>
																				<?php

echo date(COM_DATE_FORMATE,strtotime($details->appointment_date));

?>
																			</td>
																		</tr>
																		<tr>
																			<td colspan="2">
																				<strong>
																					<?php echo _l("Job Details"); ?> :
																				</strong>
																				<br />
																				<strong>
																					<?php echo _l("Customer Name"); ?> :
																				</strong>
																				<?php

echo $details->delivery_fullname;

?>
																					<br />
																					<strong>
																						<?php echo _l("Customer Contact"); ?> :
																					</strong>
																					<?php

echo $details->delivery_mobilenumber;

?>
																						<br />
																						<strong>
																							<?php echo _l("Address"); ?> :
																						</strong>
																						<address>
																							<?php

echo $details->delivery_address;

?>
																								<br />
																						</address>
																						<br />
																						<strong>
																							<?php echo _l("Work Start Time Approx"); ?> :
																						</strong>
																						<?php

echo $details->start_time;

?>
																							</p>
																			</td>
																			<td colspan="2">
																				<strong>
																					Pros Details :
																				</strong>
																				<br />
																				<strong>
																					User Name :
																				</strong>
																				<?php

echo $details->user_fullname;

?>
																					<br />
																					<strong>
																						User Email :
																					</strong>
																					<?php

echo $details->user_email;

?>
																						<br />
																						<strong>
																							Phone :
																						</strong>
																						<?php

echo $details->user_phone;

?>
																							<br />
																							<br />
																							<strong>
																								Services :
																							</strong>
																							<br />
																							<strong>
																								Start Time :
																							</strong>
																							<?php


echo ($details->start_at!='0000-00-00 00:00:00')?date(DATETIME_FORMATE,strtotime($details->start_at)):"";
?>
																								<br />
																								<strong>
																									End Time :
																								</strong>
																								<?php

echo ($details->end_at!='0000-00-00 00:00:00')?date(DATETIME_FORMATE,strtotime($details->end_at)):"";

?>
																									<br />
																									<strong>
																										Total Spent :
																									</strong>
																									<?php

$to_time = strtotime($details->end_at);
$from_time = strtotime($details->start_at);
$time_diff = $to_time - $from_time;
echo gmdate('H:i:s', $time_diff);

?>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
															<tr>
																<th>
																	Service Name
																</th>
																<th>
																	Qty
																</th>
																<th>
																	Total Price
																	<?php

echo $this->config->item("currency");

?>
																</th>
                                                                <?php if(isset($allow_edit) && $allow_edit===true && $details->status != STATUS_COMPLETED){ ?>
																<th class="action" width="100">
																	Action
																</th>
                                                                <?php } ?>
															</tr>
															<?php

$total_price = 0;
foreach ($appointment_items as $items)
{

?>
																<tr>
																	<td>
																		<?php

    echo $items->service_title

?>
																			<br />
																			<?php

    echo $this->config->item("currency");

?>
																				<?php

    echo $items->service_amount;

?>
																	</td>
																	<td>
																		<?php

    echo $items->service_qty;

?>
																	</td>
																	<td>
																		<?php

    echo $items->service_qty * $items->service_amount;
    $total_price = $total_price + ($items->service_qty * $items->service_amount);

?>
																	</td>
                                                                    <?php if(isset($allow_edit) && $allow_edit===true && $details->status != STATUS_COMPLETED){ ?>
																	<td class="action">
																		<div class="btn-group">
																			<button class="btn btn-success btn-sm" onclick="edit_service(<?php

    echo $items->appointment_services_id;

?>)" data-toggle="modal" data-target="#myModal">
																				<i class="glyphicon glyphicon-pencil">
																				</i>
																			</button>
																			<button class="btn btn-danger btn-sm" onclick="delete_service(<?php

    echo $items->appointment_services_id;

?>)">
																				<i class="glyphicon glyphicon-remove">
																				</i>
																			</button>
																		</div>
																	</td>
                                                                    <?php } ?>
																</tr>
																<?php

}

?>
																	<tr>
																		<td colspan="2">
																			<strong class="pull-right">
																				Total Amount :
																			</strong>
																		</td>
																		<td colspan="2">
																			<strong class="">
																				<?php

echo $total_price;

?>
																					<?php

echo $this->config->item("currency");

?>
																			</strong>
																		</td>
																	</tr>
																	<!--======================Appoinment Extra=======================-->
																    <tr>
																			<td colspan="4">
																				&nbsp;
																			</td>
																		</tr>
																		<tr>
																			<td colspan="4">
																				<h3>
																				<span class="pros_assign">	
																					Extras
																				</span>
																				<?php if(isset($allow_edit) && $allow_edit===true && $details->status != STATUS_COMPLETED){ ?>																				
																					<button type="button" class="btn btn-primary" onclick="add_extras()" style="float: right;">
																						ADD
																					</button>
																				<?php } ?>	
																				</h3>
																			</td>
																		</tr>
																		<?php if(!empty($appointment_extra_item)){ ?>
																		<tr>
																			<th>
																				Extar Service Name
																			</th>
																			<th>
																				Qty
																			</th>
																			<th>
																				Total Price
																				<?php

    echo $this->config->item("currency");

?>
																			</th>
																			<?php if(isset($allow_edit) && $allow_edit===true && $details->status != STATUS_COMPLETED){ ?>
																			<th class="action">
																				Action
																			</th>
																			<?php } ?>
																		</tr>
																		<?php

    //$total_price = 0;
    foreach ($appointment_extra_item as $extra)
    {

?>
																			<tr>
																				<td>
																					<?php

        echo $extra->title

?>
																						<br />
																						<?php

        echo $this->config->item("currency");

?>
																							<?php

        echo $extra->charge;

?>
																				</td>
																				<td>
																					<?php

        echo $extra->qty;

?>
																				</td>
																				<td>
																					<?php

        echo $extra->qty * $extra->charge;
        $total_price = $total_price + ($extra->qty * $extra->charge);

?>
																				</td>
																				<?php if(isset($allow_edit) && $allow_edit===true && $details->status != STATUS_COMPLETED){ ?>
																				<td class="action">
																					<div class="btn-group">
																						<button class="btn btn-success btn-sm" onclick="edit_extra(<?php

        echo $extra->appo_extra_id;

?>)" data-toggle="modal" data-target="#myModalextra">
																							<i class="glyphicon glyphicon-pencil">
																							</i>
																						</button>
																						<button class="btn btn-danger btn-sm" onclick="delete_extra(<?php

        echo $extra->appo_extra_id;

?>)">
																							<i class="glyphicon glyphicon-remove">
																							</i>
																						</button>
																					</div>
																				</td>
																				<?php } ?>
																			</tr>
																			<?php

    }

?>
																				<tr>
																					<td colspan="2">
																						<strong class="pull-right">
																							Net Total Amount :
																						</strong>
																					</td>
																					<td colspan="2">
																						<strong class="">
																							<?php

    echo $total_price;

?>
																								<?php

    echo $this->config->item("currency");

?>
																						</strong>
																					</td>
																				</tr>
																		<?php } ?>
																					<!--====================end Extra==================================-->
														</tbody>
													</table>