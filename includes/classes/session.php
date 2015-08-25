<?php 

class Session
{
	private $logged_in;
	public $user_id;
	public $user_admin;
	
	function __construct()
	{
		session_start();
		$this->check_login();
	}

	private function check_login()
	{
		if(isset($_SESSION[C_USER_ID]) && isset($_SESSION[C_USER_ADMIN]))
		{
			$this->user_id 			= $_SESSION[C_USER_ID];
			$this->user_admin 		= $_SESSION[C_USER_ADMIN];
			$this->logged_in 		= true;
		}
		else
		{
			unset($this->user_id);
			unset($this->user_admin);
			$this->logged_in = false;
		}
	}
	
	public function is_logged_in()
	{
		return $this->logged_in;
	}
	
	public function login($user)
	{
		if($user)
		{
			$this->user_id 		= $_SESSION[C_USER_ID] 		= $user->id;
			$this->user_admin 	= $_SESSION[C_USER_ADMIN] 	= $user->admin;
			$this->check_login();
		}
	}
	
	public function logout()
	{
		unset($_SESSION[C_USER_ID]);
		unset($_SESSION[C_USER_ADMIN]);
		unset($this->user_id);
		unset($this->user_admin);
		$this->logged_in = false;
	}
}

$session = new Session();

?>