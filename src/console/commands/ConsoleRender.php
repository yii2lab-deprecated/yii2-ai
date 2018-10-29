<?php

namespace yii2lab\ai\console\commands;

use yii2lab\ai\game\entities\unit\BotEntity;
use yii2lab\ai\game\entities\unit\BaseUnitEntity;
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
			foreach($line as $BaseUnitEntity) {
				$line1[] = $this->renderUnit($BaseUnitEntity);
			}
			$lineArr[] = $line1;
		}
		$text = Output::generateArray($lineArr);
		return $text;
	}
	
	private function renderUnit(BaseUnitEntity $BaseUnitEntity) {
		$content = SPC;
		
		if($BaseUnitEntity instanceof BotEntity) {
			if($BaseUnitEntity->isDead()) {
				$content = 'xx';
				$color = ColorEnum::BLACK;
			} else {
				$content = '..';
				$color = ColorEnum::BLUE;
				if($BaseUnitEntity->energy > 100) {
					$color = ColorEnum::CYAN;
				}
			}
		}
		
		if($BaseUnitEntity instanceof WallEntity) {
			$color = ColorEnum::YELLOW;
		}
		
		if($BaseUnitEntity instanceof FoodEntity) {
			$color = ColorEnum::GREEN;
		}
		
		if($BaseUnitEntity instanceof ToxicEntity) {
			$color = ColorEnum::RED;
		}
		
		$content .= str_repeat(SPC, 2 - strlen($content));
		return Output::wrap($content, $color);
	}
	
}
