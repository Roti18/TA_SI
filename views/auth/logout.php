<?php
require "config/helpers.php";
session_unset();
session_destroy();

redirect('login');

?>