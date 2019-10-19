<?php require_once ROOT.'/views/layouts/head.php';?>
<body id="bg_reg">
	<div id="popups">
		<input type="button" class="close">
		<div class="loading"><div class="load_sw"></div></div>
		<div id="insert_window"></div>
	</div>
    <div id="form_register">
        <h1>VINEFT REGISTER</h1>
        <p class="error"></p>
        <div class="loading">
            <div class="load_sw"></div>
            <p>Изображение загружается, на это может потребоваться некоторое время!</p>
        </div>
        <div id="dynamic_layouts">
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
			            <input type="text" placeholder="Иванов" data-id="surname">
			        </div>
			        <div class="clear"></div>
			        <div class="group_input">
			            <label>Дата рождения:</label>
			            <input type='text' class="datepicker-here" data-position="bottom left" placeholder="дд/мм/гг" data-id="birthday">
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
			            	<option value="0" disabled selected>Выберите специальность</option>

							<?php for($i=1;$i<=count($spec);$i++){ ?>
							    <option value="<?=$i;?>"><?=$spec[$i]['name'];?></option>
							<?php } ?>

			            </select>
			        </div>
			        <div class="group_input" data-name="course">
			            <label>* Курс:</label>
			            <select id="select_course">
			            	<option value="0" disabled selected>-</option>
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
			                <input type="radio" name="select_male" id="select_men" value="male">
			                <label for="select_men"></label>
			                <p>Мужской</p>
			            </div>
			            <div>
			                <input type="radio" name="select_male" id="select_girl" value="female">
			                <label for="select_girl"></label>
			                <p>Женский</p>
			            </div>
			        </div>
			        <div class="clear"></div>
			    </div>
			    <p class="text">* Поля являются обязательными к заполнению</p>
			    <input type="button" class="button" id="resume_reg" value="Продолжить">
			</div>
        </div>
    </div>
</body>
</html>
<script>
$(document).ready(function(){
	$('#select_spec,#select_course').niceSelect();
});
</script>