<?php

namespace yii2lab\ai\game\helpers;

use yii2lab\ai\game\entities\UnitCellEntity;
use yii2lab\ai\game\factories\UnitFactory;

class Game {
	
	private $usleep;
	
	/**
	 * @var Matrix
	 */
	private $matrix;
	private $outputHandler;
	
	/**
	 * @var UnitCellEntity[]
	 */
	private $unitCollection;
	
	public function __construct($size) {
		$matrix = UnitFactory::createMatrix($size);
		UnitFactory::createWalls($matrix);
		UnitFactory::createFoods($matrix);
		UnitFactory::createToxic($matrix);
		$this->unitCollection = UnitFactory::createUnits($matrix);
		$this->matrix = $matrix;
	}
	
	public function run($speed = 1) {
		$this->usleep =  round(200000 / $speed);
		do {
			$this->tick();
		} while($this->hasUnits());
	}
	
	public function setOutputHandler($value) {
		$this->outputHandler = $value;
	}
	
	private function hasUnits() {
		return $this->getAllUnits() != null;
	}
	
	private function tick() {
		foreach($this->unitCollection as $unitEntity) {
			$this->stepUnit($unitEntity);
		}
		$this->runOutputHandler();
		usleep($this->usleep);
	}
	
	private function runOutputHandler() {
		$info = $this->getUnitsInfo();
		call_user_func($this->outputHandler, $this->matrix, $info);
	}
	
	private function getAllUnits() {
		$units = [];
		foreach($this->unitCollection as $unitEntity) {
			if(!$unitEntity->isDead()) {
				$units[] = $unitEntity;
			}
		}
		return $units;
	}
	
	private function getUnitsInfo() {
		$info = [];
		foreach($this->unitCollection as $k => $unitEntity) {
			if(!$unitEntity->isDead()) {
				$info[] = 'unit '.$k.': ' . $unitEntity->energy;
			}
		}
		return $info;
	}
	
	private function stepUnit(UnitCellEntity $unitEntity) {
		$wantCell = $unitEntity->wantCell();
		if($wantCell) {
			$unitEntity->matrix->moveCellEntity($unitEntity, $wantCell);
		}
	}
}
