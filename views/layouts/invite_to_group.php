<input type="button" class="close">
<div id="invite_to_group">
	<p>Пригласите знакомых</p>
    <?php if($contact == "none") { ?>
        <div class="error_column no_contact" style="display: block;">
            <div class="img"></div>
            <p>Список контактов пуст</p>
        </div>
    <?php } ?>
	<div class="list_contact"><?=functions::viewInviteGroup($contact,$public_id);?></div>
	<button class="button_2" id="invite_group_contact" data-value="Пригласить в группу" <?=$button;?>>
        <div class="button_loading">
            <div class="bl">
                <div id="circleG_1" class="circleG"></div>
                <div id="circleG_2" class="circleG"></div>
                <div id="circleG_3" class="circleG"></div>
            </div>
        </div>
        <p>Пригласить в группу</p>
    </button>
</div>