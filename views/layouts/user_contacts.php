<div id="user_list">
    <div class="title_list">
        <input type="checkbox" id="view_params">
        <label for="view_params"></label>
        
        <input type="text" class="search" placeholder="Поиск по студентам">
        <div id="params_search">
            <strong>Расширенный поиск</strong>
            <select id="select_spec" value="none">
                <option selected  value="none">Выберите специальность</option>
                <?php for($i=1;$i<=count($spec);$i++){ ?>
                    <option value="<?=$i;?>"><?=$spec[$i]['name'];?></option>
                <?php } ?>
            </select>
            <select id="select_curs" value="none">
                <option selected value="none">Выберите курс</option>
                <option value="1">1 курс</option>
                <option value="2">2 курс</option>
                <option value="3">3 курс</option>
                <option value="4">4 курс</option>
            </select>
            <div class="clear"></div>
            <strong style="margin-top: 20px;">Выберите пол:</strong>
            <div class="radio">
                <input type="radio" name="select_pol" value="none" id="sp_1" checked>
                <label for="sp_1"><i></i></label>
                <p>Любой</p>
            </div>
            <div class="radio">
                <input type="radio" name="select_pol" value="male" id="sp_2">
                <label for="sp_2"><i></i></label>
                <p>Мужской</p>
            </div>
            <div class="radio">
                <input type="radio" name="select_pol" value="female" id="sp_3">
                <label for="sp_3"><i></i></label>
                <p>Женский</p>
            </div>
            <a href="#" id="view_all" data-value="contacts">Показать всех студентов</a>
            <a href="#" id="clear_param">Сбросить</a>
        </div>
    </div>
    <div class="loading">
        <div class="load_sw"></div>
    </div>
    <div class="error_column no_search">
        <div class="img"></div>
        <p>Не удалось найти студентов<br>по вашему запросу.</p>
    </div>

    <?php if($contact == "none"){ ?>
        <div class="error_column no_contact" style="display: block;">
            <div class="img"></div>
            <p>Список контактов пуст</p>
        </div>
    <?php } ?>
    <div class="scroll_content"><?=functions::listContactView($contact);?></div>
</div>
<script>
$(document).ready(function () {
    $("#select_spec,#select_curs").niceSelect();
    
});
</script>