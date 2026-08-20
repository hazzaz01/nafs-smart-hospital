<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $this->customlib->getAppName(); ?></title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="theme-color" content="#5190fd" />
        <?php 
            $logoresult = $this->customlib->getLogoImage();
            if (!empty($logoresult['mini_logo'])) {
                $mini_logo = base_url() . 'uploads/hospital_content/logo/' . $logoresult['mini_logo']; 
            }else{
                $mini_logo = base_url() . 'backend/images/s-favican.png';
            }
         ?>
        <link href="<?php echo $mini_logo; ?>" rel="shortcut icon" type="image/x-icon">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/style-main.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/jquery.mCustomScrollbar.min.css">
        <?php
$this->load->view('layout/theme');
?>
        <?php
if ($this->customlib->getRTL() == "yes") {
    ?>
        <!-- Bootstrap 3.3.5 RTL -->
        
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/bootstrap-rtl/css/bootstrap-rtl.min.css"/>
        
        <!-- Theme RTL style -->
        
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/AdminLTE-rtl.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/ss-rtlmain.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/skins/_all-skins-rtl.min.css" />        
       
        <?php } ?>        
        
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/all.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/ionicons.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/iCheck/flat/blue.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/morris/morris.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/datepicker/datepicker3.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/colorpicker/bootstrap-colorpicker.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/daterangepicker/daterangepicker-bs3.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/custom_style.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/datepicker/css/bootstrap-datetimepicker.css">
        <!--file dropify-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/dropify.min.css">
        <!--file nprogress-->
        <link href="<?php echo base_url(); ?>backend/dist/css/nprogress.css" rel="stylesheet">
        <!--print table-->
        <link href="<?php echo base_url(); ?>backend/dist/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>backend/dist/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>backend/dist/datatables/css/dataTables.bootstrap.min.css" rel="stylesheet">
        <!--print table mobile support-->
        <link href="<?php echo base_url(); ?>backend/dist/datatables/css/responsive.dataTables.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>backend/dist/datatables/css/rowReorder.dataTables.min.css" rel="stylesheet">        
        <script src="<?php echo base_url(); ?>backend/custom/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/plugins/colorpicker/bootstrap-colorpicker.js"></script>
        <script src="<?php echo base_url(); ?>backend/datepicker/date.js"></script>
        <script src="<?php echo base_url(); ?>backend/dist/js/jquery-ui.min.js"></script>		
		 
        <script src="<?php echo base_url(); ?>backend/js/hospital-custom.js"></script>
		 
		
        <!-- fullCalendar -->
        <link rel="stylesheet" href="<?php echo base_url() ?>backend/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>backend/fullcalendar/dist/fullcalendar.print.min.css" media="print">
        <link rel="stylesheet" href="<?php echo base_url() ?>backend/plugins/select2/select2.min.css">        
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/0.8.2/css/flag-icon.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>/backend/dist/css/bootstrap-select.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/clinical-dashboard.css">
        <style>
            /* Compact dashboard header treatment. Existing controls and routes remain unchanged. */
            @media (min-width: 768px) {
                .clinical-shell .main-header { height: 64px; box-shadow: 0 1px 4px rgba(20, 44, 72, .12); }
                .clinical-shell .main-header > .logo { display: flex; align-items: center; width: 270px; height: 64px; padding: 0 16px; color: #102b4d !important; background: #fff !important; border-right: 1px solid #e1e8ef; }
                .clinical-shell .main-header > .logo:hover { background: #fbfdfd !important; }
                .clinical-shell .main-header > .logo .logo-lg { display: flex; align-items: center; min-width: 0; width: 100%; line-height: normal; text-align: left; }
                .clinical-shell .main-header > .logo .logo-lg img { width: 38px; max-height: 42px; margin-right: 11px; object-fit: contain; }
                .clinical-shell .main-header > .logo .logo-lg strong { overflow: hidden; color: #102b4d; font-size: 16px; font-weight: 700; text-overflow: ellipsis; white-space: nowrap; }
                .clinical-shell .main-header > .navbar { min-height: 64px; margin-left: 270px; background: #fff; border-bottom: 1px solid #e1e8ef; }
                .clinical-shell.fixed .content-wrapper, .clinical-shell.fixed .right-side { padding-top: 64px; }
                .clinical-shell .main-sidebar, .clinical-shell .left-side { padding-top: 64px; }
                .clinical-shell .main-header .sidebar-toggle { position: relative; min-height: 0; width: 42px; height: 42px; margin: 11px 14px 11px 20px; padding: 0; border-radius: 50%; color: #fff !important; background: #08aaa4; box-shadow: 0 2px 6px rgba(0, 159, 153, .25); line-height: 42px; text-align: center; }
                .clinical-shell .main-header .sidebar-toggle:hover, .clinical-shell .main-header .sidebar-toggle:focus { color: #fff; background: #008f8a; }
                .clinical-shell .main-header > .navbar > .col-lg-4 { width: auto; height: 64px; padding: 0; }
                .clinical-shell .main-header .sidebar-session { display: flex; align-items: center; height: 64px; margin: 0; color: #102b4d; font-size: 19px; }
                .clinical-shell .main-header .sidebar-session i { width: 33px; height: 33px; margin-right: 12px; border-radius: 5px; color: #fff; background: #08aaa4; font-size: 15px; line-height: 33px; text-align: center; box-shadow: 0 2px 5px rgba(0, 159, 153, .2); }
                .clinical-shell .main-header > .navbar > .col-lg-8 { width: auto; height: 64px; padding: 0 20px 0 12px; overflow: visible; }
                .clinical-shell .main-header > .navbar > .col-lg-8 > .pull-right { display: flex; align-items: center; float: none !important; height: 64px; }
                .clinical-shell .main-header .navbar-form.search-form { width: 326px; margin: 0 15px 0 auto; border: 0; }
                .clinical-shell .main-header .navbar-form.search-form .input-group { width: 100%; padding-top: 0 !important; }
                .clinical-shell .main-header input.form-control.search-form.search-form3 { height: 50px; border: 1px solid #d5e0e8 !important; border-right: 0 !important; border-radius: 4px 0 0 4px !important; color: #42607c; background: #fafcfd; box-shadow: inset 0 1px 2px rgba(32, 57, 83, .05); font-size: 16px; }
                .clinical-shell .main-header #search-btn { height: 50px; padding: 0 17px !important; border: 1px solid #d5e0e8 !important; border-left: 0 !important; border-radius: 0 4px 4px 0 !important; color: #08aaa4; background: #fafcfd !important; font-size: 16px; }
                .clinical-shell .main-header .navbar-custom-menu { display: flex; align-items: center; float: none; }
                .clinical-shell .main-header .langdiv { margin-right: 10px; }
                .clinical-shell .main-header .headertopmenu { display: flex; align-items: center; height: 50px; margin: 0; border: 1px solid #d5e0e8; border-radius: 4px; }
                .clinical-shell .main-header .headertopmenu > li { height: 48px; }
                .clinical-shell .main-header .headertopmenu > li > a { position: relative; display: flex; align-items: center; justify-content: center; min-width: 48px; height: 48px; padding: 0 12px !important; color: #60748d !important; background: transparent !important; font-size: 17px; }
                .clinical-shell .main-header .headertopmenu > li > a:hover, .clinical-shell .main-header .headertopmenu > li.open > a { color: #008f8a !important; background: #f1fbfa !important; }
                .clinical-shell .main-header .headertopmenu > li > a .label, .clinical-shell .main-header .todo-indicator { top: 3px; right: 4px; border-radius: 12px; color: #fff; background: #c52442 !important; }
                .clinical-shell .main-header .user-menu { display: flex; align-items: center; height: 64px; margin-left: 14px; padding-left: 14px; border-left: 1px solid #dce5ec; }
                .clinical-shell .main-header .user-menu > a { display: flex; align-items: center; height: 52px; padding: 0 !important; color: #102b4d !important; background: transparent !important; }
                .clinical-shell .main-header .user-menu .topuser-image { width: 40px; height: 40px; margin: 0 11px 0 0; border: 2px solid #08aaa4; border-radius: 50%; object-fit: cover; }
                .clinical-shell .header-user-label { display: flex; flex-direction: column; align-items: flex-start; line-height: 1.15; }
                .clinical-shell .header-user-label strong { color: #1c2f49; font-size: 15px; font-weight: 700; }
                .clinical-shell .header-user-label small { margin-top: 4px; color: #718198; font-size: 11px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase; }
                .clinical-shell .user-menu > a:after { margin-left: 12px; color: #718198; font-family: FontAwesome; content: '\\f107'; }
            }
            @media (max-width: 1100px) { .clinical-shell .header-user-label { display: none; } .clinical-shell .main-header .navbar-form.search-form { width: 250px; } }
        </style>
    </head>
    <script type="text/javascript">
        var baseurl = "<?php echo base_url(); ?>";
        var chk_validate = "<?php echo $this->config->item('SHLK') ?>";
    </script>
    <body class="hold-transition skin-blue fixed sidebar-mini clinical-shell">
        <?php
if ($this->config->item('SHLK') == "") {
    ?>
            <div class="topaleart">
                <div class="slidealert">
                    <div class="alert alert-dismissible topaleart-inside">
                        <p class="palert">Alert! You are using unregistered version of Smart Hospital. Please <a  href="#" class="purchasemodal">click here</a> to register your purchase code for Smart Hospital.</p>
                    </div>
                </div>
            </div>
            <?php
}
?>
        <script type="text/javascript">
          
            function collapseSidebar() {
                if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
                    sessionStorage.setItem('sidebar-toggle-collapsed', '');
                } else {
                    sessionStorage.setItem('sidebar-toggle-collapsed', '1');
                }
            }

            function checksidebar() {
                if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
                    var body = document.getElementsByTagName('body')[0];
                    body.className = body.className + ' sidebar-collapse';
                }
            }
            checksidebar();

            function capitalizeFirstLetter(string) {
                  return string.charAt(0).toUpperCase() + string.slice(1);
            }
            
        </script>
        <?php
$logoresult = $this->customlib->getLogoImage();
if (!empty($logoresult["image"])) {
    $logo_image =  "uploads/hospital_content/logo/" . $logoresult["image"];
} else {
    $logo_image =  "uploads/hospital_content/logo/s_logo.png";
}
if (!empty($logoresult["mini_logo"])) {
    $mini_logo =  "uploads/hospital_content/logo/" . $logoresult["mini_logo"];
} else {
    $mini_logo =  "uploads/hospital_content/logo/smalllogo.png";
}
?>
        <div class="wrapper">
            <header class="main-header" id="alert">
                <a href="<?php echo base_url(); ?>admin/admin/dashboard" class="logo">
                    <span class="logo-mini"><img width="31" height="19" src="<?php echo $this->media_storage->getImageURL($mini_logo); ?>" alt="<?php echo $this->customlib->getAppName() ?>" /></span>
                    <span class="logo-lg"><img src="<?php echo $this->media_storage->getImageURL($logo_image); ?>" alt="<?php echo html_escape($this->customlib->getAppName()); ?>"><strong><?php echo html_escape($this->customlib->getAppName()); ?></strong></span>
                </a>
                <nav class="navbar navbar-static-top" role="navigation">
                    <a href="#"  onclick="collapseSidebar()"  class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3">
                        <span class="sidebar-session">
                            <i class="fa fa-home"></i>
                            <strong><?php echo isset($title) ? html_escape($title) : $this->setting_model->getCurrentHospitalName(); ?></strong>
                        </span>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-9 col-xs-9">
                        <div class="pull-right">
                            <?php if (($this->rbac->hasPrivilege('patient', 'can_view'))) {?>
                                <form class="navbar-form navbar-left search-form" role="search"  action="<?php echo site_url('admin/admin/search'); ?>" method="POST">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <div class="input-group" style="padding-top:3px;">
                                        <input type="text" name="search_text" class="form-control search-form search-form3" placeholder="<?php echo $this->lang->line('search_by_name'); ?>">
                                        <span class="input-group-btn">
                                            <button type="submit" name="search" id="search-btn" style="padding: 3px 12px !important;border-radius: 0px 30px 30px 0px; background: #fff;" class="btn btn-flat"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                </form>
                            <?php }?>
                            <div class="navbar-custom-menu">
                                 <?php if ($this->rbac->hasPrivilege('language_switcher', 'can_view')) {
    ?>
                                    <div class="langdiv" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('language') ?>"><select class="languageselectpicker" onchange="set_languages(this.value)"  type="text" id="languageSwitcher" >

                                           <?php $this->load->view('admin/language/languageSwitcher')?>

                                        </select></div>
                                    <?php
}?> 
                                <ul class="nav navbar-nav headertopmenu">
								
									<?php 
									      $userdata = $this->customlib->getUserData();
                                        
                                         
                                    if($userdata["role_id"] ==7){  
										if (($this->module_lib->hasModule('multi_branch') && $this->module_lib->hasActive('multi_branch')) || $this->db->multi_branch) {  ?>
                                    
                                            <li class="cal15" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('switch_branch'); ?>"><a href="#" data-toggle="modal" data-target="#multiBranchSwitchModal"><i class="fa fa-exchange" aria-hidden="true"></i></a></li>
                                    
									<?php } 
                                    }
                                    ?>
									
                                         <?php 
                                    if ($this->rbac->hasPrivilege('notification_center', 'can_view')) {
                                            $systemnotifications = $this->notification_model->getCountUnreadNotification();

                                             ?>
                                            <li class="cal15">
                                                
                                                <a data-placement="bottom" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('notifications'); ?>" href="<?php echo base_url() . "admin/systemnotification" ?>">
                                                    <i class="fa fa-bell-o"></i>
                                                    <?php echo ($systemnotifications->count > 0) ? "<span class='label label-warning'>".$systemnotifications->count."</span>" : "";  ?>
                                                </a>
                                            </li>
                                    <?php 
                                }
                                ?>
                                    
                                    <?php if ($this->rbac->hasPrivilege('bed_status', 'can_view')) {?>
                                        <li class="">
                                            <a data-target="modal" data-placement="bottom" data-toggle="tooltip" href="#" id='beddata' data-original-title="<?php echo $this->lang->line('bed_status'); ?>" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('loading'); ?>" onclick="getbedstatus()">
                                                <i class="fas fa-bed cal15"></i>                                                 
                                            </a>
                                    </li>
                                    <?php } if ($this->module_lib->hasActive('chat') && $this->rbac->hasPrivilege('chat', 'can_view')) { ?>
                                     <li class="cal15">
                                        <a data-placement="bottom" data-toggle="tooltip" title="" href="<?php echo site_url('admin/chat')?>" data-original-title="<?php echo $this->lang->line('chat'); ?>" class="todoicon"><i class="fa fa-whatsapp"></i>  <?php  echo chat_couter() > 0 ? "<span class='label label-warning'>".chat_couter()."</span>": "" ?></a>
                                    </li> 
                                    <?php
                                }

if ($this->module_lib->hasActive('calendar_to_do_list')) {
    if ($this->rbac->hasPrivilege('calendar_to_do_list', 'can_view')) {
        ?>
                                            <li class="cal15"><a href="<?php echo base_url() ?>admin/calendar/events" data-placement="bottom" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('calendar') ?>"><i class="fa fa fa-calendar"></i></a></li>
                                            <?php
}
}
?>
                                    <?php
if ($this->module_lib->hasActive('calendar_to_do_list')) {
    if ($this->rbac->hasPrivilege('calendar_to_do_list', 'can_view')) {
        ?>
                                            <li class="dropdown">
                                                <a href="#"  title="<?php echo $this->lang->line('task') ?>" class="dropdown-toggle todoicon" data-toggle="dropdown">
                                                    <i class="fa fa-check-square-o"></i>
                                                    <?php
$userdata = $this->customlib->getUserData();

        $count = $this->customlib->countincompleteTask($userdata["id"]);
        if ($count > 0) {
            ?>

                                                        <span class="todo-indicator"><?php echo $count ?></span>
                                                    <?php }?>
                                                </a>
                                                <ul class="dropdown-menu menuboxshadow widthMo250">

                                                    <li class="todoview plr10 ssnoti"><?php echo $this->lang->line('today_you_have'); ?> <?php echo $count; ?> <?php echo $this->lang->line('pending_task'); ?><a href="<?php echo base_url() ?>admin/calendar/events" class="pull-right pt0"><?php echo $this->lang->line('view_all'); ?></a></li>
                                                    <li>
                                                        <ul class="todolist">
                                                            <?php
$tasklist = $this->customlib->getincompleteTask($userdata["id"]);
        foreach ($tasklist as $key => $value) {
            ?>
                                                                <li><div class="checkbox">
                                                                        <label><input type="checkbox" id="newcheck<?php echo $value["id"] ?>" onclick="markc('<?php echo $value["id"] ?>')" name="eventcheck"  value="<?php echo $value["id"]; ?>"><?php echo $value["event_title"] ?></label>
                                                                    </div></li>
                                                            <?php }?>

                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                            <?php
}
}
?>

                                    <?php
$file   = "";
$result = $this->customlib->getUserData();

$image = $result["image"];
$role  = $result["user_type"];
$id    = $result["id"];
if (!empty($image)) {
    $file = "uploads/staff_images/" . $image;
} else {
    $file = "uploads/staff_images/no_image.png";
}
?>
                                    <li class="dropdown user-menu">
                                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                                            <img src="<?php echo $this->media_storage->getImageURL($file); ?>" class="topuser-image" alt="User Image">
                                            <span class="header-user-label"><strong><?php echo html_escape($this->customlib->getAdminSessionUserName()); ?></strong><small><?php echo html_escape($role); ?></small></span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-user menuboxshadow">
                                            <li>
                                                <div class="sstopuser">
                                                    <div class="ssuserleft">
                                                        <a href="<?php echo base_url() . "admin/staff/profile/" . $id ?>"><img src="<?php echo $this->media_storage->getImageURL($file); ?>" alt="User Image"></a>
                                                    </div>
                                                    <div class="sstopuser-test">
                                                        <h4 style="text-transform: capitalize;"><?php echo $this->customlib->getAdminSessionUserName(); ?></h4>
                                                        <h5><?php echo $role; ?></h5>
                                                    </div>
                                                    <div class="divider"></div>
                                                    <div class="sspass">
                                                        <a href="<?php echo base_url() . "admin/staff/profile/" . $id ?>" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('my_profile'); ?>"><i class="fa fa-user"></i><?php echo $this->lang->line('profile'); ?></a>
                                                        <a class="pl25" href="<?php echo base_url(); ?>admin/admin/changepass" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('change_password') ?>"><i class="fa fa-key"></i><?php echo $this->lang->line('password'); ?></a> <a class="pull-right" href="<?php echo base_url(); ?>site/logout"><i class="fa fa-sign-out fa-fw"></i><?php echo $this->lang->line('logout'); ?></a>
                                                    </div>
                                                </div><!--./sstopuser-->
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
			
<script>
    function defoult(id){
      var defoult=  $('#languageSwitcher').val();
        $.ajax({
            type: "POST",
            url: base_url + "admin/language/defoult_language/"+id,
            data: {},
            success: function (data) {
                successMsg("<?php echo $this->lang->line('status_change_successfully'); ?>");
				$('#languageSwitcher').html(data);
            }
        });

        window.location.reload('true');        
    }
 
    function set_languages(lang_id){
        $.ajax({
            type: "POST",
            url: base_url + "admin/language/user_language/"+lang_id,
            data: {},
            success: function (data) {
                successMsg("<?php echo $this->lang->line('status_change_successfully'); ?>");
                 window.location.reload('true');
            }
        });
    }
</script>
            <?php $this->load->view('layout/sidebar');?>
