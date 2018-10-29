<?php

namespace yii2lab\ai\game\interfaces;

use yii2lab\ai\game\entities\unit\BotEntity;
use yii2lab\domain\data\EntityCollection;

/**
 * Interface BotLogicInterface
 *
 * @package yii2lab\ai\game\interfaces
 * @property BotEntity $bot
 */
interface BotLogicInterface {
	
	public function getPoint(EntityCollection $possibles);
	public function setBot(BotEntity $botEntity);
	
}