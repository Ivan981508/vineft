<div id="stage_reg">
    <div id="info_site">
        <div class="dynamic_img">
            <img src="template/image/silhouette.png">
            <div class="group_circle">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
        <strong><span>Общайся.</span>Находи студентов.</strong>
        <p class="text">Социальная сеть для студентов орского нефтяного техникума</p>
    </div>
    <div id="basic_inform">
        <p class="h">Основная информация</p>
        <div class="group_input">
            <label>* Ваше имя:</label>
            <input type="text" placeholder="Иван" data-id="name">    
        </div>
        <div class="group_input">
            <label>* Ваша фамилия:</label>
            <input type="text" placeholder="Иванов" data-id="name">
        </div>
        <div class="clear"></div>
        <div class="group_input">
            <label>Дата рождения:</label>
            <input type='text' class="datepicker-here" data-position="bottom left" placeholder="дд/мм/гг">
        </div>
        <div class="group_input">
            <label>Город:</label>
            <input type="text" placeholder="Орск" data-id="city">
        </div>
        <div class="clear"></div>
    </div>
    <div id="student_info">
        <p class="h">Информация о студенте</p>
        <div class="group_input" data-name="number_ticket">
            <label>* № студ. билета:</label>
            <input type='number' placeholder="000" min="0" max="999">
        </div>
        <div class="group_input" data-name="specialty">
            <label>* Специальность:</label>
            <select id="select_spec" class="leave_top">
                <option value="1">Аналитический контроль качества химических соединений</option>
                <option value="2">Банковское дело</option>
                <option value="3">Экономика и бухгалтерский учет</option>
                <option value="4">Монтаж и техническая эксплуатация промышленного оборудования</option>
                <option value="5">Пожарная безопасность</option>
                <option value="6">Программирование в компьютерных системах</option>
                <option value="7">Переработка нефти и газа</option>
                <option value="8">Рациональное использование природохозяйственных комплексов</option>
            </select>
        </div>
        <div class="group_input" data-name="course">
            <label>* Курс:</label>
            <select id="select_course">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </div>
        <div class="clear"></div>
        <label>Выберите ваш пол:</label>
        <div id="group_select_male">
            <div>
                <input type="radio" name="select_male" id="select_men" value="1">
                <label for="select_men"></label>
                <p>Мужской</p>
            </div>
            <div>
                <input type="radio" name="select_male" id="select_girl" value="2">
                <label for="select_girl"></label>
                <p>Женский</p>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <p class="text">* Поля являются обязательными к заполнению</p>
    <input type="button" class="button" id="resume_reg" value="Продолжить">
</div>
<script>
    $('#select_spec,#select_course').niceSelect();
</script>