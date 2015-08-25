<?php 

require_once(INCLUDES_PATH.DS."config.php");
require_once(CLASSES_PATH.DS."database.php");

class Achievement extends DatabaseObject
{
	protected static $table_name = T_ACHIEVEMENTS;
	protected static $col_id = C_ACHIEVEMENT_ID;

	public $id;
	public $user_id;
	public $level_id;
	public $points;
	public $coins;
	public $lives;
	public $bullets;
	public $shields;
	public $kills;
	public $score;
	public $stars;
	
	public function create()
	{
		global $db;
		$sql = "INSERT INTO " . self::$table_name . " (";
		$sql .= C_ACHIEVEMENT_USER_ID 			.", ";
		$sql .= C_ACHIEVEMENT_LEVEL_ID 			.", ";
		$sql .= C_ACHIEVEMENT_POINTS 			.", ";
		$sql .= C_ACHIEVEMENT_COINS 			.", ";
		$sql .= C_ACHIEVEMENT_LIVES 			.", ";
		$sql .= C_ACHIEVEMENT_BULLETS 			.", ";
		$sql .= C_ACHIEVEMENT_SHIELDS 			.", ";
		$sql .= C_ACHIEVEMENT_KILLS 			.", ";
		$sql .= C_ACHIEVEMENT_SCORE 			.", ";
		$sql .= C_ACHIEVEMENT_STARS;
		$sql .=") VALUES (";
		$sql .= $db->escape_string($this->user_id) 			. ", ";
		$sql .= $db->escape_string($this->level_id) 		. ", ";
		$sql .= $db->escape_string($this->points) 			. ", ";
		$sql .= $db->escape_string($this->coins) 			. ", ";
		$sql .= $db->escape_string($this->lives) 			. ", ";
		$sql .= $db->escape_string($this->bullets) 			. ", ";
		$sql .= $db->escape_string($this->shields) 			. ", ";
		$sql .= $db->escape_string($this->kills) 			. ", ";
		$sql .= $db->escape_string($this->score) 			. ", ";
		$sql .= $db->escape_string($this->stars) 			. " ";
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
		$sql = "UPDATE " 						. self::$table_name . " SET ";
		$sql .= C_ACHIEVEMENT_USER_ID 			. "=" . $db->escape_string($this->user_id) 			. ", ";
		$sql .= C_ACHIEVEMENT_LEVEL_ID			. "=" . $db->escape_string($this->level_id) 		. ", ";
		$sql .= C_ACHIEVEMENT_EMAIL 			. "=" . $db->escape_string($this->email) 			. ", ";
		$sql .= C_ACHIEVEMENT_POINTS 			. "=" . $db->escape_string($this->points) 			. ", ";
		$sql .= C_ACHIEVEMENT_COINS 			. "=" . $db->escape_string($this->coins) 			. ", ";
		$sql .= C_ACHIEVEMENT_LIVES 			. "=" . $db->escape_string($this->lives) 			. ", ";
		$sql .= C_ACHIEVEMENT_BULLETS 			. "=" . $db->escape_string($this->bullets) 			. ", ";
		$sql .= C_ACHIEVEMENT_SHIELDS 			. "=" . $db->escape_string($this->shields) 			. ", ";
		$sql .= C_ACHIEVEMENT_KILLS 			. "=" . $db->escape_string($this->kills) 			. ", ";
		$sql .= C_ACHIEVEMENT_SCORE 			. "=" . $db->escape_string($this->score) 			. ", ";
		$sql .= C_ACHIEVEMENT_STARS 			. "=" . $db->escape_string($this->stars) 			. " ";
		$sql .="WHERE " . self::$col_id . "=" . $db->escape_string($this->id) 						. "";
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
		$this_class->id 				= $record[C_ACHIEVEMENT_ID];
		$this_class->user_id 			= $record[C_ACHIEVEMENT_USER_ID];
		$this_class->level_id 			= $record[C_ACHIEVEMENT_LEVEL_ID];
		$this_class->email 				= $record[C_ACHIEVEMENT_EMAIL];
		$this_class->points 			= $record[C_ACHIEVEMENT_POINTS];
		$this_class->coins 				= $record[C_ACHIEVEMENT_COINS];
		$this_class->lives 				= $record[C_ACHIEVEMENT_LIVES];
		$this_class->bullets 			= $record[C_ACHIEVEMENT_BULLETS];
		$this_class->shields			= $record[C_ACHIEVEMENT_SHIELDS];
		$this_class->kills 				= $record[C_ACHIEVEMENT_KILLS];
		$this_class->score 				= $record[C_ACHIEVEMENT_SCORE];
		$this_class->stars				= $record[C_ACHIEVEMENT_STARS];

		return $this_class;
	}
}

?>