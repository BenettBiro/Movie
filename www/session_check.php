<?php
session_start();

if(empty($_SESSION['user_id']))
    {
  echo "je bent niet ingelogd";
 exit;
    }