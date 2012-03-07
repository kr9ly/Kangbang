<?php

Library::load('3rdparty/adodb5/adodb.inc');

class Db {
	private static $dbConn;

	private static function init() {
		if (!self::$dbConn) {
			$dsn = DB_TYPE . '://' . DB_USERNAME . ':' . rawurlencode(DB_PASSWORD) . '@' . rawurlencode(DB_SERVER) . '/' . DB_DATABASE;
			self::$dbConn = NewADOConnection($dsn);
		}
	}

	public static function execute($sql,$paramArray = array()) {
		self::init();
		self::$dbConn->Execute($sql, $paramArray);
	}

	public static function query($sql,$paramArray = array()) {
		self::init();
		return self::$dbConn->GetAll($sql,$paramArray);
	}

	public static function begin() {
		self::init();
		self::$dbConn->StartTrans();
	}

	public static function commit() {
		self::init();
		self::$dbConn->CompleteTrans();
	}

	public static function rollback() {
		self::init();
		self::$dbConn->FailTrans();
	}

	public static function select($table, $columns, $where, $whereParams = array(), $option = "") {
		return self::query("SELECT " . implode(',', $columns) . " FROM " . $table . " WHERE " . $where . " " . $option,$whereParams);
	}

	public static function insert($table, $params) {
		self::execute("INSERT INTO " .$table . " (" . implode(',',array_keys($params)) . ") VALUES (" . implode(',',array_map(function ($val) {return '?';}, $params)) . ") ",$params);
	}

	public static function update($table, $params, $where, $whereParams = array(), $option = "") {
		self::execute("UPDATE " . $table . " SET " . implode(',',array_map(function($key){return $key . " = ?";},array_keys($params))) . " WHERE " . $where . " " . $option,array_merge($params,$whereParams));
	}

	public static function delete($table, $where, $whereParams = array()) {
		self::execute("DELETE FROM " . $table . " WHERE " . $where, array());
	}

	public static function getInsertId() {
		return self::$dbConn->Insert_ID();
	}
}