<?php
namespace frontend\controllers;
use yii\web\Controller;
use yii\data\Pagination;

use frontend\models\Apples_files\Apples;
use frontend\models\Apples_files\RandomNumber;

use yii\helpers\Html;

class ApplesController extends Controller
{
	 public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


	public function actionIndex()
	{
		return $this->actionIndexPagePrepare(1); // ������ ��� ���������
	}
	
	public function actionIndexapples()
	{ // ��� AJAX-�������
		return $this->actionIndexPagePrepare(10); // ������ � ���������� 10 �����
	}
	
	public function actionIndexPagePrepare($apples_number)
	{
		if($apples_number > 1){
			$flag_generate = 1;
		}else{
			$flag_generate = 0;
		}
	
		$database = \Yii::$app->db->dsn;
		$dbname = explode("=", $database);
		$dbname = $dbname[2]; // ��� ���� ������ � ����� \Yii\yii-application\common\config\main-local.php
	
		$this->actionCreateDatabase($dbname); // ���� ��� ���� ������, �� ������� ��
		
		$table = '{{apples}}';
		$this->actionCreateTable($table);
		
		if($apples_number > 1){
			apples::deleteAll(); // ��� ��������� ����� ����� ������� ������� ������� ���� ������
		}

	
for($i=1; $i < $apples_number; $i++){ // ���������� ����������� ����� - 1
	
	$apple = new Apples();

	$apple->X = RandomNumber::random_number(3);
	$apple->Y = RandomNumber::random_number(3);
	$apple->color = 'rgb('. (int)((RandomNumber::random_number(1) % 3). (RandomNumber::random_number(1) % 4). RandomNumber::random_number(1)). ', 224, 0)'; // �����-������� �����
	$apple->status = 'up';
	$apple->eaten = '0'; // 0 - ������ �� �������; -1 - ��������� �������

	$rand_num = RandomNumber::random_number(2);
	$rand_num = (int)$rand_num; // ���������� ��������� �����
	$currentDateStamp = new \DateTime();
	$apple->date_begin = date("Y-m-d H:i:s", $currentDateStamp->getTimestamp() - $rand_num);

	$apple->save();
	
}
	
		$query = Apples::find(); // ����� �� frontend\models\Apples.php
		$pagination = new Pagination([
		'defaultPageSize' => 100, // �� ������� ����� �������� �� 1 ��������
		'totalCount' => $query->count(),
		]);
	
		$apples = $query->orderBy('id')
		->offset($pagination->offset)
		->limit($pagination->limit)
		->all();
	
//  
		
		if($flag_generate == 1){
			return '1'; // ����� ��������� ����������� �������� ����� ��������� ������ �� ������
		}
		
		return $this->render('index', [
		'apples' => $apples,
		'pagination' => $pagination,
		]);
	}
	
	
	public function actionAjax(){
	
		return $this->render('ajax', [
		]);
	}
	
	
	public function actionCreateTable($table)
	{
		$SQL = "CREATE TABLE IF NOT EXISTS $table (
	  `id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY UNIQUE,
	  `X` int(7) DEFAULT NULL,
	  `Y` int(7) DEFAULT NULL,
	  `color` varchar(255) ,
	  `status` varchar(255) DEFAULT NULL,
	  `eaten` varchar(255) ,
	  `date_begin` datetime ,
	  `date_fall` timestamp  DEFAULT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";	
		
		\Yii::$app->db->createCommand($SQL)->execute();
	}

	
	public  function actionCreateDatabase($database)
	{
		 $sql = "CREATE DATABASE IF NOT EXISTS $database";
 
        try {
            $status = \Yii::$app->db->createCommand($sql)->execute();
            $message = ( $status ) ? 'completed' : 'abort';
 
        } catch ( \Exception $e ) {
            $status = $this->stdout('catch', BaseConsole::FG_RED );
            $message = $e->getMessage();
			echo $message;
        }
	}

}