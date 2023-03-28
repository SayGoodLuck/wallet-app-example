<?php

$db_host = "localhost";
$db_port = "5432";
$db_user = "postgres";
$db_password = "1234";
$db_name = "wallet";

$connection_string = "host={$db_host} port={$db_port} dbname={$db_name} user={$db_user} password={$db_password}";

$connection = pg_connect($connection_string);
