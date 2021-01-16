<?php
    session_start(); 
    unset($_SESSION['pkURes']);      
	header('location: ../index.php');