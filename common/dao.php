<?php
class Dao {
	private $tableName;
	private $columns;
	private $indexes;
	private $keyName;
	private $characterSet;
	private $engine;

	static $instances = array();

	public static function get() {
		if (self::$instances[get_class($this)]) {
			$dao = self::$instances[get_class($this)];
		} else {
			$dao = new static();
			$dao->loadSchema();
			self::$instances[get_class($this)] = $dao;
		}

		return $dao;
	}

	private function loadJson() {
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
		return array_map(function($val){
			return $this->tableName . '.' . $val;
		},array_keys($this->columns));
	}

	public function getCreateSQL() {
		static::loadSchema();

		$sql = " CREATE TABLE `" . $this->tableName . "` (";
		$sql .= implode(',',array_map(function($key,$val){
			switch ($val['type']) {
				case 'key':
					return "`" . $key . "`" . " INT(11) NOT NULL AUTO_INCREMENT";
				case 'insertDate':
					return "`" . $key . "`" . " DATETIME NOT NULL";
				case 'updateDate':
					return "`" . $key . "`" . " DATETIME NOT NULL";
				default:
					return "`" . $key . "` " . strtoupper($val['type']) . ($val['size'] ? '(' . $val['size'] . ')' : '') . ($val['notnull'] ? ' NOT NULL' : '') . ($val['default'] ? ' DEFAULT(' . (is_numeric($val['default']) ? $val['default'] : "'" . $val['default'] . "'")  . ')' : '');
			}
		},array_keys($this->columns),$this->columns));
		if ($this->keyName) {
			$sql .= ", PRIMARY KEY(" . implode(',',array_map(function($val){
				return "`" . $val . "`";
			}, $this->keyName)) . ")";
		}
		if ($this->indexes) {
			foreach ($this->indexes as $key => $columns) {
				$sql .= ", INDEX `" . $key . "` (" . implode(',', $columns) . ")";
			}
		}
		$sql .= ") DEFAULT CHARACTER SET " . ($this->characterSet ? $this->characterSet : 'utf8') . ", TYPE=" . ($this->engine ? $this->engine : 'InnoDB') . " ";

		return $sql;
	}

	public function selectByKey($key) {
		return Db::select($this->tableName, $this->columns, implode(' AND ',array_map(function($val){
			return $val . " = ?";
		},$this->keyName)), array($key));
	}

	public function select($where = '1', $whereParams = array()) {
		return Db::select($this->tableName, $this->columns, $where, $whereParams);
	}

	public function insert($params) {
		foreach ($this->columns as $key => $val) {
			if ($val['type'] == 'insertDate' || $val['type'] == 'updateDate') {
				$params[$key] = DateHelper::now();
			}
		}
		Db::insert($this->tableName, $params);
	}

	public function update($params, $where, $whereParams = array()) {
		foreach ($this->columns as $key => $val) {
			if ($val['type'] == 'updateDate') {
				$params[$key] = DateHelper::now();
			}
		}
		Db::update($this->tableName, $params, $where, $whereParams);
	}

	public function delete($where, $whereParams = array()) {
		Db::delete($this->tableName, $where, $whereParams);
	}

	public function deleteByKey($key) {
		return Db::delete($this->tableName, $this->columns, implode(' AND ',array_map(function($val){
			return $val . " = ?";
		},$this->keyName)), array($key));
	}

	final private function __construct() {

	}
}
