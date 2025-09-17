<?php
session_start();
//unsetting the whole variables
session_unset();

//destroying the sessions username and email set earlier
session_destroy();

//redirecting back to the default page
header("location:index.php");


?>