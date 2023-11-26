<?php

require_once './boot.php';

$_SESSION["user_id"] = null;
header('Location: /web-site/index.php');
