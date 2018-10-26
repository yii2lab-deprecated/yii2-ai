<?php

namespace yii2lab\ai\domain\services;

use NlpTools\Tokenizers\PennTreeBankTokenizer;
use yii2lab\ai\domain\entities\TrainEntity;
use yii2lab\ai\domain\interfaces\services\TrainInterface;
use yii2lab\domain\data\Query;
use yii2lab\domain\services\base\BaseActiveService;
use yii2lab\extension\common\helpers\StringHelper;
use yii2lab\helpers\yii\ArrayHelper;

/**
 * Class TrainService
 * 
 * @package yii2lab\ai\domain\services
 * 
 * @property-read \yii2lab\ai\domain\Domain $domain
 * @property-read \yii2lab\ai\domain\interfaces\repositories\TrainInterface $repository
 */
class TrainService extends BaseActiveService implements TrainInterface {
	
	private function filterValue($trainCollection) {
		//return $trainCollection;
		/** @var TrainEntity[] $trainCollection */
		foreach($trainCollection as $trainEntity) {
			$trainEntity->value = strtolower($trainEntity->value);
			$trainEntity->value = StringHelper::textToLine($trainEntity->value);
			//$trainEntity->value = preg_replace('#([^\w\s\d]+)#i', ' ', $trainEntity->value);
			//$trainEntity->value = preg_replace('#[\.]+#', '$1', $trainEntity->value);
			$trainEntity->value = StringHelper::removeDoubleSpace($trainEntity->value);
			$trainEntity->value = trim($trainEntity->value);
			//$tokenizer = new PennTreeBankTokenizer();
			//$trainEntity->value = implode(" ",$tokenizer->tokenize($trainEntity->value));
		}
		//prr($trainCollection,1,1);
		return $trainCollection;
	}
	
	public function allTrain($botId, $trainPercent = 100) {
		$classIds = $this->allClassOfBot($botId);
		$query = Query::forge();
		$query->where(['class_id' => $classIds]);
		$count = \App::$domain->ai->train->count();
		$border = ($count / 100) * $trainPercent;
		$query->offset(0);
		$query->limit($border);
		$collection = \App::$domain->ai->train->all($query);
		$collection = $this->filterValue($collection);
		return $collection;
	}
	
	public function allTest($botId, $trainPercent = 100) {
		$classIds = $this->allClassOfBot($botId);
		$query = Query::forge();
		$query->where(['class_id' => $classIds]);
		$count = \App::$domain->ai->train->count();
		$border = ($count / 100) * $trainPercent;
		$query->offset($border+1);
		$collection = \App::$domain->ai->train->all($query);
		$collection = $this->filterValue($collection);
		return $collection;
	}
	
	private function allClassOfBot($botId) {
		$query = Query::forge();
		$query->where(['bot_id' => $botId]);
		$classCollection =  \App::$domain->ai->class->all($query);
		return ArrayHelper::getColumn($classCollection, 'id');
	}
	
}
