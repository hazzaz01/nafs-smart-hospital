<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>

<style type="text/css">
    /*REQUIRED*/
    .carousel-row {
        margin-bottom: 10px;
    }
    .slide-row {
        padding: 0;
        background-color: #ffffff;
        min-height: 150px;
        border: 1px solid #e7e7e7;
        overflow: hidden;
        height: auto;
        position: relative;
    }
    .slide-carousel {
        width: 20%;
        float: left;
        display: inline-block;
    }
    .slide-carousel .carousel-indicators {
        margin-bottom: 0;
        bottom: 0;
        background: rgba(0, 0, 0, .5);
    }
    .slide-carousel .carousel-indicators li {
        border-radius: 0;
        width: 20px;
        height: 6px;
    }
    .slide-carousel .carousel-indicators .active {
        margin: 1px;
    }
    .slide-content {
        position: absolute;
        top: 0;
        left: 20%;
        display: block;
        float: left;
        width: 80%;
        max-height: 76%;
        padding: 1.5% 2% 2% 2%;
        overflow-y: auto;
    }
    .slide-content h4 {
        margin-bottom: 3px;
        margin-top: 0;
    }
    .slide-footer {
        position: absolute;
        bottom: 0;
        left: 20%;
        width: 78%;
        height: 20%;
        margin: 1%;
    }
    /* Scrollbars */
    .slide-content::-webkit-scrollbar {
        width: 5px;
    }
    .slide-content::-webkit-scrollbar-thumb:vertical {
        margin: 5px;
        background-color: #999;
        -webkit-border-radius: 5px;
    }
    .slide-content::-webkit-scrollbar-button:start:decrement,
    .slide-content::-webkit-scrollbar-button:end:increment {
        height: 5px;
        display: block;
    }
    .printablea4{width: 100%;}
    .printablea4>tbody>tr>th,
    .printablea4>tbody>tr>td{padding:5px 0; line-height: 1.42857143;vertical-align: top; font-size: 12px;}
</style>

<div class="content-wrapper" style="min-height: 946px;">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                 <?php $this->load->view('admin/report/_pharmacy');?>
                 <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('stock_report'); ?></h3>
                    </div>
                    <form id="form1" action="" method="post">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                             <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('medicine_category'); ?></label>
                                     <select class="form-control select2" name='medicine_category'  >
                                            <option value="<?php echo set_value('medicine_category_id'); ?>"><?php echo $this->lang->line('select'); ?>
                                            </option>
                                            <?php foreach ($medicineCategory as $dkey => $dvalue) {
                                                ?>
                                                <option value="<?php echo $dvalue["id"]; ?>"><?php echo $dvalue["medicine_category"] ?>
                                                </option>   
                                        <?php } ?>
                                        </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('stock_type'); ?></label>
                                     <select name="stock_type" class="form-control select2 supplier_select2"  id="" name='' >
                                        <option value=""><?php echo $this->lang->line('all'); ?></option>
                                        <?php foreach ($supplierCategory as $dkey => $dvalue) {
                                            ?>
                                            <option value="<?php echo $dkey; ?>" ><?php echo $dvalue;  ?></option>   
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="box border0 clear">
                        <div class="box-body">
                            <div class="box-header ptbnull mb10"></div>
                             <div class="table-responsive-mobile">   
                            <table class="table table-striped table-bordered table-hover ajaxlist " cellspacing="0" width="100%" data-export-title="<?php echo $this->lang->line('medicines_stock'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('medicine_name'); ?></th>
                                        <th><?php echo $this->lang->line('medicine_company'); ?></th>
                                        <th><?php echo $this->lang->line('medicine_composition'); ?></th>
                                        <th><?php echo $this->lang->line('medicine_category'); ?></th> 
                                        <th><?php echo $this->lang->line('medicine_group'); ?></th>
                                        <th><?php echo $this->lang->line('unit'); ?></th>
                                        <th class=""><?php echo $this->lang->line('available_qty'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>                            
                          </div>
                        </div>  
                    </div>

                </div>
            </div>
        </div>
</section>
</div>


<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2();
    });
</script>
<script type="text/javascript">
   
    function showdate(value) {
        if (value == 'period') {
            $('#fromdate').show();
            $('#todate').show();
        } else {
            $('#fromdate').hide();
            $('#todate').hide();
        }
    }
</script>
<script>
    $(document).ready(function (e) {
      
        emptyDatatable('allajaxlist', 'data');
    });
    
( function ( $ ) {
    'use strict';
    $(document).ready(function () {
       $('#form1').on('submit', (function (e) {
        e.preventDefault();
        var search= 'search_filter';
        var formData = new FormData(this);
        formData.append('search', 'search_filter');
        $.ajax({
            url: '<?php echo base_url(); ?>admin/report/stockcheckvalidation',
            type: "POST",
            data: formData,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                if (data.status == "fail") {
                   $.each(data.error, function(key, value) {
                        $('#error_' + key).html(value);
                    });
                } else {
                    $("#error_search_type").html('');
                    $("#error_collect_staff").html('');
                   initDatatable('ajaxlist','admin/report/getpharmacystock',data.param,[],100,[
                        { 'bSortable': false, 'aTargets': [ 1 ] }
                    ]);
                }
            }
        });
        }
       ));
   });

} ( jQuery ) );

</script>
<!-- //========datatable start===== -->
<script type="text/javascript">
( function ( $ ) {
    'use strict';
    $(document).ready(function () {
        initDatatable('ajaxlist','admin/report/getpharmacystock',[],[],100,[
          { 'bSortable': false, 'aTargets': [ 1 ] }
       ]);
    });
} ( jQuery ) )
</script>
<!-- //========datatable end===== -->