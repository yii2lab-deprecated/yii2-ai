<?php

namespace yii2lab\ai\game\helpers;

use Closure;
use yii\base\BaseObject;
use yii2lab\ai\game\entities\CellEntity;
use yii2lab\ai\game\events\MoveEvent;
use yii2lab\ai\game\scenario\step\IsPossibleScenario;
use yii2lab\extension\console\helpers\Output;
use yii2lab\extension\scenario\collections\ScenarioCollection;
use yii2lab\extension\scenario\exceptions\StopException;

class MatrixHelper {
	
	private static $possibleStepFilters = [
		[
			'class' => IsPossibleScenario::class,
		],
	];
	
	private static function runScenarios(BaseObject $event, array $filters) {
		$filterCollection = new ScenarioCollection($filters);
		$filterCollection->event = $event;
		try {
			$filterCollection->runAll();
			return true;
		} catch(StopException $e) {
			return false;
		}
	}
	
	public static function isPossible(CellEntity $cell = null) {
		if($cell == null) {
			return false;
		}
		$event = new MoveEvent();
		$event->toCellEntity = $cell;
		$isCanReplace = self::runScenarios($event, self::$possibleStepFilters);
		if(!$isCanReplace) {
			return false;
		}
		return true;
	}
	
	public static function getPossibles($map) {
		$possibles = [];
		/** @var CellEntity[][] $map */
		foreach($map as $line) {
			foreach($line as $cell) {
				if(self::isPossible($cell)) {
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