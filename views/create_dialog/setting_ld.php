<div id="setting_ld">
    <p>Пригласите студентов</p>
    <div class="list_contact">
        <?php if($contact == "none"){ ?>
        <div class="error_column no_contact" style="display: block;">
            <div class="img"></div>
            <p>Список контактов пуст</p>
        </div>
        <?php } else {?>
        <div class="ac_scroll"><?=functions::viewContactDialog($contact,"radio");?></div>
        <?php } ?>
    </div>
</div>