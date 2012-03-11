<?php
class TableVersionsDao extends Dao {
	public function isVersionEquals($tableName,$columns) {
		$cnt = $this->equalToTableName($tableName)->equalToVersion($columns)->count();
		return $cnt > 0;
	}

	public function updateVersion($tableName,$columns) {
		$cnt = $this->equalToTableName($tableName)->count();
		if ($cnt > 0) {
			$this->update(array('version' => $columns),'table_name = ?',array($tableName));
		} else {
			$this->insert(array('version' => $columns,'table_name' => $tableName));
		}
	}

	public function getVersionQuery($columns) {
		return md5(serialize($columns));
	}
}