<?php
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_USER', 'infost490f2406_infost490f2406');
DEFINE ('DB_PASSWORD', 'Thebestcapstonegroup');
DEFINE ('DB_NAME', 'infost490f2406_Test_DB');

$dbc = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die ('Could not connect to MySQL server with error: ' . mysqli_connect_error());