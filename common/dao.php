<?php
class Dao {
	private $tableName;
	private $columns;
	private $indexes;
	private $keyName;
	private $characterSet;
	private $engine;

	static $jsons = array();

	public static function get() {
		$dao = new static();
		$dao->loadSchema();
		return $dao;
	}

	private function loadJson() {
		if (self::$jsons[get_class($this)]) {
			return self::$jsons[get_class($this)];
		}
		self::$jsons[get_class($this)] = json_decode(file_get_contents(BASE_PATH . Loader::getClassPath(get_class($this)) . Loader::getClassFile(get_class($this)) . '.schema.json'),true);
		return self::$jsons[get_class($this)];
	}

	private function loadSchema() {
		$json = $this->loadJson();

		$this->tableName = TextHelper::toSnakeCase(Loader::getClassFile(get_class($this)));
		$this->columns = $json['columns'];
		if ($json['keyName']) {
			$this->keyName = $json['keyName'];
		}
		if ($json['indexes']) {
			$this->indexes = $json['indexes'];
		} else {
			$this->indexes = array();
		}
		if ($json['characterSet']) {
			$this->characterSet = $json['characterSet'];
		}
	}

	public function getColumnNames() {
		return array_map(function($val){return $this->tableName . '.' . $val;},array_keys($this->columns));
	}

	public function getCreateSQL() {
		static::loadSchema();

		$sql = " CREATE TABLE `" . $this->tableName . "` (";
		$sql .= implode(',',array_map(function($key,$val){
			switch ($val['type']) {
				case 'key':
					return "`" . $key . "`" . " INT(11) NOT NULL AUTO_INCREMENT";
				case 'insertDate':
					return "`" . $key . "`" . " TIMESTAMP NOT NULL DEFAULT 0";
				case 'updateDate':
					return "`" . $key . "`" . " TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
				default:
					return "`" . $key . "` " . strtoupper($val['type']) . ($val['size'] ? '(' . $val['size'] . ')' : '') . ($val['notnull'] ? ' NOT NULL' : '') . ($val['default'] ? ' DEFAULT(' . (is_numeric($val['default']) ? $val['default'] : "'" . $val['default'] . "'")  . ')' : '');
			}
		},array_keys($this->columns),$this->columns));
		if ($this->keyName) {
			$sql .= ", PRIMARY KEY(" . implode(',',array_map(function($val){return "`" . $val . "`";}, $this->keyName)) . ")";
		}
		if ($this->indexes) {
			foreach ($this->indexes as $key => $columns) {
				$sql .= ", INDEX `" . $key . "` (" . implode(',', $columns) . ")";
			}
		}
		$sql .= ") DEFAULT CHARACTER SET " . ($this->characterSet ? $this->characterSet : 'utf8') . ", TYPE=" . ($this->engine ? $this->engine : 'InnoDB') . " ";

		return $sql;
	}

	final private function __construct() {

	}
}