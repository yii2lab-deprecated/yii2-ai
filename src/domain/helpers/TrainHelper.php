<?php

namespace yii2lab\ai\domain\helpers;

use Phpml\Classification\KNearestNeighbors;
use yii\helpers\ArrayHelper;

class TrainHelper {
	
	
	public static function getBlankMatrix($size) {
		$arr = [];
		for($x = 1; $x <= $size; $x++) {
			for($y = 1; $y <= $size; $y++) {
				$arr[$x-1][$y-1] = null;
			}
		}
		return $arr;
	}
	
	public static function getSourceMatrix($arr, $rr) {
		$samples = ArrayHelper::getColumn($rr, 'sample');
		$labels = ArrayHelper::getColumn($rr, 'label');
		foreach($samples as $index => $point) {
			$x = $point[0];
			$y = $point[1];
			$arr[$x-1][$y-1] = $labels[$index];
		}
		return $arr;
	}
	
	public static function getResultMatrix($arr, $size, KNearestNeighbors $classifier) {
		for($x = 1; $x <= $size; $x++) {
			for($y = 1; $y <= $size; $y++) {
				$arr[$x-1][$y-1] = $classifier->predict([$x, $y]);
			}
		}
		return $arr;
	}
	
	public static function trainMatrix($rr) {
		$classifier = new KNearestNeighbors();
		$samples = ArrayHelper::getColumn($rr, 'sample');
		$labels = ArrayHelper::getColumn($rr, 'label');
		$classifier->train($samples, $labels);
		return $classifier;
	}
	
}
