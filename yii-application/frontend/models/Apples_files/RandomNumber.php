<?php
// ��������� ����� ��������� �����: [0...9]

namespace frontend\models\Apples_files;
use Yii;
use yii\base\Model;

\Yii::debug('DEBUG', __METHOD__);

class RandomNumber extends Model
{
	public static function random_number($number) // $number - ������������ ����� ���� (��������) � ��������� �����
	{
		
		$num = '';
		for($i=1; $i < $number+1; $i++){
			$string = Yii::$app->security->generateRandomString(1);
			$num .= (ord($string) % 10);
		}
	
		return (int)$num;
	}
}