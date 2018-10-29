<?php

namespace yii2lab\ai\game\entities;

use yii2lab\ai\game\enums\ColorEnum;
use yii2lab\domain\exceptions\ReadOnlyException;

/**
 * Class UnitCellEntity
 *
 * @package yii2lab\ai\game\entities
 * @property $energy
 */
class UnitCellEntity extends CellEntity {
	
	private $energy;
	
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
		if($this->energy > 100) {
			return ColorEnum::CYAN;
		}
		return ColorEnum::BLUE;
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
		$this->energy = $this->getEnergy() + $step;
	}
	
	public function downEnergy($step = 1) {
		$this->energy = $this->getEnergy() - $step;
	}
	
	public function getEnergy() {
		return $this->energy;
	}
	
	public function setEnergy($value) {
		if(isset($this->energy)) {
			throw new ReadOnlyException('Energy attribute read only!');
		}
		$this->energy = $value;
	}
	
}
