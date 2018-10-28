<?php

namespace yii2lab\ai\game\helpers;

use yii\base\InvalidArgumentException;
use yii2lab\ai\game\entities\BlankCellEntity;
use yii2lab\ai\game\entities\CellEntity;
use yii2lab\ai\game\entities\FoodCellEntity;
use yii2lab\ai\game\entities\PointEntity;
use yii2lab\ai\game\entities\UnitCellEntity;
use yii2lab\ai\game\exceptions\PointOverMatrixException;
use yii2lab\extension\yii\helpers\ArrayHelper;

class Matrix {
	
	private $matrix = [];
	private $height;
	private $width;
	
	public function getHeight() {
		return $this->height;
	}
	
	public function getWidth() {
		return $this->width;
	}
	
	public function __construct($height, $width, CellEntity $blankCellEntity = null) {
		$this->height = $height;
		$this->width = $width;
		$this->createMatrix($height, $width, $blankCellEntity);
	}
	
	private function onMove(UnitCellEntity $cellEntity, CellEntity $toCellEntity) {
		if($toCellEntity instanceof FoodCellEntity) {
			$cellEntity->upEnergy($toCellEntity->energy);
		} else {
			$cellEntity->downEnergy(5);
		}
	}
	
	public function moveCellEntity(UnitCellEntity $cellEntity, PointEntity $toPointEntity) {
		$fromPointEntity = clone $cellEntity->point;
		$toCellEntity = $this->getCellByPoint($toPointEntity);
		$this->onMove($cellEntity, $toCellEntity);
		$this->setCellByPoint($toPointEntity, $cellEntity);
		$this->removeCellByPoint($fromPointEntity);
	}
	
	public function getCellsByPoint(PointEntity $pointEntity, $size = 1) {
		$beginX = $pointEntity->x - $size;
		$endX = $pointEntity->x + $size;
		$beginY = $pointEntity->y - $size;
		$endY = $pointEntity->y + $size;
		/** @var CellEntity[][] $res */
		$res = [];
		for($x = $beginX; $x <= $endX; $x++) {
			$line = [];
			for($y = $beginY; $y <= $endY; $y++) {
				$line[] = $this->matrix[ $x ][ $y ];
			}
			$res[] = $line;
		}
		return $res;
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
		return ArrayHelper::getValue($this->matrix, $pointEntity->x . DOT . $pointEntity->y, null);
	}
	
	public function setCellByPoint(PointEntity $pointEntity, CellEntity $cellEntity = null) {
		$this->validatePoint($pointEntity);
		$this->forgeCellEntity($cellEntity, $pointEntity);
		$cellEntity->point = clone $pointEntity;
		$cellEntity->validate();
		$this->matrix[ $pointEntity->x ][ $pointEntity->y ] = $cellEntity;
	}
	
	public function removeCellByPoint(PointEntity $pointEntity) {
		$this->validatePoint($pointEntity);
		$this->createCellEntity($pointEntity, BlankCellEntity::class);
	}
	
	public function getMatrix() {
		return $this->matrix;
	}
	
	private function createMatrix(int $h, int $v, CellEntity $blankCellEntity = null) {
		$blankCellEntityMain = $blankCellEntity instanceof CellEntity ? $blankCellEntity : new BlankCellEntity;
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
	
	private function forgeCellEntity(CellEntity $cellEntity, PointEntity $pointEntity) {
		if($cellEntity->matrix && $cellEntity->matrix !== $this) {
			throw new InvalidArgumentException('Bad matrix!');
		}
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
	
	private function validatePoint(PointEntity $pointEntity) {
		$pointEntity->validate();
		if($pointEntity->x < 1 || $pointEntity->x > $this->height) {
			throw new PointOverMatrixException('Point x over!');
		}
		if($pointEntity->y < 1 || $pointEntity->y > $this->width) {
			throw new PointOverMatrixException('Point y over!');
		}
	}
	
}