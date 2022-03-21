<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Apples: Собираем яблоки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>На этой странице приведена небольшая игра, посвященная сбору и съедению яблок:</p>
	
	<button class="btn btn-success" name="button" onclick="apples_generate(); return false;">Сгенерировать яблоки заново <br/>(случайным образом)</button>
<!--	
	<?= Html::submitButton('Create & Add New', ['class' => 'btn btn-primary', 'value'=>'Create & Add New', 'name'=>'submit']) ?>
-->



	<h1>Apples: сгенерировано</h1>
	<!--   Блок для приема управляющих JS-скриптов  -->
<div id="mess" style="width:0; height:0; overflow:hidden"></div>
	
<div id="tree_area" style="border:1px black solid; width:400px; height:300px; position:relative">



	<?php foreach ($apples as $apple): ?>
	<div style="position: absolute;  
				border: solid 1px green; 
				border-radius: 15px; 
				height: <?= Html::encode(1+ "{$apple->eaten}")*30;  ?>px;; 
				left: <?= Html::encode("{$apple->X }")/1060*400 ?>px; 
				top: <?= Html::encode("{$apple->Y}")/1200*300 ?>px;  
				background-color: <?= Html::encode("{$apple->color}");  ?>; 
				width: <?= Html::encode(1+ "{$apple->eaten}")*30;  ?>px;   ">

		<div id="<?= $apple->id ?>" style="top: 30px; position: absolute;">
			<?= Html::submitButton('&darr;',  ['id'=>$apple->id.'_b1', 'class' => ' btn-xs btn-primary', 'title'=>'Упасть', 'value'=>'down', 'name'=>'submit', 'onclick'=>'apples_onclick1(this); return false;']) ?>
			<?= Html::submitButton('&frac14;', ['id'=>$apple->id.'_b2', 'class' => 'btn-xs btn-dark',  'style'=>'position: absolute;', 'title'=>'Съесть 1/4 часть', 'value'=>'frac14', 'name'=>'submit', 'onclick'=>'apples_onclick1(this); return false;']) ?>
		</div>

	</div>

	<!--
	<?= $apple->eaten ?>
	-->

	<?php endforeach; ?>



</div>	


<!--
<?=  LinkPager::widget(['pagination' => $pagination]) ?>	
	-->
	
<script>	

function apples_onclick1(butt){

	var div_apple_id = butt.parentNode.id;
					 
				SendData_UNIVERSAL(GetData_UNIVERSAL('', 'to_do='+butt.value+'&div_apple_id='+div_apple_id, 'mess', 'Что-то пошло не так. ', '', '', '/Yii/yii-application/frontend/web/apples/ajax'));
							
				return false;
}

function apples_generate(){
			 
//	document.getElementById('tree_area').innerHTML = '';
	
		SendData_UNIVERSAL(GetData_UNIVERSAL('', '', 'mess', 'Что-то пошло не так. ', '', '', '/Yii/yii-application/frontend/web/apples/indexapples'));
		
		
				return false;
}

// *********************************************************************************************************
// *********************    ФУНКЦИЯ ДЛЯ ОТПРАВКИ ДАННЫХ AJAX    ********************************************
//    Вот как делается вызов функции:
//   onclick="SendData_UNIVERSAL(GetData_UNIVERSAL(this, 'flag_ED=DO_vedomosti', 'error4', 'Что-то пошло не так. Ведомости НЕ обновлены.', getThisYear, this, '/lib/libraries_my/php/DO_vedomosti.php'));"

// *********************************************************************************************************

// Шаблон функции function_TODO_***()  // Можно задать практически любое имя. Эта функция что-нибудь делает перед отправкой данных
/*
    function function_TODO_***(g) {

//  Выполняемые команды   //


        return false; // Чтобы функция SendData_UNIVERSAL() выполнила отправку данных
        return 'NO_send '; // Чтобы отправка данных НЕ выполнялась
    }
*/


// Получаем данные для последующей отправки их через AJAX функцией SendData_UNIVERSAL()
    function GetData_UNIVERSAL(event_elem, body,  id_responseText, mess, function_TODO, function_TODO_arg, path_TO_PHP_File) {
/*
* event_elem - объект нажатой кнопки, вызвавшей эту функцию (передается из переменной this)
* body - тело сообщения
* id_responseText - id элемента, в который следует поместить ответ сервера
* mess - сообщение, выводимое в элемент с id=id_responseText в случае, если ответ сервера - пустой
* function_TODO - имя функции, которую нужно выполнить перед тем, как запускать функцию передачи данных
* function_TODO_arg - аргумент функции function_TODO
* path_TO_PHP_File - относительный путь от корневого каталога к программе РНР, которой будут передаваться данные (вида '/comments/comments_man_autor.php' )
*/

// Если переданное имя функции НЕпустое, то выполняем ее
var x = '';
    if(function_TODO != ''){
       x = function_TODO(function_TODO_arg); // Результат выполнения функции function_TODO()
    }

    if(x === true){ //Если она вернет true - отправку данных НЕ делаем, выводим сообщение об ошибке
        return [true];
    }

        return [body,  id_responseText, mess, x, path_TO_PHP_File];
  }

// Универсальная функция для отправки данных через AJAX
    function SendData_UNIVERSAL(Data_Array) {

        var docum_location = window.location.protocol+"//"+document.location.hostname+':'+window.location.port;
				
		
        var xhr = new XMLHttpRequest();

//        var body = 'flag_ED=DO_otchet'+'&DATA_interval='+Data; (примерный вид)
        var body = Data_Array[0];
            if(body === true){
                alert('Функция '+ arguments.callee.name+ '(): Ошибка! Ничего НЕ сделано.');
                return;
            }

		var param = yii.getCsrfParam();
		var token = yii.getCsrfToken();
		var csrf = param+'='+token;
				
			
            body = body + '&div_apples_area='+Data_Array[1]+'&'+csrf;

//            alert(body)

// Если при выполнении функция function_TODO() получила значение 'NO_send', то прекращаем работу, отправку сообщения НЕ делаем
        if(Data_Array[3] === 'NO_send'){
            return true;
        }

	
        xhr.open("POST", docum_location+Data_Array[4], true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() { // (3)
            if (xhr.readyState != 4) return;
            if (xhr.status == 200) {
                if(xhr.responseText != 1){
                    var mess = xhr.responseText;
                    if(!mess){
                        mess = Data_Array[2];
                    }
					
					if(mess.substr(0, '/*script*/'.length) == '/*script*/'){ // если по AJAX пришел script
						eval(mess);
					}
					
                    document.getElementById(Data_Array[1]).innerHTML += mess;
                }else{
                    alert('Эта страница сейчас будет обновлена.');
                    location.reload();
                }
            } else {
                alert('xhr error:  '+ xhr.status);
            }
        };
        xhr.send(body);
        return false;
    }
// *********************************************************************************************************	
	   


</script>
    
</div>

