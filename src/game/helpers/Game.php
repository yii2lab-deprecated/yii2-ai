<?php

namespace yii2lab\ai\game\helpers;

use yii2lab\ai\game\entities\unit\BotEntity;
use yii2lab\ai\game\factories\UnitFactory;
use yii2lab\ai\game\interfaces\RenderInterface;
use yii2lab\extension\common\helpers\ClassHelper;

class Game {
	
	private $usleep;
	
	/**
	 * @var Matrix
	 */
	private $matrix;
	
	/**
	 * @var RenderInterface
	 */
	private $render;
	
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
	
	public function setRender($definition) {
		$this->render = ClassHelper::createObject($definition, [], RenderInterface::class);
	}
	
	private function hasUnits() {
		return $this->getAllUnits() != null;
	}
	
	private function tick() {
		foreach($this->unitCollection as $botEntity) {
			$botEntity->step();
		}
		$this->runOutputHandler();
		usleep($this->usleep);
	}
	
	private function runOutputHandler() {
		$this->render->render($this->matrix->getMatrix());
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
	
}
