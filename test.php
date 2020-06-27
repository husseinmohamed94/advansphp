<?php

require_once 'db.php';
require_once 'abstractmodel.php' ;
require_once 'employee.php ';

$emps = Employee::get(

    'select name ,age  , salary from employees WHERE  age =:age',
    array(
'age' =>array(Employee::DATA_TYPE_INT, 24)

    )
);
 var_dump($emps);