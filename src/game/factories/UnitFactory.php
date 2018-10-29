<?php

namespace yii2lab\ai\game\factories;

use yii\base\BaseObject;
use yii2lab\ai\game\entities\BlankCellEntity;
use yii2lab\ai\game\entities\CellEntity;
use yii2lab\ai\game\entities\FoodCellEntity;
use yii2lab\ai\game\entities\PointEntity;
use yii2lab\ai\game\entities\ToxicCellEntity;
use yii2lab\ai\game\entities\UnitCellEntity;
use yii2lab\ai\game\entities\WallCellEntity;
use yii2lab\ai\game\events\UnitEvent;
use yii2lab\ai\game\helpers\botLogic\FixLogic;
use yii2lab\ai\game\helpers\Matrix;
use yii2lab\ai\game\scenario\factory\unit\CreateUnitScenario;
use yii2lab\ai\game\scenario\factory\unit\SetUnitEnergyScenario;
use yii2lab\ai\game\scenario\factory\unit\SetUnitLogicScenario;
use yii2lab\extension\scenario\collections\ScenarioCollection;

class UnitFactory {
	
	private static $unitFilters = [
		[
			'class' => CreateUnitScenario::class,
		],
		[
			'class' => SetUnitEnergyScenario::class,
			'energy' => 20,
		],
		[
			'class' => SetUnitLogicScenario::class,
			'logicClass' => FixLogic::class,
		],
	];
	
	public static function createMatrix($size) {
		$foodCellEntity = new BlankCellEntity();
		$matrix = new Matrix($size, $size, $foodCellEntity);
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
			self::randV($matrix);
		}
	}
	
	private static function randV(Matrix $matrix) {
		
		$len = rand(3, 8);
		$begin = rand(1, $matrix->getWidth() - 8);
		$y = rand(2, $matrix->getWidth() - 1);
		for($x1 = $begin; $x1 < $begin + $len; $x1++) {
			self::createWall($matrix, $x1, $y);
		}
		
		$len = rand(3, 8);
		$begin = rand(1, $matrix->getHeight() - 8);
		$y = rand(2, $matrix->getHeight() - 1);
		for($x1 = $begin; $x1 < $begin + $len; $x1++) {
			self::createWall($matrix, $y, $x1);
		}
	}
	
	private static function createWall(Matrix $matrix, $x, $y) {
		$wall = new WallCellEntity();
		$point = UnitFactory::createPoint($x, $y);
		$matrix->setCellByPoint($point, $wall);
	}
	
	
	public static function createFoods(Matrix $matrix) {
		foreach($matrix->getMatrix() as $x => $line) {
			foreach($line as $y => $cell) {
				if($cell instanceof BlankCellEntity) {
					if(!mt_rand(0, 1)) {
						$energy = mt_rand(1, 4) * 2;
						self::createFood($matrix, $cell, FoodCellEntity::class, $energy);
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
						self::createFood($matrix, $cell, ToxicCellEntity::class, 0 - $energy);
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
		/** @var UnitCellEntity[] $unitCollection */
		$unitCollection = [];
		foreach($points as $point) {
			$unitCollection[] = self::createUnit($matrix, $point);
		}
		return $unitCollection;
	}
	
	private static function createUnit(Matrix $matrix, $point) {
		$event = new UnitEvent;
		$event->matrix = $matrix;
		$event->pointEntity = UnitFactory::createPoint($point['x'], $point['y']);
		self::runScenarios($event, self::$unitFilters);
		return $event->unitCellEntity;
	}
	
	private static function runScenarios(BaseObject $event, array $filters) {
		$filterCollection = new ScenarioCollection($filters);
		$filterCollection->event = $event;
		$filterCollection->runAll();
	}
	
}
