<?php

$sql = "CREATE TABLE `products` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `sku` varchar(100) NOT NULL,
    `name` varchar(100) NOT NULL,
    `price` decimal(10,0) NOT NULL,
    `attribute` json NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`sku`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci";
