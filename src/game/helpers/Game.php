<?php

namespace yii2lab\ai\game\helpers;

use yii2lab\ai\game\entities\unit\BotEntity;
use yii2lab\ai\game\factories\UnitFactory;

class Game {
	
	private $usleep;
	
	/**
	 * @var Matrix
	 */
	private $matrix;
	private $outputHandler;
	
	/**
	 * @var BotEntity[]
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
		foreach($this->unitCollection as $botEntity) {
			$this->stepUnit($botEntity);
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
		foreach($this->unitCollection as $botEntity) {
			if(!$botEntity->isDead()) {
				$units[] = $botEntity;
			}
		}
		return $units;
	}
	
	private function getUnitsInfo() {
		$info = [];
		foreach($this->unitCollection as $k => $botEntity) {
			if(!$botEntity->isDead()) {
				$info[] = 'unit '.$k.': ' . $botEntity->energy;
			}
		}
		return $info;
	}
	
	private function stepUnit(BotEntity $botEntity) {
		$wantCell = $botEntity->wantCell();
		if($wantCell) {
			$botEntity->matrix->moveCellEntity($botEntity, $wantCell);
		}
	}
}
