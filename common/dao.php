<?php
class Dao extends Base {
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
	private $queryGroups = array();
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
			$json = json_decode(file_get_contents(BASE_PATH . $this->getClassPath() . $this->getClassFile() . '.schema.json'),true);
			self::$jsons[get_class($this)] = $json;
		}
		return $json;
	}

	private function loadSchema() {
		if (get_class() == get_class($this)) {
			return;
		}
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
					return "`" . $key . "`" . " DATETIME";
				case 'updateDate':
					return "`" . $key . "`" . " DATETIME";
				default:
					return "`" . $key . "` " . strtoupper($val['type']) . ($val['size'] ? '(' . $val['size'] . ')' : '') . ($val['default'] ? ' DEFAULT(' . (is_numeric($val['default']) ? $val['default'] : "'" . $val['default'] . "'")  . ')' : '');
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
		$cnt = Db::select('INFORMATION_SCHEMA.TABLES',array('COUNT(*) cnt'),'TABLE_NAME = ? AND TABLE_SCHEMA = ?',array($this->tableName,DB_DATABASE));
		if ($cnt[0]['cnt'] > 0) {
			if (!TableVersionsDao::get()->isVersionEquals($this->tableName,array($this->columns,$this->indexes))) {
				$schemaInfo = Db::select('INFORMATION_SCHEMA.COLUMNS',array('COLUMN_NAME'),'TABLE_NAME = ?',array($this->tableName));
				$columns = array();
				foreach ($schemaInfo as $val) {
					if (array_key_exists($val['COLUMN_NAME'],$this->columns)) {
						$columns[] = $val['COLUMN_NAME'];
					}
				}
				Db::execute($this->getCreateSQL(true));
				if (count($columns) > 0) {
					Db::execute('INSERT INTO `temp_' . $this->tableName . '`(' . implode(',',$columns) . ') SELECT ' . implode(',',$columns) . ' FROM `' . $this->tableName . '`');
				}
				Db::execute('RENAME TABLE `' . $this->tableName . '` TO `old_' . $this->tableName . '`, `temp_' . $this->tableName . '` TO `' . $this->tableName . '`');
				Db::execute('DROP TABLE `old_' . $this->tableName . '`');
				TableVersionsDao::get()->updateVersion($this->tableName,array($this->columns,$this->indexes));
			}
		} else {
			Db::execute($this->getCreateSQL());
			$this->insertDefaultRecords();
			TableVersionsDao::get()->updateVersion($this->tableName,array($this->columns,$this->indexes));
		}
	}

	public function selectByKey($key) {
		return Db::select($this->tableName, $this->columns, implode(' AND ',array_map(function($val){
			return $val . " = ?";
		},$this->keyName)), array($key));
	}

	public function count() {
		return (int)$this->getOne('COUNT(*) cnt');
	}

	public function getOne($column) {
		$rec = $this->select($column);
		return $rec ? $rec[0][0] : null;
	}

	public function getRow() {
		$rec = call_user_func_array(array($this,'select'),func_get_args());
		return $rec ? $rec[0] : null;
	}

	public function select() {
		$columns = '';
		if (func_num_args() > 0) {
			$tableName = $this->tableName;
			$cols = $this->columns;
			$queryDaos = $this->queryDaos;
			$columns = array();
			foreach (func_get_args() as $val) {
				$dao = $this->searchColumnDao($val);
				if ($dao) {
					$columns[] = $dao->tableName . '.' . TextHelper::toSnakeCase($val);
				} else {
					$columns[] = $val;
				}
			}
		} else {
			$tableName = $this->tableName;
			$columns = array_map(function($column) use ($tableName) {
				return $tableName . '.' . $column;
			},$this->columns);
		}
		$tables = $this->tableName;
		if (count($this->queryTables) > 0) {
			$tables .= ' ' . implode(' ',$this->queryTables);
		}
		$where = '';
		if (count($this->queryWheres) > 0) {
			$where .= implode(' ',$this->queryWheres);
		} else {
			$where .= '1';
		}
		if (count($this->queryGroups) > 0) {
			$where .= ' GROUP BY ' . implode(',',$this->queryGroups);
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
		$errors = $this->validate($params);
		if ($errors) {
			die(var_dump($errors));
		}
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
		$errors = $this->validate($params);
		if (!$errors) {
			die(var_dump($errors));
		}
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
			$where .= implode(' ',$this->queryWheres);
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
			$where .= implode(' ',$this->queryWheres);
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
		if (strpos($name, 'equalTo') === 0 || strpos($name, 'orEqualTo') === 0) {
			if (preg_match('/^(or)?[eE]qualTo(.+)$/u',$name,$matches)) {
				$this->filter($matches[2],$arguments[0],'=',$matches[1]);
			}
		} else if (strpos($name, 'notEqualTo') === 0 || strpos($name, 'orNotEqualTo') === 0) {
			if (preg_match('/^(or)?[nN]otEqualTo(.+)$/u',$name,$matches)) {
				$this->filter($matches[2],$arguments[0],'!=',$matches[1]);
			}
		} else if (strpos($name, 'lessThan') === 0 || strpos($name, 'orLessThan') === 0) {
			if (preg_match('/^(or)?[lL]essThan(.+)$/u',$name,$matches)) {
				$this->filter($matches[2],$arguments[0],'>',$matches[1]);
			}
		} else if (strpos($name, 'moreThan') === 0 || strpos($name, 'orMoreThan') === 0) {
			if (preg_match('/^(or)?[mM]oreThan(.+)$/u',$name,$matches)) {
				$this->filter($matches[2],$arguments[0],'<',$matches[1]);
			}
		} else if (strpos($name, 'likeTo') === 0 || strpos($name, 'orLikeTo') === 0) {
			if (preg_match('/^(or)?[lL]ikeTo(.+)$/u',$name,$matches)) {
				$this->filter($matches[2],$arguments[0],'like',$matches[1]);
			}
		} else if (strpos($name, 'lessThanOrEqualTo') === 0 || strpos($name, 'orLessThanOrEqualTo') === 0) {
			if (preg_match('/^(or)?[lL]essThanOrEqualTo(.+)$/u',$name,$matches)) {
				$this->filter($matches[2],$arguments[0],'>=',$matches[1]);
			}
		} else if (strpos($name, 'moreThanOrEqualTo') === 0 || strpos($name, 'orMoreThanOrEqualTo') === 0) {
			if (preg_match('/^(or)?[mM]oreThanOrEqualTo(.+)$/u',$name,$matches)) {
				$this->filter($matches[2],$arguments[0],'<=',$matches[1]);
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
		} else if (strpos($name, 'groupBy') === 0) {
			if (preg_match('/^groupBy(.+)$/u',$name,$matches)) {
				$this->groupBy($matches[1], $arguments[0]);
			}
		} else if (strpos($name, 'limit') === 0) {
			$this->limit($arguments[0]);
		} else if (strpos($name, 'offset') === 0) {
			$this->offset($arguments[0]);
		}
		return $this;
	}

	private function searchColumnDao($column) {
		if (array_key_exists(TextHelper::toSnakeCase($column),$this->columns)) {
			return $this;
		} else {
			foreach ($this->queryDaos as $dao) {
				if (array_key_exists(TextHelper::toSnakeCase($column),$dao->columns)) {
					return $dao;
				}
			}
		}
	}

	private function filter($column, $value, $method = '=', $or = false) {
		$dao = $this->searchColumnDao($column);
		if ($dao) {
			$query = $dao->tableName . '.' . TextHelper::toSnakeCase($column) . ' ' . $method . ' ?';
			if (method_exists($dao, 'get' . TextHelper::toCamelCase($column) . 'Query')) {
				$value = call_user_func(array($dao, 'get' . TextHelper::toCamelCase($column) . 'Query'),$value);
			}
		} else {
			die('Column Not Found.');
		}
		$this->queryWheres[] = (count($this->queryWheres) > 0 ? ($or ? 'OR ' : 'AND ') : '') .$query;
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
		$dao = $this->searchColumnDao($column);
		if ($dao) {
			$query = $dao->tableName . '.' . TextHelper::toSnakeCase($column) . ($desc ? ' DESC' : '');
		} else {
			die($this->_('error.column_notfound',$column));
		}
		$this->queryOrders[] = $query;
	}

	private function groupBy($column) {
		$dao = $this->searchColumnDao($column);
		if ($dao) {
			$query = $dao->tableName . '.' . TextHelper::toSnakeCase($column);
		} else {
			die($this->_('error.column_notfound',$column));
		}
		$this->queryGroups[] = $query;
	}

	private function limit($limit) {
		$this->queryLimit = $limit;
	}

	private function offset($offset) {
		$this->queryOffset = $offset;
	}

	public function validate($params) {
		$errors = array();
		foreach ($this->columns as $key => $val) {
			if (method_exists($this, 'get' . TextHelper::toCamelCase($key) . 'Query')) {
				$params[$key] = call_user_func(array($this, 'get' . TextHelper::toCamelCase($key) . 'Query'),$params[$key]);
			}
			if ($val['required'] && !$val['default'] && $val['type'] != 'insertDate' && $val['type'] != 'updateDate' && $val['type'] != 'key' && !$val['autoincrement'] && !array_key_exists($key,$params)) {
				$errors[$key] = $this->_('error.column_required',$this->getColumnName($key));
				continue;
			}
			if (array_key_exists($key,$params)) {
				switch ($val['type']) {
					case 'int':
						if (!preg_match('/^-?[0-9]+$/',$params[$key])) {
							$errors[$key] = $this->_('error.number_invalid',$this->getColumnName($key));
						}
						continue 2;
					case 'date':
						if (!preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/',$params[$key],$matches) || !checkdate($matches[2],$matches[3],$matches[1])) {
							$errors[$key] = $this->_('error.date_invalid',$this->getColumnName($key));
						}
						continue 2;
					case 'datetime':
						if (!preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$/',$params[$key],$matches)
							 || !checkdate($matches[2],$matches[3],$matches[1])
							 || $matches[4] < 0 || $matches[4] > 23
							 || $matches[5] < 0 || $matches[5] > 59
							 || $matches[6] < 0 || $matches[6] > 59) {
							$errors[$key] = $this->_('error.datetime_invalid',$this->getColumnName($key));
						}
						continue 2;
				}
				if ($val['size'] && mb_strlen($params[$key]) > $val['size']) {
					$errors[$key] = $this->_('error.length_invalid',$this->getColumnName($key),$val['size']);
					continue;
				}
				if ($val['unique']) {
					$dao = self::get();
					call_user_func(array($dao,'equalTo' . TextHelper::toCamelCase($key)),$params[$key]);
					if ($dao->count()) {
						$errors[$key] = $this->_('error.not_unique',$this->getColumnName($key));
						continue;
					}
				}
				if (method_exists($this,'validate' . TextHelper::toCamelCase($key))) {
					$error = call_user_func(array($this,'validate' . TextHelper::toCamelCase($key)),$params[$key]);
					if ($error) {
						$errors[$key] = $error;
						continue;
					}
				}
			}
		}
		return count($errors) > 0 ? $errors : null;
	}

	public function getColumnName($column) {
		$dao = $this->searchColumnDao($column);
		if ($dao) {
			return $dao->_('columns.' . $column);
		}
		return $column;
	}

	public function insertDefaultRecords() {

	}

	final private function __construct() {

	}
}
