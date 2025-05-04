<?php
     $dsn = 'mysql:host=localhost;dbname=lovenish_sampledb';
     $username = 'lovenish_lovenish';
     $password = 'si_6AKTqVBaCY@9';
     
     $options = [
         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
     ];
     
     try {
         $pdo = new PDO($dsn, $username, $password, $options);
     } catch (PDOException $e) {
         die("Database connection failed " . $e->getMessage());
     }
?>