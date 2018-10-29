<?php

namespace yii2lab\ai\console\commands;

use yii2lab\ai\game\interfaces\RenderInterface;
use yii2lab\extension\console\helpers\Output;

class ConsoleRender implements RenderInterface {
	
	public function render(array $map) {
		$text = self::generateMatrix($map);
		Output::render($text);
	}
	
	public static function generateMatrix(array $map) {
		$lineArr = [];
		foreach($map as $line) {
			$line1 = [];
			foreach($line as $cellEntity) {
				$content = $cellEntity->content;
				$content .= str_repeat(SPC, 2 - strlen($content));
				$line1[] = Output::wrap($content, $cellEntity->color);
			}
			$lineArr[] = $line1;
		}
		$text = Output::generateArray($lineArr);
		return $text;
	}
	
}
