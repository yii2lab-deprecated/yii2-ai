<?php

namespace yii2lab\ai\game\helpers;

use yii2lab\ai\game\entities\CellEntity;
use yii2lab\ai\game\entities\FoodCellEntity;
use yii2lab\ai\game\entities\UnitCellEntity;

class WantHelper {
	
	public static function getPoint(UnitCellEntity $unitCellEntity) {
		$map = $unitCellEntity->matrix->getCellsByPoint($unitCellEntity->point);
		$possibles = self::getPossibles($map);
		$pointEntity = self::seekFood($possibles);
		if(empty($pointEntity)) {
			$pointEntity = self::randPoint($possibles);
		}
		return $pointEntity;
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
		$p = $possibles[ $randIndex ]->point;
		return $p;
	}
	
	private static function getPossibles($map) {
		$possibles = [];
		/** @var CellEntity[][] $map */
		foreach($map as $line) {
			foreach($line as $cell) {
				if($cell != null && $cell->isCanReplace()) {
					$possibles[] = $cell;
				}
			}
		}
		return $possibles;
	}
	
}