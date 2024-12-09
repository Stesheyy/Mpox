<?php
session_start();
session_destroy();

header("Location: /M-pox/login.html"); // Redirect to login page after logging out
exit();
?>
