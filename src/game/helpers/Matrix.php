<?php

namespace yii2lab\ai\game\helpers;

use yii2lab\ai\game\entities\unit\BlankCellEntity;
use yii2lab\ai\game\entities\unit\CellEntity;
use yii2lab\ai\game\entities\PointEntity;
use yii2lab\ai\game\entities\unit\BotEntity;
use yii2lab\ai\game\events\MoveEvent;
use yii2lab\ai\game\exceptions\PointOverMatrixException;
use yii2lab\ai\game\scenario\step\EnergyScenario;
use yii2lab\domain\exceptions\ReadOnlyException;
use yii2lab\extension\scenario\collections\ScenarioCollection;
use yii2lab\extension\yii\helpers\ArrayHelper;

class Matrix {
	
	/**
	 * @var CellEntity[][]
	 */
	private $matrix = [];
	private $height;
	private $width;
	
	public function __construct($height, $width, CellEntity $blankCellEntity = null) {
		$this->height = $height;
		$this->width = $width;
		$this->createMatrix($height, $width, $blankCellEntity);
	}
	
	public function getMatrix() {
		return $this->matrix;
	}
	
	public function getHeight() {
		return $this->height;
	}
	
	public function getWidth() {
		return $this->width;
	}
	
	private function onMove(BotEntity $fromCellEntity, CellEntity $toCellEntity) {
		$filters = [
			EnergyScenario::class,
		];
		$filterCollection = new ScenarioCollection($filters);
		$event = new MoveEvent;
		$event->fromCellEntity = $fromCellEntity;
		$event->toCellEntity = $toCellEntity;
		$filterCollection->event = $event;
		$filterCollection->runAll();
	}
	
	public function moveCellEntity(BotEntity $cellEntity, PointEntity $toPointEntity) {
		$fromPointEntity = clone $cellEntity->point;
		$toCellEntity = $this->getCellByPoint($toPointEntity);
		$this->onMove($cellEntity, $toCellEntity);
		$this->setCellByPoint($toPointEntity, $cellEntity);
		$this->removeCellByPoint($fromPointEntity);
	}
	
	public function getPossibleCollection(PointEntity $pointEntity) {
		$map = $this->getCellsByPoint($pointEntity);
		$possibles = PossibleHelper::getPossibles($map);
		return $possibles;
	}
	
	public function setCellByPoint(PointEntity $pointEntity, CellEntity $cellEntity = null) {
		$this->validatePoint($pointEntity);
		$this->forgeCellEntity($cellEntity, $pointEntity);
		$cellEntity->point = clone $pointEntity;
		$cellEntity->validate();
		$this->matrix[ $pointEntity->x ][ $pointEntity->y ] = $cellEntity;
	}
	
	private function getCellsByPoint(PointEntity $pointEntity, $size = 1) {
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
	
	private function removeCellByPoint(PointEntity $pointEntity) {
		$this->validatePoint($pointEntity);
		$this->createCellEntity($pointEntity, BlankCellEntity::class);
	}
	
	/**
	 * @param PointEntity $pointEntity
	 *
	 * @return CellEntity
	 */
	private function getCellByPoint(PointEntity $pointEntity) {
		try {
			$this->validatePoint($pointEntity);
		} catch(\Exception $e) {
			return null;
		}
		return ArrayHelper::getValue($this->matrix, $pointEntity->x . DOT . $pointEntity->y, null);
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
		try {
			$cellEntity->matrix = $this;
		} catch(ReadOnlyException $e) {}
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
