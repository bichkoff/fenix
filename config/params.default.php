<?php
/**
 * MVC project.
 * Parameters to keep secret: Db connection, Mail credentials etc.
 * 
 * USAGE. Rename this file into params.php and set proper const values.
 *
 * @author Jvb 20 Jan 2015
 * @version 0.2 09.07.2015
 *
 */

\define("MYSQLHOST", 'localhost');
\define("MYSQLUSERNAME", '');
\define("MYSQLPASS", '');
\define("MYSQLDB", '');
\define("MYSQLCHARSET", 'UTF8');
\define("ROOTPATH", ''); // local usage; set to empty when on production server

\define("USERNAME", ''); // fenix server
\define("PASSWORD", '');

\define("COOKIE_FILE", ''); // full path to file (initial login page load); must be emptied before start
\define("TOURINDEX_LOGIN", '');
\define("TOURINDEX_PASS", '');
