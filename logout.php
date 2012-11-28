<?php
session_start(); 
session_unset();
session_destroy();
echo "Bye!";
header( 'Location: https://www.killthelandline.com' ) ;


?>