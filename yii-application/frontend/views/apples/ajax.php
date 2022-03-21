<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\widgets\LinkPager;
use frontend\models\Apples_files\Apples;

use yii\web\NotFoundHttpException;


$to_do = ''; $div_apple_id = ''; $resp = 'not defined...';


if(isset($_REQUEST['to_do']) && (strlen($_REQUEST['to_do']) < 10)){
    $to_do = $_REQUEST['to_do'];

    if($to_do === 'down'){
	
		if(isset($_REQUEST['div_apple_id']) && (strlen($_REQUEST['div_apple_id']) < 10)){
			$div_apple_id = $_REQUEST['div_apple_id'];
			
			$apple = Apples::find()->where(['id' => $div_apple_id])->one();
			$apple->Y = 1200;
			$apple->status = 'down';
			
			$currentDateStamp = new \DateTime();
			$apple->date_fall = date("Y-m-d H:i:s", $currentDateStamp->getTimestamp());
			$apple->save();
		}
        $resp = '/*script*/
	document.getElementById('.$div_apple_id.').parentNode.style.top = "300px"; ';
    }
	
	if($to_do === 'frac14'){ // Съедание по 1/4 яблока
	
		if(isset($_REQUEST['div_apple_id']) && (strlen($_REQUEST['div_apple_id']) < 10)){
		
			$div_apple_id = $_REQUEST['div_apple_id'];
			
			$apple = Apples::find()->where(['id' => $div_apple_id])->one();
			
			
			try {
			
				if($apple->status === 'up'){
					$resp = 'Яблоко съесть нельзя, т.к. оно еще висит на дереве. Вначале нужно его уронить вниз.';
					throw new \yii\web\HttpException(451, $resp);
					
				}else{ // Это JS производит операции с яблоками на странице: уменьшает их размер
			
					$date_fall = new DateTime($apple->date_fall);
					$date_current = new DateTime();
				

				
					$diff = $date_current->getTimestamp() - $date_fall->getTimestamp();
				
					if($diff > 1*60){ // Если яблоко пролежало более 60 секунд
					
						$resp = 'Яблоко уже испортилось: оно пролежало, т.к. прошло более 60 cек.';
						$apple->status = 'bad';
						$apple->save();
						
						throw new \yii\web\HttpException(451, $resp);
						
					}else{
		
					 $resp = '/*script*/
			document.getElementById('.$div_apple_id.').parentNode.style.borderRadius = parseInt(document.getElementById('.$div_apple_id.').parentNode.style.borderRadius)*3/4 +"px" ; 
			
			document.getElementById('.$div_apple_id.').parentNode.style.width = parseInt(document.getElementById('.$div_apple_id.').parentNode.style.width)*3/4 +"px";
			
			document.getElementById('.$div_apple_id.').parentNode.style.height = parseInt(document.getElementById('.$div_apple_id.').parentNode.style.height)*3/4 +"px";
			
			if('.$apple->eaten. ' <= -1){
				document.getElementById('.$div_apple_id.').parentNode.style.borderRadius = 0;
				document.getElementById('.$div_apple_id.').parentNode.style.width = 0;
				document.getElementById('.$div_apple_id.').parentNode.style.height = 0;
			}';
					if($apple->eaten <= -1){
						$apple->eaten = -1;
						
						$model = apples::find()->where(['id' => $div_apple_id])->one();
						// удаляем строку
						$model->delete();
						$resp = '/*script*/
							var elem = document.getElementById('.$div_apple_id.').parentNode;
							elem.parentNode.removeChild(elem); // Удаляем яблоко с кнопками со страницы
						';
				
					}else{
						$apple->eaten = $apple->eaten - 1/4;
					}
					
					$apple->save();
					}
				}
        
			} catch ( ErrorException $e ) {
				$resp = $e->getMessage();
//				echo $resp;
			}
			
		}
 	
    }
	

}



echo $resp;


die('');
