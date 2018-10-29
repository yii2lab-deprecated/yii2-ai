<?php

namespace yii2lab\ai\game\factories;

use yii\base\BaseObject;
use yii2lab\ai\game\entities\unit\BlankCellEntity;
use yii2lab\ai\game\entities\unit\CellEntity;
use yii2lab\ai\game\entities\unit\FoodEntity;
use yii2lab\ai\game\entities\PointEntity;
use yii2lab\ai\game\entities\unit\ToxicEntity;
use yii2lab\ai\game\entities\unit\BotEntity;
use yii2lab\ai\game\entities\unit\WallEntity;
use yii2lab\ai\game\events\UnitEvent;
use yii2lab\ai\game\helpers\bot\StepLogic;
use yii2lab\ai\game\helpers\Matrix;
use yii2lab\ai\game\scenario\factory\unit\CreateUnitScenario;
use yii2lab\ai\game\scenario\factory\unit\SetUnitEnergyScenario;
use yii2lab\ai\game\scenario\factory\unit\SetUnitLogicScenario;
use yii2lab\extension\scenario\collections\ScenarioCollection;

class UnitFactory {
	
	/*private static $unitFilters = [
		[
			'class' => CreateUnitScenario::class,
		],
		[
			'class' => SetUnitEnergyScenario::class,
			'energy' => 20,
		],
		[
			'class' => SetUnitLogicScenario::class,
			'logicClass' => StepLogic::class,
		],
	];*/
	
	public static function createMatrix($height, $width) {
		$FoodEntity = new BlankCellEntity();
		$matrix = new Matrix($height, $width, $FoodEntity);
		return $matrix;
	}
	
	private static function createPoint($x, $y) {
		$pointEntity = new PointEntity();
		$pointEntity->y = $x;
		$pointEntity->x = $y;
		return $pointEntity;
	}
	
	public static function createWalls(Matrix $matrix) {
		for($y = 1; $y < 4; $y++) {
			//self::randV($matrix);
		}
	}
	
	private static function randV(Matrix $matrix) {
		
		$halfWidth = round($matrix->getWidth() / 2);
		$halfHeight = round($matrix->getHeight() / 2);
		
		$len = rand(3, 8);
		$begin = rand(1, $halfWidth - $len);
		$y = rand(2, $halfWidth - 1);
		for($x1 = $begin; $x1 < $begin + $len; $x1++) {
			self::createWall($matrix, $x1, $y);
		}
		
		$len = rand(3, 8);
		$begin = rand(1, $halfHeight - $len);
		$y = rand(2, $halfHeight - 1);
		for($x1 = $begin; $x1 < $begin + $len; $x1++) {
			self::createWall($matrix, $y, $x1);
		}
	}
	
	private static function createWall(Matrix $matrix, $x, $y) {
		$wall = new WallEntity();
		$point = UnitFactory::createPoint($x, $y);
		$matrix->setCellByPoint($point, $wall);
	}
	
	public static function createFoods(Matrix $matrix) {
		foreach($matrix->getMatrix() as $x => $line) {
			foreach($line as $y => $cell) {
				if($cell instanceof BlankCellEntity) {
					if(!mt_rand(0, 1)) {
						$energy = mt_rand(1, 4) * 2;
						self::createFood($matrix, $cell, FoodEntity::class, $energy);
					}
				}
			}
		}
	}
	
	public static function createToxic(Matrix $matrix) {
		foreach($matrix->getMatrix() as $x => $line) {
			foreach($line as $y => $cell) {
				if($cell instanceof BlankCellEntity) {
					if(!mt_rand(0, 6)) {
						$energy = mt_rand(1, 4) * 2;
						self::createFood($matrix, $cell, ToxicEntity::class, 0 - $energy);
					}
				}
			}
		}
	}
	
	private static function createFood(Matrix $matrix, CellEntity $cell, $class, $energy) {
		$food = new $class;
		$food->energy = $energy;
		$matrix->setCellByPoint($cell->point, $food);
		return $food;
	}
	
	public static function createUnits(Matrix $matrix) {
		$points = [
			[
				'x' => 2,
				'y' => 2,
			],
			[
				'x' => 2,
				'y' => 15,
			],
			[
				'x' => 15,
				'y' => 2,
			],
			[
				'x' => 15,
				'y' => 15,
			],
		];
		/** @var BotEntity[] $unitCollection */
		$unitCollection = [];
		foreach($points as $point) {
			$pointEntity = UnitFactory::createPoint($point['x'], $point['y']);
			$unitCollection[] = self::createUnit($matrix, $pointEntity);
		}
		return $unitCollection;
	}
	
	private static function createUnit(Matrix $matrix, $pointEntity) {
		$botEntity = new BotEntity();
		$matrix->setCellByPoint($pointEntity, $botEntity);
		$botEntity->energy = 20;
		$botEntity->setLogic(StepLogic::class);
		return $botEntity;
		
		/*$event = new UnitEvent;
		$event->matrix = $matrix;
		$event->pointEntity = UnitFactory::createPoint($point['x'], $point['y']);
		self::runScenarios($event, self::$unitFilters);
		return $event->BotEntity;*/
	}
	
	/*private static function runScenarios(BaseObject $event, array $filters) {
		$filterCollection = new ScenarioCollection($filters);
		$filterCollection->event = $event;
		$filterCollection->runAll();
	}*/
	
}
