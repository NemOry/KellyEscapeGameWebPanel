<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class Logs extends DatabaseObject
{
	protected static $table_name = T_LOGS;
	protected static $col_id = C_LOGS_ID;

	public $id;
	public $user_id;
	public $platform;
	public $date;
	public $type;

	public function create()
	{
		global $db;
		$sql = "INSERT INTO " . self::$table_name . " (";
		$sql .= C_LOGS_USER_ID 		.", ";
		$sql .= C_LOGS_PLATFORM		.", ";
		$sql .= C_LOGS_DATE 		.", ";
		$sql .= C_LOGS_TYPE;
		$sql .=") VALUES (";
		$sql .= $db->escape_string($this->user_id) 		. ", '";
		$sql .= $db->escape_string($this->platform) 	. "', ";
		$sql .= "NOW()" 								. ", '";
		$sql .= $db->escape_string($this->type) 		. "' ";
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
		$sql .= C_LOGS_USER_ID 		. "=" . $db->escape_string($this->user_id) 			. ", ";
		$sql .= C_LOGS_PLATFORM 	. "='" . $db->escape_string($this->platform) 		. "', ";
		$sql .= C_LOGS_DATE 		. "="  . "NOW()" 									. ", ";
		$sql .= C_LOGS_TYPE 		. "='" . $db->escape_string($this->value) 			. "' ";
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
		$this_class->id 				= $record[C_LOGS_ID];
		$this_class->user_id 			= $record[C_LOGS_USER_ID];
		$this_class->platform 			= $record[C_LOGS_PLATFORM];
		$this_class->date 				= $record[C_LOGS_DATE];
		$this_class->type 				= $record[C_LOGS_TYPE];
		return $this_class;
	}
}

?>