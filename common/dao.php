<?php
class Dao {
	public $tableName;
	public $columns;
	public $indexes;
	public $keyName;
	public $characterSet;
	public $engine;

	private $queryWheres = array();
	private $queryWhereParams = array();
	private $queryTables = array();
	private $queryDaos = array();
	private $queryOrders = array();
	private $queryLimit;
	private $queryOffset;

	static $jsons = array();

	public static function get() {
		$dao = new static();
		$dao->loadSchema();

		return $dao;
	}

	private function loadJson() {
		if (self::$jsons[get_class($this)]) {
			$json = self::$jsons[get_class($this)];
		} else {
			$json = json_decode(file_get_contents(BASE_PATH . Loader::getClassPath(get_class($this)) . Loader::getClassFile(get_class($this)) . '.schema.json'),true);
			self::$jsons[get_class($this)] = $json;
		}
		return $json;
	}

	private function loadSchema() {
		$json = $this->loadJson();

		$this->tableName = TextHelper::toSnakeCase(preg_replace('/Dao$/','',get_class($this)));
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

	private function getCreateSQL($temp = false) {
		$sql = " CREATE TABLE `" . ($temp ? 'temp_' : '') . $this->tableName . "` (";
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

	public function initSchema() {
		$cnt = Db::select('INFORMATION_SCHEMA.TABLES',array('COUNT(*) cnt'),'TABLE_NAME = ?',array($this->tableName));
		if ($cnt[0]['cnt'] > 0) {
			$schemaInfo = Db::select('INFORMATION_SCHEMA.COLUMNS',array('COLUMN_NAME'),'TABLE_NAME = ?',array($this->tableName));
			$columns = array();
			foreach ($schemaInfo as $val) {
				if (array_key_exists($val['COLUMN_NAME'],$this->columns)) {
					$columns[] = $val['COLUMN_NAME'];
				}
			}
			Db::execute($this->getCreateSQL(true));
			if (count($columns) > 0) {
				Db::execute('INSERT INTO `temp_' . $this->tableName . '` SELECT ' . implode(',',$columns) . ' FROM `' . $this->tableName . '`');
			}
			Db::execute('RENAME TABLE `' . $this->tableName . '` TO `old_' . $this->tableName . '`, `temp_' . $this->tableName . '` TO `' . $this->tableName . '`');
			Db::execute('DROP TABLE `old_' . $this->tableName . '`');
		} else {
			Db::execute($this->getCreateSQL());
		}
	}

	public function selectByKey($key) {
		return Db::select($this->tableName, $this->columns, implode(' AND ',array_map(function($val){
			return $val . " = ?";
		},$this->keyName)), array($key));
	}

	public function select() {
		$columns = '';
		if (func_num_args() > 0) {
			$tableName = $this->tableName;
			$cols = $this->columns;
			$queryDaos = $this->queryDaos;
			$columns = array_map(function($column) use ($tableName,$cols,$queryDaos) {
				if (array_key_exists(TextHelper::toSnakeCase($column),$cols)) {
					return $tableName . '.' . TextHelper::toSnakeCase($column);
				} else {
					foreach ($queryDaos as $dao) {
						if (array_key_exists(TextHelper::toSnakeCase($column),$dao->columns)) {
							return $tableName . '.' . TextHelper::toSnakeCase($column);
						}
					}
				}
				return $column;
			},func_get_args());
		} else {
			$tableName = $this->tableName;
			$sql .= implode(',',array_map(function($column) use ($tableName) {
				return $tableName . '.' . $column;
			},$this->columns));
		}
		$tables = $this->tableName;
		if (count($this->queryTables) > 0) {
			$tables .= ' ' . implode(' ',$this->queryTables);
		}
		$where = '';
		if (count($this->queryWheres) > 0) {
			$where .= implode(' AND ',$this->queryWheres);
		}
		if (count($this->queryOrders) > 0) {
			$where .= ' ORDER BY ' . implode(',',$this->queryOrders);
		}
		if ($this->queryLimit) {
			$where .= ' LIMIT ' . (int)$this->queryLimit;
		}
		if ($this->queryLimit && $this->queryOffset) {
			$where .= ',' . (int)$this->queryOffset;
		}

		return Db::select($tables, $columns, $where, $this->queryWhereParams);
	}

	public function insert($params) {
		foreach ($this->columns as $key => $val) {
			if ($val['type'] == 'insertDate' || $val['type'] == 'updateDate') {
				$params[$key] = DateHelper::now();
			}
			if (method_exists($this, 'get' . TextHelper::toCamelCase($key) . 'Query')) {
				$params[$key] = call_user_func(array($this, 'get' . TextHelper::toCamelCase($key) . 'Query'),$params[$key]);
			}
		}
		Db::insert($this->tableName, $params);
	}

	public function update($params) {
		foreach ($this->columns as $key => $val) {
			if ($val['type'] == 'updateDate') {
				$params[$key] = DateHelper::now();
			}
			if (method_exists($this, 'get' . TextHelper::toCamelCase($key) . 'Query')) {
				$params[$key] = call_user_func(array($this, 'get' . TextHelper::toCamelCase($key) . 'Query'),$params[$key]);
			}
		}
		$where = '';
		if (count($this->queryWheres) > 0) {
			$where .= implode(' AND ',$this->queryWheres);
		}
		if ($this->queryLimit) {
			$where .= ' LIMIT ' . (int)$this->queryLimit;
		}
		if ($this->queryLimit && $this->queryOffset) {
			$where .= ',' . (int)$this->queryOffset;
		}
		Db::update($this->tableName, $params, $where, array_merge($params,$this->queryWhereParams));
	}

	public function delete() {
		$where = '';
		if (count($this->queryWheres) > 0) {
			$where .= implode(' AND ',$this->queryWheres);
		}
		if ($this->queryLimit) {
			$where .= ' LIMIT ' . (int)$this->queryLimit;
		}
		if ($this->queryLimit && $this->queryOffset) {
			$where .= ',' . (int)$this->queryOffset;
		}
		Db::delete($this->tableName, $where, $this->queryWhereParams);
	}

	public function deleteByKey($key) {
		return Db::delete($this->tableName, $this->columns, implode(' AND ',array_map(function($val){
			return $val . " = ?";
		},$this->keyName)), array($key));
	}

	public function __call($name, $arguments) {
		if (strpos($name, 'equalTo') === 0) {
			if (preg_match('/^equalTo(.+)$/u',$name,$matches)) {
				$this->filter($matches[1],$arguments[0]);
			}
		} else if (strpos($name, 'notEqualTo') === 0) {
			if (preg_match('/^notEqualTo(.+)$/u',$name,$matches)) {
				$this->filter($matches[1],$arguments[0],'!=');
			}
		} else if (strpos($name, 'lessThan') === 0) {
			if (preg_match('/^lessThan(.+)$/u',$name,$matches)) {
				$this->filter($matches[1],$arguments[0],'>');
			}
		} else if (strpos($name, 'moreThan') === 0) {
			if (preg_match('/^moreThan(.+)$/u',$name,$matches)) {
				$this->filter($matches[1],$arguments[0],'<');
			}
		} else if (strpos($name, 'likeTo') === 0) {
			if (preg_match('/^likeTo(.+)$/u',$name,$matches)) {
				$this->filter($matches[1],$arguments[0],'like');
			}
		} else if (strpos($name, 'lessThanOrEqualTo') === 0) {
			if (preg_match('/^lessThanOrEqualTo(.+)$/u',$name,$matches)) {
				$this->filter($matches[1],$arguments[0],'>=');
			}
		} else if (strpos($name, 'moreThanOrEqualTo') === 0) {
			if (preg_match('/^moreThanOrEqualTo(.+)$/u',$name,$matches)) {
				$this->filter($matches[1],$arguments[0],'<=');
			}
		} else if (strpos($name, 'join') === 0) {
			if (preg_match('/^join(.+)On(.+)$/u',$name,$matches)) {
				$this->join($matches[1], $matches[2], $arguments[0]);
			}
		} else if (strpos($name, 'leftJoin') === 0) {
			if (preg_match('/^leftJoin(.+)On(.+)$/u',$name,$matches)) {
				$this->join($matches[1], $matches[2], $arguments[0], true);
			}
		} else if (strpos($name, 'orderBy') === 0) {
			if (preg_match('/^orderBy(.+)$/u',$name,$matches)) {
				$this->orderBy($matches[1], $arguments[0]);
			}
		} else if (strpos($name, 'limit') === 0) {
			$this->limit($arguments[0]);
		} else if (strpos($name, 'offset') === 0) {
			$this->offset($arguments[0]);
		}
		return $this;
	}

	private function filter($column, $value, $method = '=') {
		if (array_key_exists(TextHelper::toSnakeCase($column),$this->columns)) {
			$query = $this->tableName . '.' . TextHelper::toSnakeCase($column) . ' ' . $method . ' ?';
			if (method_exists($this, 'get' . TextHelper::toCamelCase($column) . 'Query')) {
				$value = call_user_func(array($this, 'get' . TextHelper::toCamelCase($column) . 'Query'),$value);
			}
		} else {
			foreach ($this->queryDaos as $dao) {
				if (array_key_exists(TextHelper::toSnakeCase($column),$dao->columns)) {
					$query = $dao->tableName . '.' . TextHelper::toSnakeCase($column) . ' ' . $method . ' ?';
					if (method_exists($dao, 'get' . TextHelper::toCamelCase($column) . 'Query')) {
						$value = call_user_func(array($dao, 'get' . TextHelper::toCamelCase($column) . 'Query'),$value);
					}
					break;
				}
			}
		}
		if (!$query) {
			die('Column Not Found.');
		}
		$this->queryWheres[] = $query;
		$this->queryWhereParams[] = $value;
	}

	private function join($table, $key, $column, $leftJoin = false) {
		$dao = call_user_func($table. 'Dao::get');
		$query = ($leftJoin ? 'LEFT ' : '') . 'JOIN ';
		$query .= $dao->tableName . ' ON (';
		if ($key == 'Key') {
			$keyName = $dao->keyName[0];
		} else {
			$keyName = TextHelper::toSnakeCase($key);
		}
		$query .= $this->tableName . '.' . $column . ' = ' . $dao->tableName . '.' . $keyName;
		$query .= ')';
		$this->queryTables[] = $query;
		$this->queryDaos[] = $dao;
	}

	private function orderBy($column, $desc = false) {
		if (array_key_exists($this->columns,TextHelper::toSnakeCase($column))) {
			$query = $this->tableName . '.' . TextHelper::toSnakeCase($column) . ($desc ? ' DESC' : '');
		} else {
			foreach ($this->queryDaos as $dao) {
				if (array_key_exists($dao->columns,TextHelper::toSnakeCase($column))) {
					$query = $dao->tableName . '.' . TextHelper::toSnakeCase($column) . ($desc ? ' DESC' : '');
					break;
				}
			}
		}
		if (!$query) {
			die('Column Not Found.');
		}
		$this->queryOrders[] = $query;
	}

	private function limit($limit) {
		$this->queryLimit = $limit;
	}

	private function offset($offset) {
		$this->queryOffset = $offset;
	}

	final private function __construct() {

	}
}
