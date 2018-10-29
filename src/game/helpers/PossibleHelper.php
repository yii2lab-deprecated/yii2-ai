<?php

namespace yii2lab\ai\game\helpers;

use yii\base\BaseObject;
use yii2lab\ai\game\entities\BlankCellEntity;
use yii2lab\ai\game\entities\CellEntity;
use yii2lab\ai\game\entities\FoodCellEntity;
use yii2lab\ai\game\entities\ToxicCellEntity;
use yii2lab\ai\game\entities\WallCellEntity;
use yii2lab\ai\game\events\MoveEvent;
use yii2lab\ai\game\scenario\step\IsPossibleScenario;
use yii2lab\extension\scenario\collections\ScenarioCollection;

class PossibleHelper {
	
	private static $possibleStepFilters = [
		[
			'class' => IsPossibleScenario::class,
			'possibleClasses' => [
				BlankCellEntity::class,
				FoodCellEntity::class,
				ToxicCellEntity::class,
			],
			'notPossibleClasses' => [
				WallCellEntity::class,
			],
		],
	];
	
	public static function getPossibles($map) {
		$possibles = [];
		/** @var CellEntity[][] $map */
		foreach($map as $line) {
			foreach($line as $toCellEntity) {
				if(self::isPossible($toCellEntity)) {
					$possibles[] = $toCellEntity;
				}
			}
		}
		return $possibles;
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