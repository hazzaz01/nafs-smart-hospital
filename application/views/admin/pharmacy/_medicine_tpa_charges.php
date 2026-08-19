<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo $this->lang->line('bill'); ?></title>
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/sh-print.css">
    </head>
    <div id="html-2-pdfwrapper" class="p-1">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="pt5">
                    <?php if (!empty($print_details[0]['print_header'])) { ?>
                    <div class="pprinta4">
                        <img src="<?php
                            if (!empty($print_details[0]['print_header'])) {
                                echo $this->media_storage->getImageURL($print_details[0]['print_header']);
                            }
                            ?>" class="img-responsive" style="height:100px; width: 100%;">
                    </div>
                    <?php } ?>
                    <div class="table-responsive">
	                    <table class="table table-bordered mb0">
	                        <tr>
	                            <td><?php echo $this->lang->line('purchase_no'); ?>  <?php echo $this->customlib->getSessionPrefixByType('purchase_no').$result["id"] ?></td>
	                            <td class="ps-sm-1"><?php echo $this->lang->line('bill_no'); ?> <?php echo $result["invoice_no"] ?></td>
	                            <td class="text-right rtl-text-left ps-sm-1"><?php echo $this->lang->line('purchase_date')?> <?php echo $this->customlib->YYYYMMDDHisTodateFormat($result['date'], $this->customlib->getHospitalTimeFormat());?></td>
	                        </tr>
	                    </table>
	                </div>    
                    <div class="divider mb5 mt-5"></div>
                    <div class="table-responsive mb10">
                        <table class="printablea4 table table-bordered mb0" cellspacing="0" cellpadding="0" width="100%">
                            <tr class="">
                                <th width="15%"><?php echo $this->lang->line('supplier_name'); ?></th>
                                <td width="35%"><?php echo $result["supplier"]; ?></td>
                                <th width="15%" class="ps-sm-1"><?php echo $this->lang->line('supplier_contact'); ?></th>
                                <td width="35%"><?php echo $result["contact"]; ?></td>
                            </tr>
                            <tr class="">
                                <th width="15%"><?php echo $this->lang->line('contact_person'); ?></th>
                                <td width="35%"><?php echo $result["supplier_person"]; ?></td>
                                <th width="15%" class="ps-sm-1"><?php echo $this->lang->line('contact_person_phone'); ?></th>
                                <td width="35%"><?php echo $result["supplier_person_contact"]; ?></td>                        
                            </tr>
                            <tr class="">
                                <th width="15%"><?php echo $this->lang->line('drug_license_number'); ?></th>
                                <td width="35%"><?php echo $result["supplier_drug_licence"]; ?></td>
                                <th width="15%" class="ps-sm-1"><?php echo $this->lang->line('address'); ?></th>
                                <td width="35%"><?php echo $result['address']; ?></td> 
                            </tr> 
                        </table>
                    </div>   
                    <div class="box pl-10 pe-10 ptt10 pb10">
	                    <div class="row">
	                        <div class="col-lg-3 col-md-3 col-sm-4">
	                        	<div class="tab-heading-body mb10">
        							<div class="blood-title"><?php echo $this->lang->line('medicine'); ?></div>
        						</div>	
	                            <ul class="nav nav-pills nav-stacked blood-stacked blood-stacked2 pb-sm-7-5">
	                                <?php $i = 1;
	                                foreach ($detail as $bill_key => $bill) { ?>
	                                    <li <?= $i == 1 ? " class='active'" : ""; ?>><a class="rounded-2" data-toggle="tab" href="#menu<?= $bill_key; ?>"><?php echo $bill["medicine_name"]; ?> (<?php echo $bill["batch_no"]; ?>) - <?php echo $bill["medicine_category"]; ?></a></li>
	                                <?php $i++;
	                                } ?>
	                            </ul>
	                        </div> 
	                        <div class="col-lg-9 col-md-9 col-sm-8">
	                            <div class="tab-content">
									<?php
										$j = 0;
										foreach ($detail as $bill_key => $bill) {
											$active_class = ($j == 0) ? "active" : "fade"; // Only make the first tab active
									?>
	                                <div class="tab-pane <?php echo $active_class; ?>" id="menu<?= $bill_key; ?>">				<div class="row">		 
	                                    <div class="col-lg-6 col-md-6 col-sm-6">
	                                    	
											<div>
												<div class="tab-heading-body mb5">
				        							<div class="blood-title"><?php echo $this->lang->line('purchase_details'); ?></div>
				        						</div>	
												<table class="printablea4" cellspacing="0" cellpadding="0" width="100%">
													<tbody>														
														<tr >
															<td width="30%"><?php echo $this->lang->line('medicine_category'); ?></td>
															<td width="30%"><?php echo $bill["medicine_category"]; ?></td>
														</tr>
														<tr >
															<td width="30%"><?php echo $this->lang->line('medicine_name'); ?></td>
															<td width="30%"><?php echo $bill["medicine_name"]; ?></td>
														</tr>
														<tr >
															<td width="30%"><?php echo $this->lang->line('batch_no'); ?></td>
															<td width="30%"><?php echo $bill["batch_no"]; ?></td>
														</tr>
														<tr >
															<td width="30%"><?php echo $this->lang->line('expiry_month'); ?></td>
															<td width="30%"><?php echo $this->customlib->getMedicine_expire_month($bill['expiry']); ?></td>
														</tr>
														<tr >
															<td width="30%"><?php echo $this->lang->line('mrp'). ' (' . $currency_symbol . ')'; ?></td>
															<td width="30%"><?php echo $bill['mrp']; ?></td>
														</tr>
														<tr >
															<td width="30%"><?php echo $this->lang->line('batch_amount'). ' (' . $currency_symbol . ')'; ?></td>
															<td width="30%"><?php echo $bill['batch_amount']; ?></td>
														</tr>
														<tr >
															<td width="30%"><?php echo $this->lang->line('sale_price'). ' (' . $currency_symbol . ')'; ?></td>
															<td width="30%"><?php echo number_format($bill['sale_rate'],2); ?>
															<input type="hidden" name="salerate[]" id="salerate" class="form-control" style="margin-left:1rem;width:80px;"
															value="<?php echo number_format($bill['sale_rate'],2); ?>">
															<input type="hidden" name="id[]" id="id" value="<?php echo $bill["id"]; ?>">					
															</td>
														</tr>
														<tr>
															<td width="30%"><?php echo $this->lang->line('packing_qty'); ?></td>
															<td width="30%"><?php echo $bill['packing_qty']; ?></td>
														</tr>
														<tr>
															<td width="30%"><?php echo $this->lang->line('quantity'); ?></td>
															<td width="30%"><?php echo $bill["quantity"]; ?></td>
														</tr>
														<tr>
															<td width="30%"><?php echo $this->lang->line('tax'); ?> (%)</td>
															<td width="30%"><?php echo $bill["tax"]; ?></td>
														</tr>
														<tr>
															<td width="30%"><?php echo $this->lang->line('purchase_price') . ' (' . $currency_symbol . ')'; ?></td>
															<td width="30%"><?php echo number_format($bill['purchase_price'],2); ?></td>
														</tr>
														<tr>
															<td width="30%"><?php echo $this->lang->line('amount') . ' (' . $currency_symbol . ')'; ?></td>
															<td width="30%"><?php echo number_format($bill["amount"], 2); ?></td>
														</tr>
													</tbody>	
												</table>
											</div>	
										</div>	
										<div class="col-lg-6 col-md-6 col-sm-6">
											<div class="pt-xs-2">
												<div class="tab-heading-body mb5">
        							<div class="blood-title"><?php echo $this->lang->line('scheduled_charges_for_tpa');?></div>
        						</div>	
												<table class="printablea4">
													<tbody>			
														<tr>																
															<td><?php echo $this->lang->line('sale_price'). ' (' . $currency_symbol . ')';?></td>
															<td><?php echo number_format($bill['sale_rate'],2); ?> </td>
															<td><!-- apply to all sale rate -->
																<button type="button" class="plusign btn-xs mb5" onclick="apply_to_all(<?php echo $bill["id"]; ?>)"><?php echo $this->lang->line('apply_to_all'); ?></button>
																<input type="hidden" name="standard_charge_<?php echo $bill["id"]; ?>" id="standard_charge_<?php echo $bill["id"]; ?>" class="form-control"  value="<?php echo $bill["sale_rate"]; ?>" readonly>
																<input type="hidden" name="batch_id_<?php echo $bill["id"]; ?>" id="batch_id_<?php echo $bill["id"]; ?>" value="<?php echo $bill["id"]; ?>" >
																<!-- apply to all sale rate --></td>
														</tr>	
														
														<?php
															foreach ($schedule as $category) {
																$batchorgcharges = $this->customlib->getbatchorgcharges($bill["id"],$category['id']);
														?>	
																	
														<tr>																
															<td width="60%" >
																<input type="hidden" name="orgnization_medicine_charge_id_<?php echo $category['id']; ?>_<?php echo $bill["id"]; ?>" value="<?php echo (isset($batchorgcharges->orgnization_medicine_charge_id)) ? $batchorgcharges->orgnization_medicine_charge_id : "";?>">
																<input type="hidden" name="schedule_charge_id_<?php echo $bill["id"]; ?>[]" value="<?php echo $category['id']; ?>"><?php echo $category['organisation_name']; ?>
															</td>
															<td colspan="2" class="rtl-text-right">	
																<input type="text" name="schedule_charge_<?php echo $category['id']; ?>_<?php echo $bill["id"]; ?>" id="schedule_charge_<?php echo $category['id']; ?>_<?php echo $bill["id"]; ?>" class="form-control text-right schedule_charge_<?php echo $bill["id"]; ?>"  value="<?php echo isset(($batchorgcharges->org_charge)) ? $batchorgcharges->org_charge : "";?>">
															</td>																	
														</tr>																
														<?php } ?>                                      
													</tbody>
												</table>											
	                                        </div><!--./row-->
	                                    </div>
	                                  </div><!--./row-->  
	                                </div><!--#/menu1-->
									<?php
										$j++;
										}
									?>
								</div><!--./tab-content-->
	                        </div><!--./col-lg-9-->
	                    </div><!--./row--> 
	                </div>    	
            </div>
        </div>
    </div>
</html>

<script type="text/javascript">
    function apply_to_all(batch_id) {
        var standard_charge = $("#standard_charge_"+batch_id).val();
        $('input.schedule_charge_'+batch_id).val(standard_charge);
    }
</script>
