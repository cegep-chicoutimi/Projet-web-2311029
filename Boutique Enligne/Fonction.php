<?php
function GetMySQLiInstance()
{
	// Create MySQLi instance
		$mysqli = mysqli_init();

		if (!$mysqli) {
			die('mysqli_init failed');
		}

		// Set SSL options (similar to PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false)
		$mysqli->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);

		// Set SSL parameters (pass NULL for unused parameters)
		$mysqli->ssl_set(
			NULL,    // key
			NULL,    // cert
			NULL,    // ca
			NULL,    // capath
			NULL     // cipher
		);

		// Connect using real_connect (required when using ssl_set)
		if (!$mysqli->real_connect("sql.decinfo-cchic.ca", "dev-2311029", "Balde622@@", "h26_web_2311029","33306", NULL, MYSQLI_CLIENT_SSL)) {
			die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
		}

		// Set charset
		$mysqli->set_charset("utf8");
		return $mysqli;
}
?>