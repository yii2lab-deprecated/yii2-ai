<?php

namespace yii2lab\ai\game\factories;

use yii2lab\ai\game\entities\BlankCellEntity;
use yii2lab\ai\game\entities\CellEntity;
use yii2lab\ai\game\entities\FoodCellEntity;
use yii2lab\ai\game\entities\PointEntity;
use yii2lab\ai\game\entities\ToxicCellEntity;
use yii2lab\ai\game\entities\UnitCellEntity;
use yii2lab\ai\game\entities\WallCellEntity;
use yii2lab\ai\game\helpers\Matrix;

class UnitFactory {
	
	public static function createMatrix($size) {
		$foodCellEntity = new BlankCellEntity();
		$matrix = new Matrix($size, $size, $foodCellEntity);
		return $matrix;
	}
	
	public static function createPoint($x, $y) {
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
		/** @var UnitCellEntity[] $unitCollection */
		$unitCollection = [];
		$unitCollection[] = UnitFactory::createUnit($matrix, 2, 2);
		$unitCollection[] = UnitFactory::createUnit($matrix, 2, 15);
		$unitCollection[] = UnitFactory::createUnit($matrix, 15, 2);
		$unitCollection[] = UnitFactory::createUnit($matrix, 15, 15);
		return $unitCollection;
	}
	
	public static function createUnit(Matrix $matrix, $x, $y) {
		$pointEntity = self::createPoint($x, $y);
		$unitEntity = new UnitCellEntity();
		$matrix->setCellByPoint($pointEntity, $unitEntity);
		return $unitEntity;
	}
	
}
