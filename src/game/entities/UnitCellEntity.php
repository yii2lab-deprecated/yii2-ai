<?php

namespace yii2lab\ai\game\entities;

use yii2lab\ai\game\enums\ColorEnum;

/**
 * Class UnitCellEntity
 *
 * @package yii2lab\ai\game\entities
 * @property-read $energy
 */
class UnitCellEntity extends CellEntity {
	
	protected $color = ColorEnum::BLUE;
	private $energy = 10;
	
	/**
	 * @var Object
	 */
	private $logicInstance;
	
	public function setLogicInstance($logicClass) {
		$this->logicInstance = \Yii::createObject($logicClass);
	}
	
	public function isCanReplace() {
		return false;
	}
	
	public function getColor() {
		if($this->isDead()) {
			return ColorEnum::BLACK;
		}
		return $this->color;
	}
	
	public function getContent() {
		if($this->isDead()) {
			return 'xx';
		}
		return '..';
	}
	
	/**
	 * @return PointEntity|boolean
	 */
	public function wantCell() {
		if($this->isDead()) {
			return false;
		}
		return $this->logicInstance->getPoint($this);
	}
	
	public function isDead() {
		return $this->getEnergy() <= 0;
	}
	
	public function upEnergy($step = 1) {
		$energy = $this->getEnergy() + $step;
		$this->setEnergy($energy);
	}
	
	public function downEnergy($step = 1) {
		$energy = $this->getEnergy() - $step;
		$this->setEnergy($energy);
	}
	
	public function getEnergy() {
		return $this->energy;
	}
	
	private function setEnergy($value) {
		$this->energy = $value;
		//$this->content = $value;
		/*if($this->energy == 0) {
			//$this->kill('Low energy');
		}*/
		/*if($this->energy == 20) {
			$this->kill('Higth energy');
		}*/
	}
	
	/*private function kill($message = null) {
		//$this->matrix->removeCellByPoint($this->point);
		//$this->color = ColorEnum::CYAN;
		//$this->matrix = null;
		//throw new DeadUnitException($message);
	}
	
	public function step($dir, $count = 1) {
		if($dir == self::DIR_UP) {
			$this->moveUp($count);
		} elseif($dir == self::DIR_LEFT) {
			$this->moveLeft($count);
		} elseif($dir == self::DIR_DOWN) {
			$this->moveDown($count);
		} elseif($dir == self::DIR_RIGHT) {
			$this->moveRight($count);
		}
	}
	
	private function moveUp($stepCount = 1) {
		$toPointEntity = clone $this->point;
		$toPointEntity->x = $toPointEntity->x - $stepCount;
		$this->matrix->moveCellEntity($this, $toPointEntity);
	}
	
	private function moveDown($stepCount = 1) {
		$toPointEntity = clone $this->point;
		$toPointEntity->x = $toPointEntity->x + $stepCount;
		$this->matrix->moveCellEntity($this, $toPointEntity);
	}
	
	private function moveLeft($stepCount = 1) {
		$toPointEntity = clone $this->point;
		$toPointEntity->y = $toPointEntity->y - $stepCount;
		$this->matrix->moveCellEntity($this, $toPointEntity);
	}
	
	private function moveRight($stepCount = 1) {
		$toPointEntity = clone $this->point;
		$toPointEntity->y = $toPointEntity->y + $stepCount;
		$this->matrix->moveCellEntity($this, $toPointEntity);
	}*/
	
}
