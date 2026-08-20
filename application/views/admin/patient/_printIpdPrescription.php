<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>
    <div class="fixed-print-header"> 
                <?php  if (!empty($print_details['print_header'])) { ?>
                    <img src="<?php
                    if (!empty($print_details['print_header'])) {
                        echo $this->media_storage->getImageURL($print_details['print_header']);
                    }
                    ?>" style="height:100px; width:100%;" class="img-responsive">
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
<div class="print-area p-1">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div>
                    <?php
                    $date = $result->presdate;
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?php echo $this->lang->line('prescription'); ?>: <?php echo $this->customlib->getSessionPrefixByType('ipd_prescription').$result->prescription_id; ?>
                        </div>
                       <div class="col-md-6 text-right">
                            <?php echo $this->lang->line('date'); ?> : <?php
                                if (!empty($result->presdate)) {
                                    echo $this->customlib->YYYYMMDDTodateFormat($date);
                                }
                                ?>
                        </div>
                    </div>
                    <div class="divider" style="margin-top: 5px;"></div>
                    <table class="noborder_table mb-0">
                        <tr>
                            <th style="padding-left: 0;"><?php echo $this->lang->line("patient_name"); ?></th>
                            <td><?php echo composePatientName($result->patient_name,$result->id); ?></td>
                            <th><?php echo $this->lang->line("age"); ?></th>
                            <td><?php
                                echo $this->customlib->get_patient_current_age($result->id);
                                ?></td>
                        </tr>
                        <tr>                            
                            <th style="padding-left: 0;"><?php echo $this->lang->line("gender"); ?></th>
                            <td><?php echo $this->lang->line(strtolower($result->gender)) ?></td>
                            <th><?php echo $this->lang->line("blood_group"); ?></th>
                            <td><?php echo $result->blood_group_name ?></td>                            
                        </tr>
                        <tr>                           
                            <th style="padding-left: 0;"><?php echo $this->lang->line("phone"); ?></th>
                            <td><?php echo $result->mobileno ?></td>
                            <th><?php echo $this->lang->line("prescribe_by"); ?></th>
                            <td><?php echo composeStaffNameByString($result->priscribe_by_name,$result->priscribe_by_surname,$result->priscribe_by_employee_id); ?></td>
                        </tr>                      
                        <tr>
                            <th style="padding-left: 0;"><?php echo $this->lang->line("email"); ?></th>
                            <td><?php echo $result->email ?></td>
                            <th><?php echo $this->lang->line('consultant_doctor'); ?></th>
                            <td><?php echo composeStaffNameByString($result->name,$result->surname,$result->employee_id); ?></td>
                        </tr>
                        <tr>
                            <th style="padding-left: 0;"><?php echo $this->lang->line("generated_by"); ?></th>
                            <td><?php echo composeStaffNameByString($result->staff_name,$result->staff_surname,$result->staff_employee_id); ?></td>
                            </tr>
                        <?php if (!empty($result->diagnosis)) { ?>
                        <tr>
                            <th style="padding-left: 0;"><?php echo $this->lang->line('diagnosis'); ?></th>
                            <td colspan="3"><?php echo html_escape($result->diagnosis); ?></td>
                        </tr>
                        <?php } ?>
                         <?php 
                        if (!empty($fields_prescription)) {
                            $display_field = '';
                            foreach ($fields_prescription as $fields_key => $fields_value) {
                            ?>
                                <tr>
                                    <th style="padding-left: 0;"><?php echo $fields_value->name; ?></th>
                                    <td colspan="3"><?php echo $result->{"$fields_value->name"};?></td>
                                </tr>
                            <?php
                            }
                        }
                        ?>
                    </table>
                    <div class="divider mb-10 mt-10"></div>
                    <?php if($result->is_finding_print=='yes'){ $colspan = 6 ; $width = '50%'; }else{ $colspan = 12; $width = '100%';
                    } ?>
                    <table class=""  style="border-color: none;" cellpadding="4"  cellspacing="5" width="100%">                        
                        <tr style="vertical-align: top;!important;">
						<?php if($result->symptoms !=''){ ?>
                            <td colspan="<?php echo $colspan; ?>" width="<?php echo $width; ?>"><b><?php echo $this->lang->line("symptoms"); ?></b><br><?php echo nl2br($result->symptoms)  ?></td>
						<?php } ?>
						
                        <?php if($result->is_finding_print=='yes' && trim($result->finding_description) != ''){ ?> <td>                      
                           <b><?php echo $this->lang->line("finding"); ?></b><br>
                            <?php echo nl2br($result->finding_description)  ?></td>
                        <?php }  ?>
                        </tr>
                    </table>       
                    
                    <?php if($result->symptoms != '' || trim($result->finding_description) != '' || $result->is_finding_print == 'yes'){ ?>
                        <div class="divider mb-10 mt-10"></div>
					<?php } ?>
                    

                    <?php if($result->header_note!=""  && isset($result->header_note) && $result->header_note!=null){ ?>
                    <table width="100%">
                        <tr>
                            <td style="margin-bottom: 0;"><?php echo $result->header_note ?></td>
                        </tr>
                    </table>
                    <div class="divider mb-10 mt-10"></div>
                    <?php } ?>
                    <?php $this->load->view('admin/patient/_print_optical_prescription', array('optical_prescription' => isset($optical_prescription) ? $optical_prescription : array())); ?>


                    <?php if($result->medicines){ ?>
                     <h6><b><?php echo $this->lang->line("medicines"); ?></b></h6>
                    <table class="table table-striped table-hover"  cellpadding="4"  cellspacing="5" width="100%">                     
                            <tr>
                                <th width="2%" class="text text-left">#</th>
                                <th width="16%" class="text text-left"><?php echo $this->lang->line("medicine_category"); ?></th>
                                <th width="14%" class="text text-left"><?php echo $this->lang->line("medicine"); ?></th> 
                                <th width="14%" class="text text-left"><?php echo $this->lang->line("dosage"); ?></th>
                                <th width="14%" class="text text-left"><?php echo $this->lang->line("dose_interval"); ?></th>
                                <th width="14%" class="text text-left"><?php echo $this->lang->line("dose_duration"); ?></th> 
                                <th width="22%" class="text text-left"><?php echo $this->lang->line("instruction"); ?></th> 
                            </tr>
                        <?php $medsl =''; foreach ($result->medicines as $pkey => $pvalue) { $medsl++;
                              ?>
                            <tr>
                                <td class="text text-left"><?php echo $medsl; ?></td>
                                <td class="text text-left"><?php echo $pvalue->medicine_category; ?></td>
                                <td class="text text-left"><?php echo $pvalue->medicine_name; ?></td>
                                <td class="text text-left"><?php echo $pvalue->dosage." ".$pvalue->unit; ?></td>
                                <td class="text text-left"><?php echo $pvalue->dose_interval_name; ?></td>
                                <td class="text text-left"><?php echo $pvalue->dose_duration_name; ?></td>
                                <td class="text text-left"><?php echo $pvalue->instruction; ?></td>
                            </tr>  
                        <?php } ?>
                    </table>
                      <div class="divider mb-10 mt-10"></div>
                    <?php } ?>


                    <?php if(!empty($result->tests)){ 
                        $r=$p=0;
                        foreach ($result->tests as $test_key => $test_value) {
                            if($test_value->test_name != ""){
                                $p=1;
                            }
                        }
                        foreach ($result->tests as $test_key => $test_value) {
                            if($test_value->radio_test_name != ""){
                                $r=1;
                            }
                        }
                        ?>    
                    <table   cellpadding="4"  cellspacing="5" width="100%">                        
                        <tr>
                           <?php 
                            if($p==1){  ?>
                                <th  width="50%" style="padding: 2px;"><b><?php echo $this->lang->line("pathology_test");  ?></b></th>
                                <?php  }   ?>
                            <?php  if($r==1){  ?>
                                <th  width="50%" style="padding: 2px;"><b><?php echo $this->lang->line("radiology_test"); ?></b></th>
                                <?php   }  ?>
                        </tr>
                        <tr> 
						<?php 
                            if($p==1){  ?>
                            <td width="50%"><?php $sl=''; foreach ($result->tests as $test_key => $test_value) {  ?>
                                <table >   
                                    <?php if($test_value->test_name != ""){ $sl++;?>
                                    <tr>
                                        <td  style="padding: 2px;"><?php echo $sl.'. '.$test_value->test_name." (".$test_value->short_name.")"; ?></td>  
                                    </tr>        
                                    <?php } ?>                             
                                </table>    
                                <?php } ?>
                            </td> 
							  <?php  }   ?>
							   <?php  if($r==1){  ?>
                            <td  width="50%"><?php $slradiology=''; foreach ($result->tests as $test_key => $test_value) {  ?>
                                <table>   
                                    <?php if($test_value->test_name == ""){ $slradiology++;?>
                                    <tr>
                                        <td  style="padding: 2px;"><?php echo $slradiology.'. '.$test_value->radio_test_name." (".$test_value->radio_short_name.")"; ?></td>
                                    </tr>
                                    <?php } ?>                             
                                </table>   
                                <?php } ?>
                            </td>
							 <?php   }  ?>
                        </tr>
                    </table>
                    <div class="divider mb-10 mt-10"></div>
                    <?php } ?> 

                    
                    <?php if($result->footer_note!=""  && isset($result->footer_note) && $result->footer_note!=null){ ?>         
                    <table width="100%">
                        <tr>
                            <td><?php echo $result->footer_note; ?></td>
                        </tr>
                    </table>
                    <div class="divider mb-10 mt-10"></div>
                    <?php } ?>
                  
                </div>
            </div>
        </div>
        </div>
    <tfoot><tr><td>
<?php
    if (!empty($print_details['print_footer'])) { ?>
   <div class="footer-space">&nbsp;</div>
<?php
}
?>
</td>
</tr>
</tfoot>
</table>

<?php
if (!empty($print_details['print_footer'])) { ?>
<div class="footer-fixed">
<?php  echo $print_details['print_footer'];?>
</div>
<?php
}
?>
