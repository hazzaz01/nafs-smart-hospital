<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>
<div class="fixed-print-header">
     <?php if (!empty($print_details[0]['print_header'])) { ?>
        <div>
            <img src="<?php
                                if (!empty($print_details[0]['print_header'])) {
                                    echo $this->media_storage->getImageURL($print_details[0]['print_header']);
                                }
                                ?>" class="img-responsive" style="height:100px; width: 100%;">
        </div>
    <?php } ?>
</div>    
<table class="table-print-full" width="100%">
    <thead>
        <tr>
            <td><div class="header-space">&nbsp;</div></td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
      <div class="content-body">
<div class="print-area">
    <div class="row">
        <div class="col-md-12">
           
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12" style="padding-top:10px">
                            <table class="noborder_table">
                                <tr>
                                    <th><?php echo $this->lang->line("opd_id"); ?></th>
                                    <td><?php echo $opd_prefix . $result["opd_details_id"]; ?></td>
                                    <th><?php echo $this->lang->line("checkup_id"); ?></th>
                                    <td><?php echo $checkup_prefix . $result["id"] ?></td>
                                    <th><?php echo $this->lang->line("appointment_date"); ?></th>
                                    <td><?php echo $this->customlib->YYYYMMDDHisTodateFormat($result["appointment_date"], $this->customlib->getHospitalTimeFormat()); ?></td>
                                </tr>
                                <?php if ($result["appointment_no"] != "" || $result["appointment_serial_no"]) { ?>
                                    <tr>
                                        <th><?php echo $this->lang->line("appointment_no"); ?></th>
                                        <td><?php if ($result["appointment_no"] != "") {
                                                echo $this->customlib->getSessionPrefixByType('appointment') . $result["appointment_no"];
                                            } ?></td>
                                        <th><?php echo 'Appointment S.No'; ?></th>
                                        <td><?php echo $result["appointment_serial_no"] ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <th><?php echo $this->lang->line("patient_name"); ?></th>
                                    <td><?php echo $result["patient_name"] . ' (' . $result["patient_id"] . ')' ?></td>  
                                    <th><?php echo $this->lang->line("age"); ?></th>
                                    <td><?php echo $this->customlib->get_patient_current_age($result["patient_id"]);?></td> 
                                    <th><?php echo $this->lang->line("gender"); ?></th>
                                    <td><?php echo (isset($result["gender"])) ? $this->lang->line(strtolower($result["gender"])) : ""; ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo $this->lang->line("blood_group"); ?></th>
                                    <td><?php echo $blood_group_name; ?></td>
                                    <th><?php echo $this->lang->line('known_allergies'); ?></th>
                                    <td><?php echo $result["known_allergies"]; ?></td>
                                    <th><?php echo $this->lang->line('department'); ?></th>
                                    <td colspan="3"><?php echo (isset($result["department_name"])) ? $this->lang->line(strtolower($result["department_name"])) : "" ; ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo $this->lang->line("address"); ?></th>
                                    <td><?php echo $result["address"] ?></td>                                    
                                </tr>
                                <tr>
                                    <th><?php echo $this->lang->line('consultant_doctor'); ?></th>
                                    <td><?php echo $result["name"] . " " . $result["surname"] . ' (' . $result["employee_id"] . ')' ?></td>                                    
                                </tr>                               
                                
                    <?php if (!empty($fields)) {
                        foreach ($fields as $fields_key => $fields_value) {
                    ?>
                            <tr>
                                <th><?php echo $fields_value->name; ?></th>
                                <td colspan="5"><?php echo $result[$fields_value->name]; ?></td>
                            </tr>
                    <?php }
                    } ?>
                            </table>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <h4 class="heading-title"><?php echo $this->lang->line("payment_details"); ?></h4>
                    <?php 
                    if (!empty($charge)) {
                    ?>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="print-table">
                                    <thead>
                                        <tr class="line">
                                            <td>#</td>
                                            <td><?php echo $this->lang->line('description'); ?></td>
                                            <td><?php echo $this->lang->line('tax') . ' (' . '%' . ')'; ?></td>
                                            <td class="text-right"><?php echo $this->lang->line('amount') . ' (' . $currency_symbol . ')'; ?></td>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php 
										 
										$total_standard_charge=0; $total_discount=0; $total_tax =0; $total_apply_charge=0;
										
										foreach($charge as $charge_value){ 
										
											$discount = 0;
											$discount_amt = 0;										 
									
											$total_standard_charge += $charge_value['standard_charge'];
											$total_apply_charge += $charge_value['apply_charge'];											
											
											$discount = ($charge_value['standard_charge']*$charge_value['discount_percentage'])/100 ;										
											$total_discount += $discount;											
											
											if ($charge_value['tax'] > 0) {
												$tax = amountFormat((($charge_value['standard_charge']-$discount) * $charge_value['tax']) / 100);
											} else {
												$tax = 0;
											}																	
											$total_tax += $tax;
										?>
                                        <tr>
                                            <td>1</td>
                                            <td><?php echo $charge_value['name']; ?><br>
                                                <?php echo $charge_value['note']; ?>
                                            </td>
                                            <td><?php echo amountFormat($tax) . " (" . $charge_value['tax'] . "%)"; ?></td>
                                            <td class="text-right"><?php										
											echo amountFormat($charge_value['standard_charge']); ?></td>
                                        </tr>
										<?php } ?>
										
                                        <tr><td colspan="4" class="p-0" style="padding:0"></td></tr>
                                        <tr>
                                            <td colspan="3" class="text-right no-line"><?php echo $this->lang->line('amount'); ?></td>
                                            <td class="text-right no-line"><?php echo $currency_symbol . amountFormat($total_standard_charge); ?></td>
                                        </tr>
										
                                        <tr>
                                            <td colspan="3" class="text-right no-line"><?php echo $this->lang->line('discount'); ?></td>
                                            <td class="text-right no-line"><?php 											 
											$total_discount_per =  ($total_discount*100 / $total_standard_charge );
											echo $currency_symbol . amountFormat($total_discount); echo " (".amountFormat($total_discount_per)."%)";?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right no-line"><?php echo $this->lang->line('tax'); ?></td>
                                            <td class="text-right no-line"><?php 
											$total_tax_per =  ($total_tax*100 / ($total_standard_charge-$total_discount) );												
											echo $currency_symbol . amountFormat($total_tax); echo " (".amountFormat($total_tax_per)."%)";?></td>
                                        </tr>									
                                        <tr>
                                            <td colspan="3" class="text-right no-line"><?php echo $this->lang->line('net_amount'); ?></td>
                                            <td class="text-right no-line"><?php 
											$toatl_amt = $total_standard_charge-$total_discount+$total_tax ;											
											echo $currency_symbol. amountFormat($toatl_amt) ; ?></td>
                                        </tr> 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
	
</div>
</div>
    <tfoot><tr><td>

<?php
if (!empty($print_details[0]['print_footer'])) { ?>
   <div class="footer-space">&nbsp;</div>
<?php } ?>
</td>
</tr>
</tfoot>
</table>
<?php
 if (!empty($print_details[0]['print_footer'])) { ?>
<div class="footer-fixed">
<?php   echo $print_details[0]['print_footer'];?>            
</div>
<?php
}
?>