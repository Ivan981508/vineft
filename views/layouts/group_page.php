<div id="group_page" style="display: block;" data-type-id="<?=$public['type'];?>">
	<div id="dynamic_layout"></div>
	<h1>Vineft</h1>
	<div class="user_img size_120">
		<img src="template/image/public_photo/<?=$public['avatar'];?>">
		<?php if($user['id'] == $public['admin']) {?> <input type="button" id="setting" data-public-id="<?=$public['id'];?>"><?php } ?>
		<?php if(community::isPublic($public['id'])) {?> <input type="button" id="invite"><?php } ?>
	</div>
	<p id="group_name"><?=$public['name'];?></p>
	<p id="type_group"></p>

	<?=$action_button;?>
	<p id="descript_group"></p>
	<div id="public_users" data-hover="<?=$hover;?>">
		<?php if($hover == "true") { ?>
			<div id="all_user_public"><?=$user_view;?></div>
		<?php } ?>
		<div id="group_user" style="<?=$width;?>"><?=$user_view;?></div>
		<p id="count_sub"><span><?=$public['number_user'];?></span> участников</p>
	</div>
</div>