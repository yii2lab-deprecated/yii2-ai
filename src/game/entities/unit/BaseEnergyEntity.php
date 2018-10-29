<?php

namespace yii2lab\ai\game\entities\unit;

use yii2lab\domain\exceptions\ReadOnlyException;

/**
 * Class BaseEnergyEntity
 *
 * @package yii2lab\ai\game\entities\unit
 *
 * @property $energy
 */
class BaseEnergyEntity extends CellEntity {
	
	private $energy;
	
	public function isDead() {
		return $this->getEnergy() <= 0;
	}
	
	public function kill() {
		return $this->energy = 0;
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
