
<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>
<div class="fixed-print-header">
<?php if (!empty($print_details[0]['print_header'])) {
    ?>
                        <div class="pprinta4">
                            <img src="<?php
if (!empty($print_details[0]['print_header'])) {
        echo $this->media_storage->getImageURL($print_details[0]['print_header']);
    }
    ?>" class="img-responsive" style="height:100px; width: 100%;">
                        </div>
                    <?php }?>
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
    <div id="html-2-pdfwrapper">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="">
                    
                <div class="table-responsive">    
                    <table width="100%">
                        <tr>
                            <td class="text-left" width="80%"><?php echo $this->lang->line('bill_no') ?> : <?php echo $this->customlib->getPatientSessionPrefixByType('pharmacy_billing').$result["id"] ?>
                            </td>
                            <td class="text-right"><?php echo $this->lang->line('date') . " : "; ?><?php echo date($this->customlib->getHospitalDateFormat(true, true), strtotime($result['date'])) ?>
                            </td>
                        </tr>
                    </table>
                    <div class="divider"></div>
                    <table class="printablea4" width="100%">
                        <tr>
                            <th width="10%"><?php echo $this->lang->line('name'); ?></th>
                            <td width="10%"><?php echo composePatientName($result["patient_name"],$result['patient_unique_id']); ?></td>
                            <th width="10%"><?php echo $this->lang->line('phone'); ?></th>
                            <td width="10%"><?php echo $result["mobileno"]; ?></td>
                            <th width="10%"><?php echo $this->lang->line('doctor'); ?></th>
                            <td width="10%" class="text-left"><?php echo $result["doctor_name"]; ?></td>
                        </tr>
                        <tr>
                            <th width="10%"><?php echo $this->lang->line('case_id') ?></th> 
                            <td width="10%"><?php echo $result["case_reference_id"]; ?></td>                      
                            <th width="10%"><?php echo $this->lang->line('prescription') ?></th>  
                            <td width="10%"><?php echo $prescription; ?></td>
                            <th width="10%"><?php echo $this->lang->line('tpa') ?></th>  
                            <td width="10%"><?php echo $result["organisation_name"]; ?></td>
                        </tr>
                        <tr>
                            <th width="10%"><?php echo $this->lang->line('tpa_id') ?></th> 
                            <td width="10%"><?php echo $result["insurance_id"]; ?></td>                      
                            <th width="10%"><?php echo $this->lang->line('tpa_validity') ?></th>  
                            <td width="10%"><?php echo $this->customlib->YYYYMMDDTodateFormat($result['insurance_validity']); ?></td>          
                        </tr>
                        <tr>
                            <th width="10%"><?php echo $this->lang->line('note') ?></th> 
                            <td colspan="10%"><?php echo $result["note"]; ?></td>
                        </tr>    
                        <?php
                            if (!empty($fields)) {

                                foreach ($fields as $fields_key => $fields_value) {
                                    $display_field = $result[$fields_value->name];
                                    ?>
                                    <tr>
                                        <th><?php echo $fields_value->name; ?></th>
                                        <td><?php echo $display_field; ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                        ?>
                    </table>
                    <div class="divider mb-10 mt-10"></div>
                    <table class="printablea-no-col" id="testreport" width="100%">
                        <tr>
                            <th width="20%"><?php echo $this->lang->line('medicine_category'); ?></th>
                            <th width="20%"><?php echo $this->lang->line('medicine_name'); ?></th>
                            <th><?php echo $this->lang->line('batch_no'); ?></th>
                            <th><?php echo $this->lang->line('unit'); ?></th>
                            <th><?php echo $this->lang->line('expiry_date'); ?></th>
                            <th><?php echo $this->lang->line('quantity'); ?></th>
                            <th style=""><?php echo $this->lang->line('tax'); ?></th>
                            <th class="text-right rtl-text-left"><?php echo $this->lang->line('discount'); ?></th>
                            <th style="text-align: right;"><?php echo $this->lang->line('amount') . ' (' . $currency_symbol . ')'; ?></th>
                        </tr>
                        <?php  
$j =  $total_tax =0; 
foreach ($detail as $bill) {
   
  
   if ($bill['tax'] > 0) {
                            $tax = ((($bill["sale_price"] - (($bill["sale_price"]*$result["discount_percentage"]) / 100))* $bill['tax'])/100)*$bill["quantity"] ;
                        } else {
                            $tax = 0;
                        }
  
  
  $total_tax += $tax;
    ?>
                            <tr>
                                <td width="20%"><?php echo $bill["medicine_category"]; ?></td>
                                <td width="20%"><?php echo $bill["medicine_name"]; ?></td>
                                <td><?php echo $bill["batch_no"]; ?></td>
                                <td><?php echo $bill["unit_name"]; ?></td>
                                <td><?php echo $this->customlib->getmedicine_expire_month($bill['expiry']); ?></td>
                                <td><?php echo $bill["quantity"]; ?></td>
                                <td><?php echo $bill['tax']."%"; ?></td>
                                <td class="text-right rtl-text-left"><?php echo $bill['discount'] . "%";    ?></td>
                                <td class="text-right rtl-text-left"><?php 
                                $total_sale_amount=$bill["sale_price"] * $bill["quantity"];
                                $discount_amt=(($total_sale_amount*$bill['discount'])/100);
                                echo amountFormat($total_sale_amount-$discount_amt);
                                 ?></td>
                            </tr>
                            <?php
$j++;
}
?>

                    </table>
                    <div class="divider mb-10 mt-10"></div>
                    <table class="printablea4 white-space-nowrap" style="width: 45%; float: right; padding-bottom:10px">
                        <?php 
						
						if (!empty($result["total"])) {?>
                            <tr>
                                <th class="text-right"><?php echo $this->lang->line('total') . " (" . $currency_symbol . ")"; ?></th>
                                <td class="text-right"><?php echo $result["total"]; ?></td>
                            </tr>
                        <?php }?>
                        <?php if (!empty($result["discount"])) {
    ?>
                            <tr>
                                <th class="text-right"><?php
echo $this->lang->line('discount') ;
    ?></th>
                                <td class="text-right"><?php echo $result["discount"]. " (" . $result["discount_percentage"]."%)"; ?></td>
                            </tr>
                        <?php }?>
                        <?php if (!empty($result["tax"])) {
    ?>
                            <tr>
                                <th class="text-right"><?php
echo $this->lang->line('tax') . " (" . $currency_symbol . ")";
    ?></th>
                                <td class="text-right"><?php 
								
								$tax_percentage = ($result["tax"]*100) / ($result["total"]-$result["discount"]) ;
								
								echo $result["tax"]. " (" . amountFormat($tax_percentage)."%)"; ?></td>
                            </tr>
                        <?php }?>
                        <?php
if ((!empty($result["discount"])) && (!empty($result["tax"]))) {
    if (!empty($result["net_amount"])) {
        ?>
                                <tr>
                                    <th class="text-right"><?php
echo $this->lang->line('net_amount') . " (" . $currency_symbol . ")";
        ?></th>
                                    <td class="text-right"><?php echo $result["net_amount"]; ?></td>
                                </tr>
                                <?php
}
}
?>
   <tr>
                                    <th class="text-right"><?php
echo $this->lang->line('paid_amount') . " (" . $currency_symbol . ")";
        ?></th>
                                    <td class="text-right"><?php echo amountFormat($result["paid_amount"]); ?></td>
                                </tr>
                                <tr>
                                    <th class="text-right"><?php echo $this->lang->line('refund_amount') . " (" . $currency_symbol . ")";  ?></th>
                                    <td class="text-right"><?php echo amountFormat($result["refund_amount"]); ?></td>
                                </tr>
                                       <tr>
                                    <th class="text-right"><?php
echo $this->lang->line('due_amount') . " (" . $currency_symbol . ")";
        ?></th>
                                    <td class="text-right">
                                        <?php 
                                         echo amountFormat( ($result["net_amount"] + $result["refund_amount"])  -$result['paid_amount']); 
                                   ?></td>
                                </tr>
                        <?php 
if (!$print) {
    ?>
                            <tr id="generated_by">
                                <th class="text-right"><?php echo $this->lang->line('collected_by'); ?></th>
                                <td class="text-right">
                                <?php                                        
                                        if($superadmin_restriction == 'disabled' && $result['staff_roles_id'] == 7){
                                             
                                        }else{
                                           
                                                echo composeStaffNameByString($result['name'], $result['surname'], $result['employee_id']);
                                             
                                        }

                                ?></td>
                            </tr>
                        <?php
}
?>
                    </table>
                    <div class="divider mb-10 mt-10"></div>

                

                </div>
                </div>    
            </div>
            <!--/.col (left) -->
        </div>
    </div>
    </div>
    </td></tr></tbody>
    <tfoot><tr><td>

    <?php
                    if (!empty($print_details[0]['print_footer'])) {
                        ?>
       <div class="footer-space">&nbsp;</div>
  <?php
}
?>



    </td></tr></tfoot>
  </table>
  <?php
                    if (!empty($print_details[0]['print_footer'])) {
                        ?>
  <div class="footer-fixed">
  
  <?php   echo $print_details[0]['print_footer'];?>
                
  </div>
  <?php
}
?>