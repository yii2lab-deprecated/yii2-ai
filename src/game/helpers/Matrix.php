<?php

namespace yii2lab\ai\game\helpers;

use yii2lab\ai\game\entities\unit\BlankEntity;
use yii2lab\ai\game\entities\unit\BaseUnitEntity;
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
	 * @var BaseUnitEntity[][]
	 */
	private $matrix = [];
	private $height;
	private $width;
	
	public function __construct($height, $width, BaseUnitEntity $BlankEntity = null) {
		$this->height = $height;
		$this->width = $width;
		$this->createMatrix($height, $width, $BlankEntity);
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
	
	private function onMove(BotEntity $fromBaseUnitEntity, BaseUnitEntity $toBaseUnitEntity) {
		$filters = [
			EnergyScenario::class,
		];
		$filterCollection = new ScenarioCollection($filters);
		$event = new MoveEvent;
		$event->fromBaseUnitEntity = $fromBaseUnitEntity;
		$event->toBaseUnitEntity = $toBaseUnitEntity;
		$filterCollection->event = $event;
		$filterCollection->runAll();
	}
	
	public function moveBaseUnitEntity(BotEntity $BaseUnitEntity, PointEntity $toPointEntity) {
		$fromPointEntity = clone $BaseUnitEntity->point;
		$toBaseUnitEntity = $this->getCellByPoint($toPointEntity);
		$this->onMove($BaseUnitEntity, $toBaseUnitEntity);
		$this->setCellByPoint($toPointEntity, $BaseUnitEntity);
		$this->removeCellByPoint($fromPointEntity);
	}
	
	public function getPossibleCollection(PointEntity $pointEntity) {
		$map = $this->getCellsByPoint($pointEntity);
		$possibles = PossibleHelper::getPossibles($map);
		return $possibles;
	}
	
	public function setCellByPoint(PointEntity $pointEntity, BaseUnitEntity $BaseUnitEntity = null) {
		$this->validatePoint($pointEntity);
		$this->forgeBaseUnitEntity($BaseUnitEntity, $pointEntity);
		$BaseUnitEntity->point = clone $pointEntity;
		$BaseUnitEntity->validate();
		$this->matrix[ $pointEntity->x ][ $pointEntity->y ] = $BaseUnitEntity;
	}
	
	private function getCellsByPoint(PointEntity $pointEntity, $size = 1) {
		$beginX = $pointEntity->x - $size;
		$endX = $pointEntity->x + $size;
		$beginY = $pointEntity->y - $size;
		$endY = $pointEntity->y + $size;
		/** @var BaseUnitEntity[][] $res */
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
		$this->createBaseUnitEntity($pointEntity, BlankEntity::class);
	}
	
	/**
	 * @param PointEntity $pointEntity
	 *
	 * @return BaseUnitEntity
	 */
	private function getCellByPoint(PointEntity $pointEntity) {
		try {
			$this->validatePoint($pointEntity);
		} catch(\Exception $e) {
			return null;
		}
		return ArrayHelper::getValue($this->matrix, $pointEntity->x . DOT . $pointEntity->y, null);
	}
	
	private function createMatrix(int $h, int $v, BaseUnitEntity $BlankEntity = null) {
		$BlankEntityMain = $BlankEntity instanceof BaseUnitEntity ? $BlankEntity : new BlankEntity;
		$pointEntity = new PointEntity;
		for($x = 1; $x <= $h; $x++) {
			for($y = 1; $y <= $v; $y++) {
				$pointEntity->x = $x;
				$pointEntity->y = $y;
				$BlankEntity = clone $BlankEntityMain;
				$this->forgeBaseUnitEntity($BlankEntity, $pointEntity);
				$this->setCellByPoint($pointEntity, $BlankEntity);
			}
		}
	}
	
	private function forgeBaseUnitEntity(BaseUnitEntity $BaseUnitEntity, PointEntity $pointEntity) {
		try {
			$BaseUnitEntity->matrix = $this;
		} catch(ReadOnlyException $e) {}
		$BaseUnitEntity->point = clone $pointEntity;
		$BaseUnitEntity->validate();
	}
	
	private function createBaseUnitEntity(PointEntity $pointEntity, $className) {
		/** @var BaseUnitEntity $BaseUnitEntity */
		$BaseUnitEntity = new $className;
		$this->setCellByPoint($pointEntity, $BaseUnitEntity);
		return $BaseUnitEntity;
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
