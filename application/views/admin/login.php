<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#00a09a">
    <?php
    $titleresult = $this->customlib->getTitleName();
    $logoresult = $this->customlib->getLogoImage();
    $title_name = !empty($titleresult['name']) ? $titleresult['name'] : 'Smart Hospital';
    $hospital_name = !empty($sch_name) ? $sch_name : $title_name;
    $logo_image = !empty($logoresult['image']) ? 'uploads/hospital_content/logo/' . $logoresult['image'] : 'uploads/hospital_content/logo/s_logo.png';
    ?>
    <title><?php echo html_escape($title_name); ?></title>
    <link href="<?php echo base_url() . 'uploads/hospital_content/logo/' . $logoresult['mini_logo']; ?>" rel="shortcut icon" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,500,600,700">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/font-awesome/css/font-awesome.min.css">
    <style>
        * { box-sizing: border-box; }
        html, body { min-height: 100%; }
        body { margin: 0; color: #172b4d; background: linear-gradient(105deg, #edfafa 0%, #ffffff 48%, #f6fbfb 100%); font-family: 'Roboto', Arial, sans-serif; }
        .login-page { min-height: 100vh; padding: 23px 20px 48px; }
        .login-wrap { width: 100%; max-width: 1170px; margin: 0 auto; }
        .login-brand { text-align: center; margin-bottom: 38px; }
        .brand-mark { width: 100px; height: 100px; margin: 0 auto 28px; padding: 14px; display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 50%; background: #009f99; box-shadow: 0 8px 18px rgba(0, 159, 153, .14); }
        .brand-mark img { display: block; max-width: 100%; max-height: 100%; object-fit: contain; }
        .login-brand h1 { margin: 0; color: #102b55; font-size: 38px; line-height: 1.2; font-weight: 700; }
        .login-brand p { margin: 10px 0 0; color: #395470; font-size: 20px; line-height: 1.45; }
        .login-grid { display: flex; align-items: flex-start; gap: 30px; max-width: 1000px; margin: 0 auto; }
        .login-card, .info-card { width: calc(50% - 15px); border-radius: 14px; }
        .login-card { padding: 40px; background: #fff; box-shadow: 0 15px 27px rgba(27, 47, 72, .18); }
        .form-group { margin: 0 0 28px; }
        .form-group label { display: block; margin: 0 0 10px; color: #1c355a; font-size: 17px; font-weight: 500; }
        .input-wrap { position: relative; }
        .input-wrap > i { position: absolute; z-index: 1; top: 50%; left: 18px; transform: translateY(-50%); color: #9aa7b9; font-size: 21px; pointer-events: none; }
        .input-wrap input { width: 100%; height: 62px; padding: 15px 47px; color: #122b4d; background: #fff; border: 1px solid #cbd3df; border-radius: 10px; outline: 0; font-size: 17px; transition: border-color .2s, box-shadow .2s; }
        .input-wrap input:focus { border-color: #009f99; box-shadow: 0 0 0 3px rgba(0, 159, 153, .14); }
        .password-toggle { position: absolute; top: 0; right: 0; width: 54px; height: 62px; border: 0; color: #9aa7b9; background: transparent; font-size: 19px; cursor: pointer; }
        .password-toggle:hover { color: #009f99; }
        .text-danger { display: block; margin-top: 6px; color: #cf3d3d; font-size: 13px; }
        .captcha-row { display: flex; align-items: center; gap: 12px; }
        .captcha-row .captcha-image { min-height: 40px; }
        .captcha-row input { min-width: 0; flex: 1; height: 42px; padding: 8px 12px; border: 1px solid #cbd3df; border-radius: 8px; }
        .captcha-refresh { border: 0; color: #009f99; background: transparent; font-size: 19px; cursor: pointer; }
        .login-actions { display: flex; align-items: center; margin: 0 0 28px; }
        .login-actions input { width: 20px; height: 20px; margin: 0 10px 0 0; accent-color: #009f99; }
        .login-actions label { margin: 0; color: #1c355a; font-size: 16px; font-weight: 400; }
        .sign-in { width: 100%; height: 58px; border: 0; border-radius: 10px; color: #fff; background: #009f99; font-size: 17px; font-weight: 600; cursor: pointer; transition: background .2s, transform .2s; }
        .sign-in:hover, .sign-in:focus { color: #fff; background: #008c87; transform: translateY(-1px); }
        .forgot-link { display: inline-block; margin-top: 21px; color: #007f7b; font-size: 14px; text-decoration: none; }
        .forgot-link:hover { color: #005f5b; text-decoration: underline; }
        .alert { padding: 12px 14px; margin: 0 0 22px; border-radius: 8px; font-size: 14px; }
        .alert-danger { color: #8e2929; background: #fff0f0; border: 1px solid #f6c4c4; }
        .alert-success { color: #187043; background: #ecfff4; border: 1px solid #bce9cd; }
        .info-card { padding: 30px; color: #865000; background: #fffdf0; border: 1px solid #f1d95d; }
        .info-heading { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 20px; }
        .info-heading h2 { margin: 0; color: #814900; font-size: 22px; font-weight: 600; }
        .info-heading h2 i { margin-right: 9px; }
        .secure-tag { padding: 7px 10px; border-radius: 6px; color: #8a5d00; background: #ffea77; font-size: 12px; font-weight: 600; white-space: nowrap; }
        .info-intro { margin: 0 0 20px; color: #995900; font-size: 16px; line-height: 1.48; }
        .notice-list { display: grid; gap: 14px; }
        .notice-item { display: flex; align-items: center; min-height: 105px; padding: 16px; border: 2px solid; border-radius: 10px; }
        .demo-card { width: 100%; color: inherit; cursor: pointer; text-align: left; font-family: inherit; transition: box-shadow .2s, transform .2s; }
        .demo-card:hover, .demo-card:focus { outline: 0; box-shadow: 0 6px 14px rgba(29, 45, 68, .14); transform: translateY(-1px); }
        .notice-item:nth-child(3n + 1) { color: #6e28bf; background: #f0e3ff; border-color: #dfc6ff; }
        .notice-item:nth-child(3n + 2) { color: #2258bf; background: #dceafd; border-color: #aed1ff; }
        .notice-item:nth-child(3n) { color: #087840; background: #d8f8e5; border-color: #a6edc6; }
        .notice-icon { width: 45px; height: 45px; margin-right: 14px; display: flex; align-items: center; justify-content: center; flex: 0 0 auto; border-radius: 9px; background: #fff; font-size: 20px; }
        .notice-copy { min-width: 0; }
        .notice-copy h3 { overflow: hidden; margin: 0 0 5px; font-size: 17px; font-weight: 600; text-overflow: ellipsis; white-space: nowrap; }
        .notice-copy p { display: -webkit-box; overflow: hidden; margin: 0; font-size: 13px; line-height: 1.35; opacity: .82; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
        .copy-button { width: 37px; height: 37px; margin-left: auto; padding: 0; flex: 0 0 auto; border: 0; border-radius: 7px; color: inherit; background: transparent; font-size: 18px; cursor: pointer; }
        .copy-button:hover, .copy-button:focus { background: rgba(255, 255, 255, .72); outline: 0; }
        .copy-status { min-height: 18px; margin: 11px 0 0; color: #875400; font-size: 12px; text-align: center; }
        @media (max-width: 820px) { .login-brand { margin-bottom: 26px; } .login-brand h1 { font-size: 30px; } .login-brand p { font-size: 17px; } .login-grid { max-width: 480px; flex-direction: column; } .login-card, .info-card { width: 100%; } }
        @media (max-width: 480px) { .login-page { padding: 20px 14px 35px; } .brand-mark { width: 82px; height: 82px; margin-bottom: 20px; } .login-brand h1 { font-size: 26px; } .login-card, .info-card { padding: 25px 20px; } .info-heading h2 { font-size: 19px; } }
    </style>
</head>
<body>
    <main class="login-page">
        <div class="login-wrap">
            <header class="login-brand">
                <div class="brand-mark"><img src="<?php echo $this->media_storage->getImageURL($logo_image); ?>" alt="<?php echo html_escape($hospital_name); ?>"></div>
                <h1>Welcome back to <?php echo html_escape($hospital_name); ?></h1>
                <p>Sign in to access your hospital management system</p>
            </header>
            <div class="login-grid">
                <section class="login-card" aria-label="<?php echo html_escape($this->lang->line('admin_login')); ?>">
                    <?php if (isset($error_message)) { echo "<div class='alert alert-danger'>" . $error_message . "</div>"; } ?>
                    <?php if ($this->session->flashdata('message')) { echo "<div class='alert alert-success'>" . $this->session->flashdata('message') . "</div>"; } ?>
                    <form action="<?php echo site_url('site/login'); ?>" method="post">
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div class="form-group"><label for="email"><?php echo $this->lang->line('username'); ?></label><div class="input-wrap"><i class="fa fa-user" aria-hidden="true"></i><input type="text" name="username" placeholder="<?php echo html_escape($this->lang->line('username')); ?>" value="" id="email" autocomplete="username" required></div><span class="text-danger"><?php echo form_error('username'); ?></span></div>
                        <div class="form-group"><label for="password"><?php echo $this->lang->line('password'); ?></label><div class="input-wrap"><i class="fa fa-lock" aria-hidden="true"></i><input type="password" name="password" placeholder="<?php echo html_escape($this->lang->line('password')); ?>" id="password" autocomplete="current-password" required><button class="password-toggle" type="button" aria-label="Show password" aria-pressed="false"><i class="fa fa-eye" aria-hidden="true"></i></button></div><span class="text-danger"><?php echo form_error('password'); ?></span></div>
                        <?php if ($is_captcha) { ?><div class="form-group"><label for="captcha"><?php echo $this->lang->line('enter_captcha'); ?></label><div class="captcha-row"><span class="captcha-image" id="captcha_image"><?php echo $captcha_image; ?></span><button class="captcha-refresh" type="button" title="Refresh Captcha" aria-label="Refresh Captcha" onclick="refreshCaptcha()"><i class="fa fa-refresh"></i></button><input type="text" name="captcha" placeholder="<?php echo html_escape($this->lang->line('enter_captcha')); ?>" id="captcha" required></div><span class="text-danger"><?php echo form_error('captcha'); ?></span></div><?php } ?>
                        <div class="login-actions"><input type="checkbox" id="remember-me" name="remember-me"><label for="remember-me">Remember me</label></div>
                        <button type="submit" class="sign-in"><?php echo $this->lang->line('sign_in'); ?></button>
                    </form>
                    <a href="<?php echo site_url('site/forgotpassword'); ?>" class="forgot-link"><i class="fa fa-key" aria-hidden="true"></i> <?php echo $this->lang->line('forgot_password'); ?>?</a>
                </section>
                <aside class="info-card">
                    <div class="info-heading"><h2><i class="fa fa-user" aria-hidden="true"></i>Demo Credentials</h2><span class="secure-tag">DEMO MODE</span></div>
                    <p class="info-intro">Select a role to fill the login form, or use the copy button to copy its credentials.</p>
                    <?php
                    $demo_credentials = array(
                        array('role' => 'Super Admin', 'email' => 'superadmin@gmail.com', 'password' => 'Admin@123', 'icon' => 'fa-user'),
                        array('role' => 'Admin', 'email' => 'demo.admin@smarthospital.local', 'password' => 'Admin@123', 'icon' => 'fa-user'),
                        array('role' => 'Accountant', 'email' => 'demo.accountant@smarthospital.local', 'password' => 'Admin@123', 'icon' => 'fa-calculator'),
                        array('role' => 'Doctor', 'email' => 'ajay@gmail.com', 'password' => 'Admin@123', 'icon' => 'fa-stethoscope'),
                        array('role' => 'Pharmacist', 'email' => 'demo.pharmacist@smarthospital.local', 'password' => 'Admin@123', 'icon' => 'fa-medkit'),
                        array('role' => 'Pathologist', 'email' => 'demo.pathologist@smarthospital.local', 'password' => 'Admin@123', 'icon' => 'fa-flask'),
                        array('role' => 'Radiologist', 'email' => 'demo.radiologist@smarthospital.local', 'password' => 'Admin@123', 'icon' => 'fa-heartbeat'),
                        array('role' => 'Receptionist', 'email' => 'demo.receptionist@smarthospital.local', 'password' => 'Admin@123', 'icon' => 'fa-phone'),
                        array('role' => 'Nurse', 'email' => 'demo.nurse@smarthospital.local', 'password' => 'Admin@123', 'icon' => 'fa-plus-square'),
                    );
                    ?>
                    <div class="notice-list">
                        <?php foreach ($demo_credentials as $credential) { ?>
                            <article class="notice-item demo-card" tabindex="0" role="button" data-username="<?php echo html_escape($credential['email']); ?>" data-password="<?php echo html_escape($credential['password']); ?>">
                                <div class="notice-icon"><i class="fa <?php echo html_escape($credential['icon']); ?>" aria-hidden="true"></i></div>
                                <div class="notice-copy"><h3><?php echo html_escape($credential['role']); ?></h3><p><?php echo html_escape($credential['email']); ?></p><p>Password: <?php echo html_escape($credential['password']); ?></p></div>
                                <button class="copy-button" type="button" title="Copy credentials" aria-label="Copy <?php echo html_escape($credential['role']); ?> credentials"><i class="fa fa-copy" aria-hidden="true"></i></button>
                            </article>
                        <?php } ?>
                    </div>
                    <p class="copy-status" aria-live="polite"></p>
                </aside>
            </div>
        </div>
    </main>
    <script src="<?php echo base_url(); ?>backend/usertemplate/assets/js/jquery-1.11.1.min.js"></script>
    <script>
        (function () {
            var toggle = document.querySelector('.password-toggle'), password = document.getElementById('password'), username = document.getElementById('email'), status = document.querySelector('.copy-status');
            if (toggle && password) { toggle.addEventListener('click', function () { var showing = password.type === 'text'; password.type = showing ? 'password' : 'text'; this.setAttribute('aria-pressed', String(!showing)); this.setAttribute('aria-label', showing ? 'Show password' : 'Hide password'); this.firstChild.className = showing ? 'fa fa-eye' : 'fa fa-eye-slash'; }); }
            function selectCredentials(card) { username.value = card.getAttribute('data-username'); password.value = card.getAttribute('data-password'); username.focus(); }
            function copyCredentials(card) { var value = 'Username: ' + card.getAttribute('data-username') + '\nPassword: ' + card.getAttribute('data-password'); if (navigator.clipboard && window.isSecureContext) { navigator.clipboard.writeText(value); } else { var input = document.createElement('textarea'); input.value = value; document.body.appendChild(input); input.select(); document.execCommand('copy'); document.body.removeChild(input); } status.textContent = 'Credentials copied to clipboard.'; }
            Array.prototype.forEach.call(document.querySelectorAll('.demo-card'), function (card) { card.addEventListener('click', function (event) { if (!event.target.closest('.copy-button')) { selectCredentials(card); } }); card.addEventListener('keydown', function (event) { if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); selectCredentials(card); } }); card.querySelector('.copy-button').addEventListener('click', function (event) { event.stopPropagation(); copyCredentials(card); }); });
        }());
        function refreshCaptcha() { $.ajax({ type: 'POST', url: '<?php echo base_url('site/refreshCaptcha'); ?>', data: {}, success: function (captcha) { $('#captcha_image').html(captcha); } }); }
    </script>
</body>
</html>
