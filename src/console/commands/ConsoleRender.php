<?php

namespace yii2lab\ai\console\commands;

use yii2lab\ai\game\entities\unit\BotEntity;
use yii2lab\ai\game\entities\unit\CellEntity;
use yii2lab\ai\game\entities\unit\FoodEntity;
use yii2lab\ai\game\entities\unit\ToxicEntity;
use yii2lab\ai\game\entities\unit\WallEntity;
use yii2lab\ai\game\enums\ColorEnum;
use yii2lab\ai\game\interfaces\RenderInterface;
use yii2lab\extension\console\helpers\Output;

class ConsoleRender implements RenderInterface {
	
	public function render(array $map) {
		$text = $this->generateMatrix($map);
		Output::render($text);
	}
	
	public function generateMatrix(array $map) {
		$lineArr = [];
		foreach($map as $line) {
			$line1 = [];
			foreach($line as $cellEntity) {
				$line1[] = $this->renderUnit($cellEntity);
			}
			$lineArr[] = $line1;
		}
		$text = Output::generateArray($lineArr);
		return $text;
	}
	
	private function renderUnit(CellEntity $cellEntity) {
		$content = SPC;
		
		if($cellEntity instanceof BotEntity) {
			if($cellEntity->isDead()) {
				$content = 'xx';
				$color = ColorEnum::BLACK;
			} else {
				$content = '..';
				$color = ColorEnum::BLUE;
				if($cellEntity->energy > 100) {
					$color = ColorEnum::CYAN;
				}
			}
		}
		
		if($cellEntity instanceof WallEntity) {
			$color = ColorEnum::YELLOW;
		}
		
		if($cellEntity instanceof FoodEntity) {
			$color = ColorEnum::GREEN;
		}
		
		if($cellEntity instanceof ToxicEntity) {
			$color = ColorEnum::RED;
		}
		
		$content .= str_repeat(SPC, 2 - strlen($content));
		return Output::wrap($content, $color);
	}
	
}
