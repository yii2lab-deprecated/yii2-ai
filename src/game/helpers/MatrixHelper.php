<?php

namespace yii2lab\ai\game\helpers;

use Closure;
use yii2lab\ai\game\entities\CellEntity;
use yii2lab\extension\console\helpers\Output;

class MatrixHelper {
	
	public static function getPossibles($map) {
		$possibles = [];
		/** @var CellEntity[][] $map */
		foreach($map as $line) {
			foreach($line as $cell) {
				if($cell != null && $cell->isCanReplace()) {
					$possibles[] = $cell;
				}
			}
		}
		return $possibles;
	}
	
	public static function fillMatrix(Matrix $matrix, Closure $closure) {
		/** @var CellEntity[][] $matrix */
		foreach($matrix->getMatrix() as $x => $line) {
			foreach($line as $y => $cellEntity) {
				$closure($x, $y, $cellEntity);
			}
		}
	}
	
	public static function generateMatrix(Matrix $matrix) {
		$lineArr = [];
		/** @var CellEntity[][] $matrix */
		foreach($matrix->getMatrix() as $line) {
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