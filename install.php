<?php
require 'functions.php';
require 'config.php';

$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "CREATE TABLE IF NOT EXISTS `textpad` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `gen_uid` varchar(50) NOT NULL,
      `last_updated` date NOT NULL,
      `user_text` text NOT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `gen_uid` (`gen_uid`)
  )";
$q = $pdo->prepare($sql);
$q->execute();
Database::disconnect();
