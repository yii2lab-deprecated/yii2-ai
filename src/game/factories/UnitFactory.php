<?php

namespace yii2lab\ai\game\factories;

use yii2lab\ai\game\entities\FoodCellEntity;
use yii2lab\ai\game\entities\PointEntity;
use yii2lab\ai\game\entities\UnitCellEntity;
use yii2lab\ai\game\helpers\Matrix;

class UnitFactory {
	
	public static function createMatrix($size) {
		$foodCellEntity = new FoodCellEntity();
		$matrix = new Matrix($size, $size, $foodCellEntity);
		return $matrix;
	}
	
	public static function createPoint($x, $y) {
		$pointEntity = new PointEntity();
		$pointEntity->y = $x;
		$pointEntity->x = $y;
		return $pointEntity;
	}
	
	public static function createUnit(Matrix $matrix, $x, $y) {
		$pointEntity = self::createPoint($x, $y);
		$unitEntity = new UnitCellEntity();
		$matrix->setCellByPoint($pointEntity, $unitEntity);
		return $unitEntity;
	}
	
}
