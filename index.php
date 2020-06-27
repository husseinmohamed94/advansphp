
<?php
session_start();

require_once 'db.php';
require_once 'abstractmodel.php' ;
require_once 'employee.php ';

if(isset($_POST['submit'])){

    $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
    $address  =  filter_input(INPUT_POST,'address',FILTER_SANITIZE_STRING);
    $age =  filter_input(INPUT_POST,'age',FILTER_SANITIZE_NUMBER_INT);
    $salary =  filter_input(INPUT_POST,'salary',FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
    $tax =  filter_input(INPUT_POST,'tax',FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);

	//update
    if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])){
        $id =  filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        if($id > 0 ){
            $user = Employee::getBypk($id);
            $user->name = $name;
            $user->age  = $age;
            $user->address =$address;
            $user->salary  = $salary;
            $user->tax = $tax;

        }

    }else{
            $user = new Employee($name,$age ,$address,$tax,$salary);

    }
	if($user->save()=== true){
  $_SESSION['message'] = 'employee saving successfully';
  header('location:http://localhost/advansphp');
  session_write_close();
  exit;
  }

else{
	$error= true;
       $_SESSION['message']  = 'Error saveing employees';
		}

}

if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])){

    $id =  filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
    if($id > 0 ){
        $user = Employee::getBypk($id);
    }
}


//delete
if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])){

    $id =  filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
    if($id > 0 ){
        $user = Employee::getBypk($id);
    if($user->delete() === true){
    
 $_SESSION['message'] = 'employee delete successfully';
  header('location:http://localhost/advansphp');
  session_write_close();
  exit;
    }
    }
}

//reading form datebase

	$result = Employee::getAll();


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www. w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PDO</title>
<link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="css/font-awesome.min.css" />

</head>

<body>
<div class="wrapper">
    <div class="empform">
    <form class="appform" method="post" enctype="application/x-www-form-urlencoded">
        <fieldset>
            <legend>employees informtion</legend>
            <?php if(isset( $_SESSION['message'])) { ?>
            
	        <P class="message <?= isset($error) ? 'error' : ' '  ?> "> <?=   $_SESSION['message']  ?> </P>
		
        	<?php  
			unset($_SESSION['message']);
			} ?>
            <table> 
                <tr>
                    <td>
                        <label for="name">employee name</label>
                    </td>
                </tr>

                <tr>
                    <td>

                        <input  type="text"  required name="name"  id="name" value="<?= isset($user) ? $user->name : '' ?>" placeholder="writer the employee" maxlength="50" />
                    </td>
                </tr>
                <tr>
                <td>
                    <label for="age">employee age</label>
                </td>
                </tr>

                <tr>
                    <td>
                        <input type="number" required name="age" id="age"  value="<?= isset($user) ? $user->age : '' ?>"  min="22" max="60"/>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="address">employee address</label>
                    </td>
                </tr>

                <tr>
                    <td>

                        <input  type="text"  required name="address"  id="address"  value="<?= isset($user) ? $user->address : '' ?>" placeholder="writer the employee address"  maxlength="50" />
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="salary">employee salary</label>
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="number" required name="salary"  id="salary" value="<?= isset($user) ? $user->salary : '' ?>" step="0.01"  min="3000" max="6000"/>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="tax">employee tax %</label>
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="number" required name="tax" id="tax" value="<?= isset($user) ? $user->tax : '' ?>" step="0.01"  min="1" max="2"/>
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="submit"  name="submit" value="save" />
                    </td>
                </tr>


            </table>
        </fieldset>
    </form>
</div>
<div class="employees">
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>name</th>
            <th>age</th>
            <th>adders</th>
            <th>salary</th>
            <th>tax%</th>
            <th>control</th>
        </tr>

        </thead>
        <tbody>
       	<?php  
       
	   if(false !== $result){
		   foreach ($result as $employee){
			   ?>
                <tr>
            <td><?= $employee->id ?> </td>
            <td><?= $employee->name ?></td>
            <td><?= $employee->age ?></td>
            <td><?= $employee->address ?></td>
            <td><?= round ($employee->calculatesalary())?> L.E</td>
            <td><?= $employee->tax ?></td>
            <td>
                <a href="/advansphp/?action=edit&id=<?= $employee->id  ?> "  > <i class="fa fa-edit"></i> </a>
                <a href="/advansphp/?action=delete&id=<?= $employee->id ?>"
                 onclick="if(!confirm('Do uou want to delete this employee ')) return false ; " ><i class="fa fa-times"></i> </a>
            </td>
          </tr>
         <?php     
			   
			   }
		   }else{
			  ?>
              <td colspan="7" ><p>sorry no employees to list</p></td>
            <?php   
			   
			   }
		?>
        </tbody>


    </table>
</div>

</div>
</body>
</html>
