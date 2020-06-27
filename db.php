<?php
$connection =null;
$dsn = 'mysql://hostbname=localhost;dbname=php_pdo2';
$username = 'root' ;
$password = '';
$message='';
try{
    $connection = new PDO($dsn,$username,$password);

}catch(PDOException $e ){
    echo " not database is emptoy";
}

//var_dump($connection->exec("SELECT * FROM employees"));