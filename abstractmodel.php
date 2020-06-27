<?php


class Abstractmodel
{
    const DATA_TYPE_BOOL  = PDO::PARAM_BOOL;
    const DATA_TYPE_STR  =  PDO::PARAM_STR;
    const DATA_TYPE_INT  =   PDO::PARAM_INT;
    const DATA_TYPE_DECIMAL= 4;

      private    function  prepareValus (PDOStatement &$stmt ) {

          foreach (static::$tableSchema as $columName => $type ){

              if ($type == 4){
                  $sanitizedvalue =filter_var($this->$columName, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                  $stmt->bindValue(":{$columName}",$sanitizedvalue);
                   }else{
                  $stmt->bindValue( ":{$columName}",$this->$columName, $type);
              }
          }

    }

    private static function  buildNameParameterSQl(){

        $namedParams = '';
        foreach(static::$tableSchema as $columName => $type) {
            $namedParams .= $columName . ' = :' . $columName . ',';
        }
        return trim($namedParams ,',');
        }


    private function create(){
        global $connection;
        $sql  =  'INSERT INTO ' . static::$tableName . ' SET ' . self::buildNameParameterSQl();
        $stmt = $connection->prepare($sql);
        $this->prepareValus($stmt);
       return $stmt->execute();

}
private  function  UPdate(){
        global $connection;
  $sql= ' UPDATE ' . static::$tableName . ' SET ' . self::buildNameParameterSQl(). ' WHERE ' . static::$primaryKey .'=' . $this->{static::$primaryKey};
  
 $stmt = $connection->prepare($sql);
        $this->prepareValus($stmt);
        return $stmt->execute();
      }
	  
public function save(){
	return $this->{static::$primaryKey} === null ? $this->create() : $this->UPdate() ;
	
	}

    public  function   delete(){
        global $connection;
     $sql  = 'DELETE FROM ' . static::$tableName .' WHERE ' . static::$primaryKey . '=' . $this->{static::$primaryKey};
     $stmt = $connection->prepare($sql);
        return $stmt->execute();

    }

    public static function getAll(){
    global  $connection;
	
    $sql =' select * from ' . static::$tableName;
        $stmt = $connection->prepare($sql);
        $stmt->execute() ;
        $results = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE , get_called_class()   ,array_keys(static::$tableSchema));
         return (is_array($results) && !empty($results)) ? $results : false ;

 
}

public static function getBypk($pk){

	 global  $connection;
	 
    $sql =' select * from ' . static::$tableName .'  WHERE ' . static::$primaryKey . '="' . $pk .'"';
    $stmt = $connection->prepare($sql);
  if($stmt->execute() === true )
  {
	$obj= $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE , get_called_class() ,array_keys(static::$tableSchema));
 	return array_shift($obj);

  }
  return false;
	}

	public  static function get($sql,$options = array() ){
          global $connection;
        $stmt = $connection->prepare($sql);
        if(!empty($options)){
            foreach ($options as $columName => $type ){

                if ($type[0] == 4){
                    $sanitizedvalue =filter_var($type[1]  , FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                    $stmt->bindValue(":{$columName}",$sanitizedvalue);
                }else{

                    $stmt->bindValue( ":{$columName}",$type[1]  , $type[0]);
                }
            }

        }
        $stmt->execute() ;
        $results = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE , get_called_class()   ,array_keys(static::$tableSchema));
        return (is_array($results) && !empty($results)) ? $results : false ;
    }

}