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
abstract class BaseEnergyEntity extends BaseUnitEntity {
	
	const EVENT_BEFORE_CHANGE_ENERGY = 'EVENT_BEFORE_CHANGE_ENERGY';
	const EVENT_AFTER_CHANGE_ENERGY = 'EVENT_AFTER_CHANGE_ENERGY';
	
	private $energy;
	
	public function isDead() {
		return $this->getEnergy() <= 0;
	}
	
	public function kill() {
		$this->setEnergyNative(0);
	}

	public function upEnergy($step = 1) {
		$value = $this->getEnergy() + $step;
		$this->setEnergyNative($value);
	}
	
	public function downEnergy($step = 1) {
		$value = $this->getEnergy() - $step;
		$this->setEnergyNative($value);
	}
	
	public function getEnergy() {
		return $this->energy;
	}
	
	protected function setEnergyNative($value) {
		$this->beforeChangeEnergyTrigger();
		$this->energy = $value;
		$this->afterChangeEnergyTrigger();
	}
	
	public function setEnergy($value) {
		if(isset($this->energy)) {
			throw new ReadOnlyException('Energy attribute read only!');
		}
		$this->setEnergyNative($value);
	}
	
	public function beforeChangeEnergyTrigger() {
		$this->trigger(self::EVENT_BEFORE_CHANGE_ENERGY);
	}
	
	public function afterChangeEnergyTrigger() {
		$this->trigger(self::EVENT_AFTER_CHANGE_ENERGY);
	}
	
}
