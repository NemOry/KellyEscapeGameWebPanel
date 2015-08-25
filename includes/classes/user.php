<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class User extends DatabaseObject
{
	protected static $table_name = T_USERS;
	protected static $col_id = C_USER_ID;

	public $id;
	public $username;
	public $password;

	public $oauth_provider;
	public $oauth_uid;

	public $email;
	public $level;
	public $name;
	public $enabled;
	public $date;
	public $admin;

	public $lives;
	public $coins;
	public $bullets;
	public $shields;
	public $kills;
	public $points;
	public $slowmos;
	public $top_score;
	public $volume;
	public $language;
	public $control;

	public function create()
	{
		global $db;
		$sql = "INSERT INTO " . self::$table_name . " (";
		$sql .= C_USER_USERNAME 		.", ";
		$sql .= C_USER_PASSWORD 		.", ";
		$sql .= C_USER_OAUTH_PROVIDER 	.", ";
		$sql .= C_USER_OAUTH_UID 		.", ";
		$sql .= C_USER_EMAIL 			.", ";
		$sql .= C_USER_LEVEL 			.", ";
		$sql .= C_USER_NAME 			.", ";
		$sql .= C_USER_ENABLED 			.", ";
		$sql .= C_USER_DATE 			.", ";
		$sql .= C_USER_LIVES 			.", ";
		$sql .= C_USER_COINS 			.", ";
		$sql .= C_USER_BULLETS 			.", ";
		$sql .= C_USER_SHIELDS 			.", ";
		$sql .= C_USER_KILLS 			.", ";
		$sql .= C_USER_POINTS 			.", ";
		$sql .= C_USER_SLOWMOS 			.", ";
		$sql .= C_USER_TOP_SCORE		.", ";
		$sql .= C_USER_VOLUME 			.", ";
		$sql .= C_USER_CONTROL			.", ";
		$sql .= C_USER_LANGUAGE			.", ";
		$sql .= C_USER_ADMIN;
		$sql .=") VALUES ('";
		$sql .= $db->escape_string($this->username) 		. "', '";
		$sql .= $db->escape_string($this->password) 		. "', '";
		$sql .= $db->escape_string($this->oauth_provider) 	. "', '";
		$sql .= $db->escape_string($this->oauth_uid) 		. "', '";
		$sql .= $db->escape_string($this->email) 			. "', ";
		$sql .= $db->escape_string($this->level) 			. ", '";
		$sql .= $db->escape_string($this->name) 			. "', ";
		$sql .= $db->escape_string($this->enabled) 			. ", ";
		$sql .= "NOW()" 									. ", ";
		$sql .= $db->escape_string($this->lives) 			. ", ";
		$sql .= $db->escape_string($this->coins) 			. ", ";
		$sql .= $db->escape_string($this->bullets) 			. ", ";
		$sql .= $db->escape_string($this->shields) 			. ", ";
		$sql .= $db->escape_string($this->kills) 			. ", ";
		$sql .= $db->escape_string($this->points) 			. ", ";
		$sql .= $db->escape_string($this->slowmos) 			. ", ";
		$sql .= $db->escape_string($this->top_score) 		. ", ";
		$sql .= $db->escape_string($this->volume) 			. ", ";
		$sql .= $db->escape_string($this->control) 			. ", ";
		$sql .= $db->escape_string($this->language) 		. ", ";
		$sql .= $db->escape_string($this->admin) 			. " ";
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
		$sql .= C_USER_USERNAME 		. "='" . $db->escape_string($this->username) 		. "', ";
		$sql .= C_USER_PASSWORD			. "='" . $db->escape_string($this->password) 		. "', ";
		$sql .= C_USER_OAUTH_PROVIDER 	. "='" . $db->escape_string($this->oauth_provider) 	. "', ";
		$sql .= C_USER_OAUTH_UID		. "='" . $db->escape_string($this->oauth_uid) 		. "', ";
		$sql .= C_USER_EMAIL 			. "='" . $db->escape_string($this->email) 			. "', ";
		$sql .= C_USER_LEVEL 			. "=" . $db->escape_string($this->level) 			. ", ";
		$sql .= C_USER_NAME 			. "='" . $db->escape_string($this->name) 			. "', ";
		$sql .= C_USER_ENABLED 			. "=" . $db->escape_string($this->enabled) 			. ", ";
		$sql .= C_USER_DATE 			. "=" . "NOW()" 									. ", ";
		$sql .= C_USER_LIVES 			. "=" . $db->escape_string($this->lives) 			. ", ";
		$sql .= C_USER_COINS 			. "=" . $db->escape_string($this->coins) 			. ", ";
		$sql .= C_USER_BULLETS 			. "=" . $db->escape_string($this->bullets) 			. ", ";
		$sql .= C_USER_SHIELDS 			. "=" . $db->escape_string($this->shields) 			. ", ";
		$sql .= C_USER_KILLS 			. "=" . $db->escape_string($this->kills) 			. ", ";
		$sql .= C_USER_POINTS 			. "=" . $db->escape_string($this->points) 			. ", ";
		$sql .= C_USER_SLOWMOS 			. "=" . $db->escape_string($this->slowmos) 			. ", ";
		$sql .= C_USER_TOP_SCORE 		. "=" . $db->escape_string($this->top_score) 		. ", ";
		$sql .= C_USER_VOLUME 			. "=" . $db->escape_string($this->volume) 			. ", ";
		$sql .= C_USER_CONTROL 			. "=" . $db->escape_string($this->control) 			. ", ";
		$sql .= C_USER_LANGUAGE 		. "=" . $db->escape_string($this->language) 		. ", ";
		$sql .= C_USER_ADMIN 			. "=" . $db->escape_string($this->admin) 			. " ";
		$sql .="WHERE " . self::$col_id . "=" . $db->escape_string($this->id) 				. "";
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
		$this_class->id 				= $record[C_USER_ID];
		$this_class->username 			= $record[C_USER_USERNAME];
		$this_class->password 			= $record[C_USER_PASSWORD];
		$this_class->oauth_provider 	= $record[C_USER_OAUTH_PROVIDER];
		$this_class->oauth_uid 			= $record[C_USER_OAUTH_UID];
		$this_class->email 				= $record[C_USER_EMAIL];
		$this_class->level 				= $record[C_USER_LEVEL];
		$this_class->name 				= $record[C_USER_NAME];
		$this_class->enabled 			= $record[C_USER_ENABLED];
		$this_class->date 				= $record[C_USER_DATE];
		$this_class->admin				= $record[C_USER_ADMIN];
		$this_class->lives 				= $record[C_USER_LIVES];
		$this_class->coins 				= $record[C_USER_COINS];
		$this_class->bullets 			= $record[C_USER_BULLETS];
		$this_class->kills 				= $record[C_USER_KILLS];
		$this_class->points 			= $record[C_USER_POINTS];
		$this_class->shields			= $record[C_USER_SHIELDS];
		$this_class->slowmos			= $record[C_USER_SLOWMOS];
		$this_class->top_score			= $record[C_USER_TOP_SCORE];
		$this_class->volume				= $record[C_USER_VOLUME];
		$this_class->control			= $record[C_USER_CONTROL];
		$this_class->language			= $record[C_USER_LANGUAGE];
		return $this_class;
	}

	public static function username_exists($username)
	{
		global $db;
		$username = $db->escape_string($username);
		$sql = "SELECT * FROM " . self::$table_name . " WHERE " . C_USER_USERNAME . " = '" . $username . "'";
		$result = $db->query($sql);
		return ($db->get_num_rows($result) == 1) ? true : false;
	}

	public static function email_exists($email)
	{
		if($email != "")
		{
			global $db;
			$email = $db->escape_string($email);
			$sql = "SELECT * FROM " . self::$table_name . " WHERE " . C_USER_EMAIL . " = '" . $email . "'";
			$result = $db->query($sql);
			return ($db->get_num_rows($result) == 1) ? true : false;
		}
		else
		{
			return false;
		}
	}

	public static function authenticate($paramUsername="", $paramPassword="")
	{
		global $db;
		$paramUsername= $db->escape_string($paramUsername);
		$paramPassword= $db->escape_string($paramPassword);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_USER_USERNAME . " = '" . $paramUsername. "'";
		$sql .= " AND " 	. C_USER_PASSWORD . " = '" . $paramPassword. "'";
		$sql .= " LIMIT 1";
		
		$result = $db->query($sql);
		return ($db->get_num_rows($result) == 1) ? true : false;
	}

	public static function login($username="", $password="")
	{
		global $db;
		$username 	= $db->escape_string($username);
		$password 	= $db->escape_string($password);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_USER_USERNAME . " = '" . $username . "'";
		$sql .= " AND " 	. C_USER_PASSWORD . " = '" . $password . "'";
		$sql .= " LIMIT 1";
		
		$result = self::get_by_sql($sql);
		return !empty($result) ? array_shift($result) : null;
	}

	public static function get_by_username($username="")
	{
		global $db;
		$username = $db->escape_string($username);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_USER_USERNAME . " = '" . $username . "'";
		$sql .= " LIMIT 1";
		
		$result_array = self::get_by_sql($sql);

		return !empty($result_array) ? $result_array : false;
	}

	public static function get_by_oauthid($oauthid="")
	{
		global $db;
		$oauthid = $db->escape_string($oauthid);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE " 	. C_USER_OAUTH_UID . " = '" . $oauthid . "'";
		$sql .= " LIMIT 1";
		
		$result_array = self::get_by_sql($sql);

		return !empty($result_array) ? array_shift($result_array) : false;
	}
}
?>