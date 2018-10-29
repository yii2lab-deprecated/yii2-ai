<?php

namespace yii2lab\ai\game\helpers;

use yii\base\BaseObject;
use yii2lab\ai\game\entities\unit\BlankCellEntity;
use yii2lab\ai\game\entities\unit\CellEntity;
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
				BlankCellEntity::class,
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
	 * @return CellEntity[]|EntityCollection
	 */
	public static function getPossibles(array $map) {
		$collection = new EntityCollection(CellEntity::class);
		/** @var CellEntity[][] $map */
		foreach($map as $line) {
			foreach($line as $toCellEntity) {
				if(self::isPossible($toCellEntity)) {
					$collection[] = $toCellEntity;
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
	
	private static function isPossible(CellEntity $toCellEntity = null) {
		if($toCellEntity == null) {
			return false;
		}
		$event = new MoveEvent();
		$event->toCellEntity = $toCellEntity;
		$isCanReplace = self::runScenarios($event, self::$possibleStepFilters);
		if(!$isCanReplace) {
			return false;
		}
		return true;
	}
	
}