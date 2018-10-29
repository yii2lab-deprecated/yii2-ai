<?php

namespace yii2lab\ai\game\helpers;

use yii\base\BaseObject;
use yii2lab\ai\game\entities\unit\BlankEntity;
use yii2lab\ai\game\entities\unit\BaseUnitEntity;
use yii2lab\ai\game\entities\unit\FoodEntity;
use yii2lab\ai\game\entities\unit\ToxicEntity;
use yii2lab\ai\game\entities\unit\WallEntity;
use yii2lab\ai\game\events\MoveEvent;
use yii2lab\ai\game\scenario\step\IsPossibleScenario;
use yii2lab\domain\data\EntityCollection;
use yii2lab\extension\scenario\collections\ScenarioCollection;

class PossibleHelper {
	
	private static $possibleStepFilters = [
		[
			'class' => IsPossibleScenario::class,
			'possibleClasses' => [
				BlankEntity::class,
				FoodEntity::class,
				ToxicEntity::class,
			],
			'notPossibleClasses' => [
				WallEntity::class,
			],
		],
	];
	
	/**
	 * @param array $map
	 *
	 * @return BaseUnitEntity[]|EntityCollection
	 */
	public static function getPossibles(array $map) {
		$collection = new EntityCollection(BaseUnitEntity::class);
		/** @var BaseUnitEntity[][] $map */
		foreach($map as $line) {
			foreach($line as $toBaseUnitEntity) {
				if(self::isPossible($toBaseUnitEntity)) {
					$collection[] = $toBaseUnitEntity;
				}
			}
		}
		return $collection;
	}
	
	private static function runScenarios(BaseObject $event, array $filters) {
		$filterCollection = new ScenarioCollection($filters);
		$filterCollection->event = $event;
		return $filterCollection->runIs();
	}
	
	private static function isPossible(BaseUnitEntity $toBaseUnitEntity = null) {
		if($toBaseUnitEntity == null) {
			return false;
		}
		$event = new MoveEvent();
		$event->toBaseUnitEntity = $toBaseUnitEntity;
		$isCanReplace = self::runScenarios($event, self::$possibleStepFilters);
		if(!$isCanReplace) {
			return false;
		}
		return true;
	}
	
}