<?php require_once ROOT.'/views/layouts/head.php';
include ROOT.'/config/vk_params.php';?>
<body id="login_page">
    <div id="main_page" data-link="https://oauth.vk.com/authorize?client_id=<?=ID?>&display=page&redirect_uri=<?=URL?>&response_type=code">
        <img src="template/image/logo.png">
        <h1>Vineft<span>network </span></h1>
        <input type="button" id="autoriz" value="Авторизоваться">
        <!--<input type="button" id="registr" value="Зарегестрироваться через ВК">-->
    </div>
</body>
</html>