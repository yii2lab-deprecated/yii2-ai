<?php

namespace yii2lab\ai\game\helpers\botLogic;

use yii2lab\ai\game\entities\CellEntity;
use yii2lab\ai\game\entities\FoodCellEntity;
use yii2lab\ai\game\entities\UnitCellEntity;
use yii2lab\ai\game\helpers\MatrixHelper;

class StepLogic {
	
	public static function getPoint(UnitCellEntity $unitCellEntity) {
		$possibles = self::getPossibles($unitCellEntity);
		$pointEntity = self::seekFood($possibles);
		if(empty($pointEntity)) {
			$pointEntity = self::randPoint($possibles);
		}
		return $pointEntity;
	}
	
	private static function getPossibles(UnitCellEntity $unitCellEntity) {
		$map = $unitCellEntity->matrix->getCellsByPoint($unitCellEntity->point);
		$possibles = MatrixHelper::getPossibles($map);
		return $possibles;
	}
	
	private static function seekFood($possibles) {
		$p = null;
		foreach($possibles as $cell) {
			if($cell instanceof FoodCellEntity) {
				if($p == null) {
					$p = $cell;
				} elseif($cell->energy > $p->energy) {
					$p = $cell;
				}
			}
		}
		return $p->point;
	}
	
	private static function randPoint($possibles) {
		$randIndex = mt_rand(0, count($possibles) - 1);
		/** @var CellEntity[] $possibles */
		$p = $possibles[ $randIndex ]->point;
		return $p;
	}
}