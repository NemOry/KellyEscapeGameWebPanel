<?php 

// ----------------------------------------- PHP MAILER  ------------------------------------------------------- \\

defined('EMAIL_PASS') ? null : 				define("EMAIL_PASS"					, "DhjkLmnOP2{}");
defined('EMAIL_ADDRESS') ? null : 			define("EMAIL_ADDRESS"				, "admin@kellyescape.com");

// ----------------------------------------- FACEBOOK PHP SDK  ------------------------------------------------------- \\

defined('APP_ID') ? null : 					define("APP_ID"						, "170997829744867");
defined('APP_SECRET') ? null : 				define("APP_SECRET"					, "d2cb18bdfb041ca4d1890dd68a6e17f6");

// ----------------------------------------- TWITTER SDK  ------------------------------------------------------- \\

defined('CONSUMER_KEY') ? null : 			define("CONSUMER_KEY"				, "bSR0sJHU2xovCHIjTw7A4A");
defined('CONSUMER_SECRET') ? null : 		define("CONSUMER_SECRET"			, "1zuPmNX1c0q6RAsOrDS0DAYquTlOqsf4iGKYg60YsPo");
defined('OAUTH_CALLBACK') ? null : 			define("OAUTH_CALLBACK"				, "http://kellyescape.com/public/functions/connecttwitter.php");

// ----------------------------------------- RECAPTCHA KEYS  ------------------------------------------------------- \\

defined('RECAPTCHA_PUBLIC') ? null : 		define("RECAPTCHA_PUBLIC"			, "6Lcgl-MSAAAAAF6J4o0d0rmrbx0cVc8nsyoT38XH");
defined('RECAPTCHA_PRIVATE') ? null : 		define("RECAPTCHA_PRIVATE"			, "6Lcgl-MSAAAAAHHYvj1hzBmO47kmsVxccvL52jo2");

// ----------------------------------------- USERS TABLE  ------------------------------------------------------- \\
defined('T_USERS') ? null :					define("T_USERS"					, "users");
defined('C_USER_ID') ? null : 				define("C_USER_ID"					, "id");
defined('C_USER_USERNAME') ? null : 		define("C_USER_USERNAME"			, "username");
defined('C_USER_PASSWORD') ? null : 		define("C_USER_PASSWORD"			, "password");
defined('C_USER_EMAIL') ? null : 			define("C_USER_EMAIL"				, "email");
defined('C_USER_LEVEL') ? null : 			define("C_USER_LEVEL"				, "level");
defined('C_USER_NAME') ? null : 			define("C_USER_NAME"				, "name");
defined('C_USER_ENABLED') ? null : 			define("C_USER_ENABLED"				, "enabled");
defined('C_USER_DATE') ? null :				define("C_USER_DATE"				, "date");
defined('C_USER_ADMIN') ? null :			define("C_USER_ADMIN"				, "admin");
defined('C_USER_LIVES') ? null : 			define("C_USER_LIVES"				, "lives");
defined('C_USER_COINS') ? null : 			define("C_USER_COINS"				, "coins");
defined('C_USER_BULLETS') ? null :			define("C_USER_BULLETS"				, "bullets");
defined('C_USER_SHIELDS') ? null :			define("C_USER_SHIELDS"				, "shields");
defined('C_USER_KILLS') ? null :			define("C_USER_KILLS"				, "kills");
defined('C_USER_POINTS') ? null :			define("C_USER_POINTS"				, "points");
defined('C_USER_SLOWMOS') ? null :			define("C_USER_SLOWMOS"				, "slowmos");
defined('C_USER_TOP_SCORE') ? null :		define("C_USER_TOP_SCORE"			, "top_score");
defined('C_USER_VOLUME') ? null :			define("C_USER_VOLUME"				, "volume");
defined('C_USER_CONTROL') ? null :			define("C_USER_CONTROL"				, "control");
defined('C_USER_LANGUAGE') ? null :			define("C_USER_LANGUAGE"			, "language");
defined('C_USER_OAUTH_PROVIDER') ? null :	define("C_USER_OAUTH_PROVIDER"		, "oauth_provider");
defined('C_USER_OAUTH_UID') ? null :		define("C_USER_OAUTH_UID"			, "oauth_uid");

// ----------------------------------------- ACHIEVEMENTS TABLE  ------------------------------------------------------- \\
defined('T_ACHIEVEMENTS') ? null :					define("T_ACHIEVEMENTS"				, "achievements");
defined('C_ACHIEVEMENT_ID') ? null : 				define("C_ACHIEVEMENT_ID"			, "id");
defined('C_ACHIEVEMENT_USER_ID') ? null : 			define("C_ACHIEVEMENT_USER_ID"		, "user_id");
defined('C_ACHIEVEMENT_LEVEL_ID') ? null : 			define("C_ACHIEVEMENT_LEVEL_ID"		, "level_id");
defined('C_ACHIEVEMENT_POINTS') ? null : 			define("C_ACHIEVEMENT_POINTS"		, "points");
defined('C_ACHIEVEMENT_COINS') ? null : 			define("C_ACHIEVEMENT_COINS"		, "coins");
defined('C_ACHIEVEMENT_LIVES') ? null : 			define("C_ACHIEVEMENT_LIVES"		, "lives");
defined('C_ACHIEVEMENT_BULLETS') ? null : 			define("C_ACHIEVEMENT_BULLETS"		, "bullets");
defined('C_ACHIEVEMENT_SHIELDS') ? null : 			define("C_ACHIEVEMENT_SHIELDS"		, "shields");
defined('C_ACHIEVEMENT_KILLS') ? null :				define("C_ACHIEVEMENT_KILLS"		, "kills");
defined('C_ACHIEVEMENT_SCORE') ? null :				define("C_ACHIEVEMENT_SCORE"		, "score");
defined('C_ACHIEVEMENT_STARS') ? null :				define("C_ACHIEVEMENT_STARS"		, "stars");

// ----------------------------------------- CODE TABLE  ------------------------------------------------------- \\
defined('T_CODES') ? null :							define("T_CODES"					, "codes");
defined('C_CODE_ID') ? null : 						define("C_CODE_ID"					, "id");
defined('C_CODE_USER_ID') ? null : 					define("C_CODE_USER_ID"				, "user_id");
defined('C_CODE_CODE') ? null : 					define("C_CODE_CODE"				, "code");
defined('C_CODE_MESSAGE') ? null : 					define("C_CODE_MESSAGE"				, "message");
defined('C_CODE_ITEM') ? null : 					define("C_CODE_ITEM"				, "item");
defined('C_CODE_VALUE') ? null : 					define("C_CODE_VALUE"				, "value");

// ----------------------------------------- REDEEMED CODE TABLE  ------------------------------------------------------- \\
defined('T_REDEEMED_CODES') ? null :				define("T_REDEEMED_CODES"			, "redeemed_codes");
defined('C_REDEEMED_CODE_ID') ? null : 				define("C_REDEEMED_CODE_ID"			, "id");
defined('C_REDEEMED_CODE_CODE_ID') ? null : 		define("C_REDEEMED_CODE_CODE_ID"	, "code_id");
defined('C_REDEEMED_CODE_USER_ID') ? null : 		define("C_REDEEMED_CODE_USER_ID"	, "user_id");

// ----------------------------------------- LOGS TABLE  ------------------------------------------------------- \\
defined('T_LOGS') ? null :							define("T_LOGS"						, "logs");
defined('C_LOGS_ID') ? null : 						define("C_LOGS_ID"					, "id");
defined('C_LOGS_USER_ID') ? null : 					define("C_LOGS_USER_ID"				, "user_id");
defined('C_LOGS_PLATFORM') ? null : 				define("C_LOGS_PLATFORM"			, "platform");
defined('C_LOGS_DATE') ? null : 					define("C_LOGS_DATE"				, "date");
defined('C_LOGS_TYPE') ? null : 					define("C_LOGS_TYPE"				, "type");

// ----------------------------------------- HITS TABLE  ------------------------------------------------------- \\
defined('T_HITS') ? null :							define("T_HITS"						, "hits");
defined('C_HITS_ID') ? null : 						define("C_HITS_ID"					, "id");
defined('C_HITS_NAME') ? null : 					define("C_HITS_NAME"				, "name");
defined('C_HITS_PLATFORM') ? null : 				define("C_HITS_PLATFORM"			, "platform");
defined('C_HITS_USER_ID') ? null : 					define("C_HITS_USER_ID"				, "user_id");
defined('C_HITS_DATE') ? null : 					define("C_HITS_DATE"				, "date");

?>