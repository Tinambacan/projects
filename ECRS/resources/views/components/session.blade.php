<?php
session_start();

if (empty($_SESSION['loginID'])) {
    header('');
}
?>
