<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class RedeemedCode extends DatabaseObject
{
	protected static $table_name = T_REDEEMED_CODES;
	protected static $col_id = C_REDEEMED_CODE_ID;

	public $id;
	public $code_id;
	public $user_id;

	public function create()
	{
		global $db;
		$sql = "INSERT INTO " . self::$table_name . " (";
		$sql .= C_REDEEMED_CODE_CODE_ID 		.", ";
		$sql .= C_REDEEMED_CODE_USER_ID;
		$sql .=") VALUES (";
		$sql .= $db->escape_string($this->code_id) 		. ", ";
		$sql .= $db->escape_string($this->user_id) 		. " ";
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
		$sql = "UPDATE " 				. self::$table_name . " SET ";
		$sql .= C_REDEEMED_CODE_CODE_ID 		. "=" . $db->escape_string($this->code_id) 			. ", ";
		$sql .= C_REDEEMED_CODE_USER_ID 		. "=" . $db->escape_string($this->user_id) 			. " ";
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
		$this_class->id 				= $record[C_REDEEMED_CODE_ID];
		$this_class->code_id 			= $record[C_REDEEMED_CODE_CODE_ID];
		$this_class->user_id 			= $record[C_REDEEMED_CODE_USER_ID];
		return $this_class;
	}

	public static function exists($code_id="", $user_id="")
	{
		global $db;
		$code_id= $db->escape_string($code_id);
		$user_id= $db->escape_string($user_id);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_REDEEMED_CODE_CODE_ID . " = '" . $code_id. "'";
		$sql .= " AND " 	. C_REDEEMED_CODE_USER_ID . " = '" . $user_id. "'";
		$sql .= " LIMIT 1";
		
		$result = $db->query($sql);
		return ($db->get_num_rows($result) == 1) ? true : false;
	}
}

?>