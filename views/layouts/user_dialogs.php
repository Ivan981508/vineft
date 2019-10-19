<div id="user_dialogs">
	<div class="title_list">
		<input type="text" class="search" placeholder="Поиск по диалогам">
		<input type="button" class="create" data-page="setting_ld">
		<div class="clear"></div>
	</div>

	<?php if($dialog == "none"){ ?>
		<div class="error_column no_dialogs" style="display: block;">
			<div class="img"></div>
			<p>У вас нету диалогов</p>
		</div>
    <?php } ?>
	<div class="scroll_content"><?=dialog::listDialogView($dialog);?></div>
</div>
<script>
/*unread_message = parseInt('<?=$count_unread;?>');
array_dialog = '<?=$array_dialog;?>'.split(",");
my_id = '<?=$my_id;?>';
//load();*/
</script>