<?php

namespace yii2lab\ai\game\helpers;

use yii\base\InvalidArgumentException;
use yii2lab\ai\game\entities\BlankCellEntity;
use yii2lab\ai\game\entities\CellEntity;
use yii2lab\ai\game\entities\PointEntity;
use yii2lab\ai\game\enums\ColorEnum;
use yii2lab\ai\game\exceptions\PointOverMatrixException;
use yii2lab\extension\yii\helpers\ArrayHelper;

class Matrix {
	
	private $matrix = [];
	private $size;
	private $blankCellEntity;
	
	/**
	 * @return array
	 * @deprecated
	 */
	public function getMatrix() {
		return $this->matrix;
	}
	
	/*
	 * @param int $x
	 * @param int $y
	 *
	 * @return CellEntity
	 */
	/*public function getCell(int $x, int $y) {
		return $this->matrix[$x][$y];
	}*/
	
	public function createMatrix(int $h, int $v, CellEntity $blankCellEntity = null) {
		$blankCellEntityMain = $blankCellEntity instanceof CellEntity ? $blankCellEntity : new BlankCellEntity;
		$this->size = $h;
		$pointEntity = new PointEntity;
		for($x = 1; $x <= $h; $x++) {
			for($y = 1; $y <= $v; $y++) {
				$pointEntity->x = $x;
				$pointEntity->y = $y;
				$blankCellEntity = clone $blankCellEntityMain;
				$this->forgeCellEntity($blankCellEntity, $pointEntity);
				$this->setCellByPoint($pointEntity, $blankCellEntity);
			}
		}
	}
	
	public function moveCellEntity(CellEntity $cellEntity, PointEntity $toPointEntity) {
		$fromPointEntity = clone $cellEntity->point;
		$this->setCellByPoint($toPointEntity, $cellEntity);
		$this->removeCellByPoint($fromPointEntity);
	}
	
	/**
	 * @param PointEntity $pointEntity
	 *
	 * @return CellEntity
	 */
	public function getCellByPoint(PointEntity $pointEntity) {
		try {
			$this->validatePoint($pointEntity);
		} catch(\Exception $e) {
			return null;
		}
		return ArrayHelper::getValue($this->matrix, $pointEntity->x . DOT .  $pointEntity->y, null);
	}
	
	/*private function moveCellByPoints(PointEntity $fromPointEntity, PointEntity $toPointEntity) {
		$fromCellEntity = $this->getCellByPoint($fromPointEntity);
		if(!$fromCellEntity) {
			return;
		}
		$this->setCellByPoint($toPointEntity, $fromCellEntity);
		$this->removeCellByPoint($fromPointEntity);
	}*/
	
	private function forgeCellEntity(CellEntity $cellEntity, PointEntity $pointEntity) {
		/*if($cellEntity->matrix && $cellEntity->matrix !== $this) {
			throw new InvalidArgumentException('Bad matrix!');
		}*/
		
		$matrix = $cellEntity->matrix;
		
		if($matrix == null) {
			$cellEntity->matrix = $this;
		}
		$cellEntity->point = clone $pointEntity;
		$cellEntity->validate();
	}
	
	private function createCellEntity(PointEntity $pointEntity, $className) {
		/** @var CellEntity $cellEntity */
		$cellEntity = new $className;
		$this->setCellByPoint($pointEntity, $cellEntity);
		return $cellEntity;
	}
	
	public function setCellByPoint(PointEntity $pointEntity, CellEntity $cellEntity = null) {
		$this->validatePoint($pointEntity);
		$this->forgeCellEntity($cellEntity, $pointEntity);
		$cellEntity->point = clone $pointEntity;
		$cellEntity->validate();
		$this->matrix[$pointEntity->x][$pointEntity->y] = $cellEntity;
	}
	
	public function removeCellByPoint(PointEntity $pointEntity) {
		$this->validatePoint($pointEntity);
		$this->createCellEntity($pointEntity, BlankCellEntity::class);
	}
	
	private function validatePoint(PointEntity $pointEntity) {
		$pointEntity->validate();
		if($pointEntity->x < 1 || $pointEntity->x > $this->size) {
			throw new PointOverMatrixException('Point x over!');
		}
		if($pointEntity->y < 1 || $pointEntity->y > $this->size) {
			throw new PointOverMatrixException('Point y over!');
		}
	}
	
}
