<?php



class Employee extends Abstractmodel
{
    public $id;
    public $name;
    public $age;
    public $address;
    public $tax;
    public $salary;


    protected  static  $tableName = 'employees';
    protected  static  $tableSchema =array(
        'name'        =>self::DATA_TYPE_STR,
        'age'         => self::DATA_TYPE_INT,
        'address'     => self::DATA_TYPE_STR,
        'salary'      =>self::DATA_TYPE_DECIMAL,
        'tax'         =>self::DATA_TYPE_DECIMAL

    ) ;

    protected  static  $primaryKey = 'id';

    public function __construct($name,$age ,$address,$tax,$salary)
    {
        global $connection;

        $this->name = $name;
        $this->age = $age;
        $this->address = $address;
        $this->salary = $salary;
        $this->tax = $tax;


    }
    public function __get($prop){
        return $this->$prop;

}

    public function setName($name){
        $this->name = $name;

}

public function  calculatesalary(){
		
		return $this->salary -($this->salary * $this->tax /100);
		}
	
public function gettableName (){
        return self::$tableName;
}




 
}