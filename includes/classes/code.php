<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class Code extends DatabaseObject
{
	protected static $table_name = T_CODES;
	protected static $col_id = C_CODE_ID;

	public $id;
	public $user_id;
	public $code;
	public $message;
	public $item;
	public $value;

	public function create()
	{
		global $db;
		$sql = "INSERT INTO " . self::$table_name . " (";
		$sql .= C_CODE_USER_ID 		.", ";
		$sql .= C_CODE_CODE			.", ";
		$sql .= C_CODE_MESSAGE 		.", ";
		$sql .= C_CODE_ITEM			.", ";
		$sql .= C_CODE_VALUE;
		$sql .=") VALUES (";
		$sql .= $db->escape_string($this->user_id) 		. ", ";
		$sql .= $db->escape_string($this->code) 		. "', '";
		$sql .= $db->escape_string($this->message) 		. "', '";
		$sql .= $db->escape_string($this->item) 		. "', '";
		$sql .= $db->escape_string($this->value) 		. "' ";
		$sql .=")";

		if($db->query($sql))
		{
			$this->id = $db->get_last_id();
			return true;
		}
		else
		{
			return false;	
		}
	}
	
	public function update()
	{
		global $db;
		$sql = "UPDATE " 			. self::$table_name . " SET ";
		$sql .= C_CODE_USER_ID 		. "=" . $db->escape_string($this->user_id) 			. ", ";
		$sql .= C_CODE_CODE 		. "='" . $db->escape_string($this->code) 			. "', ";
		$sql .= C_CODE_MESSAGE 		. "='" . $db->escape_string($this->message) 		. "', ";
		$sql .= C_CODE_LEVEL 		. "='" . $db->escape_string($this->item) 			. "', ";
		$sql .= C_CODE_ITEM 		. "='" . $db->escape_string($this->value) 			. "' ";
		$sql .="WHERE " . self::$col_id . "=" . $db->escape_string($this->id) 			. "";
		$db->query($sql);
		return ($db->get_affected_rows() == 1) ? true : false;
	}
	
	public function delete()
	{
		global $db;
		$sql = "DELETE FROM " . self::$table_name . " WHERE " . self::$col_id . "=" . $this->id . "";
		$db->query($sql);
		return ($db->get_affected_rows() == 1) ? true : false;
	}
	
	protected static function instantiate($record)
	{
		$this_class = new self;
		$this_class->id 				= $record[C_CODE_ID];
		$this_class->user_id 			= $record[C_CODE_USER_ID];
		$this_class->code 				= $record[C_CODE_CODE];
		$this_class->message 			= $record[C_CODE_MESSAGE];
		$this_class->item 				= $record[C_CODE_ITEM];
		$this_class->value 				= $record[C_CODE_VALUE];
		return $this_class;
	}

	public static function code_exists($code)
	{
		if($code != "")
		{
			global $db;
			$code = $db->escape_string($code);
			$sql = "SELECT * FROM " . self::$table_name . " WHERE " . C_CODE_CODE . " = '" . $code . "'";
			$result = $db->query($sql);
			return ($db->get_num_rows($result) == 1) ? true : false;
		}
		else
		{
			return false;
		}
	}

	public static function get_by_code($code="")
	{
		global $db;
		$code = $db->escape_string($code);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_CODE_CODE . " = '" . $code . "'";
		$sql .= " LIMIT 1";
		
		$result_array = self::get_by_sql($sql);

		return !empty($result_array) ? array_shift($result_array) : false;
	}
}


?>