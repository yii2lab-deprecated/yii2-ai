<?php

namespace yii2lab\ai\game\entities;

class BlankCellEntity extends CellEntity {
	
	public function isCanReplace() {
		return true;
	}
	
}
