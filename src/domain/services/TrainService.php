<?php

namespace yii2lab\ai\domain\services;

use NlpTools\Tokenizers\PennTreeBankTokenizer;
use NlpTools\Tokenizers\WhitespaceTokenizer;
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
			$trainEntity->value = preg_replace('#[\.]+#', '$1', $trainEntity->value);
			$trainEntity->value = preg_replace('#[\(\)\']+#', ' ', $trainEntity->value);
			$trainEntity->value = StringHelper::removeDoubleSpace($trainEntity->value);
			$trainEntity->value = trim($trainEntity->value);
			//$tok = new WhitespaceTokenizer();
			//$trainEntity->value = $tok->tokenize($trainEntity->value);
			//$tokenizer = new PennTreeBankTokenizer();
			//$trainEntity->value = implode(" ",$tokenizer->tokenize($trainEntity->value));
		}
		//prr($trainCollection,1,1);
		return $trainCollection;
	}
	
	public function all(Query $query = null) {
		$collection = parent::all($query);
		$collection = $this->filterValue($collection);
		return $collection;
	}
	
}
