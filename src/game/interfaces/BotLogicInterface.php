<?php

namespace yii2lab\ai\game\interfaces;

use yii2lab\ai\game\entities\unit\BotEntity;

interface BotLogicInterface {
	
	public function getPoint(BotEntity $botEntity);
	
}