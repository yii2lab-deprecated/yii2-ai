<?php

namespace yii2lab\ai\game\helpers;

use Closure;
use yii2lab\ai\game\entities\unit\BaseUnitEntity;
use yii2lab\extension\console\helpers\Output;

class MatrixHelper {
	
	public static function fillMatrix(Matrix $matrix, Closure $closure) {
		/** @var BaseUnitEntity[][] $matrix */
		foreach($matrix->getMatrix() as $x => $line) {
			foreach($line as $y => $BaseUnitEntity) {
				$closure($x, $y, $BaseUnitEntity);
			}
		}
	}
	
	public static function generateMatrix(Matrix $matrix) {
		$lineArr = [];
		/** @var BaseUnitEntity[][] $matrix */
		foreach($matrix->getMatrix() as $line) {
			$line1 = [];
			foreach($line as $BaseUnitEntity) {
				$content = $BaseUnitEntity->content;
				$content .= str_repeat(SPC, 2 - strlen($content));
				$line1[] = Output::wrap($content, $BaseUnitEntity->color);
			}
			$lineArr[] = $line1;
		}
		$text = Output::generateArray($lineArr);
		return $text;
	}
	
}