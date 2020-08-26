<?php

//action.php

$connect = new PDO("mysql:host=localhost;dbname=crud_vue", "root", "");
$received_data = json_decode(file_get_contents("php://input"));
$data = array();
if($received_data->action == 'fetchall')
{
 $query = "
 SELECT * FROM customer_details 
 ";
 $statement = $connect->prepare($query);
 $statement->execute();
 while($row = $statement->fetch(PDO::FETCH_ASSOC))
 {
  $data[] = $row;
 }
 echo json_encode($data);
}

//adding data to database

if($received_data->action == 'addCustomerData')
      
{
    $data = array(
        ':fname'=>$received_data->fname,
        ':lname'=>$received_data->lname,
        ':address'=>$received_data->address,
        ':city'=>$received_data->city,
        ':pin'=>$received_data->pin,
        ':country'=>$received_data->country
    );

    $query = "insert into customer_details(id,fname,lname,address,city,pin,country) 
     values (null,:fname, :lname, :address, :city, :pin, :country)";
     
    $statement = $connect->prepare($query);
    $statement->execute($data);

    $output = array(
        'message'=>'Data Inserted to Database'
    );
    echo json_encode($output);

}

if($received_data->action=='deleteCustomerDetails'){

    $data = array(
        ':id'=>$received_data->id
    );
    $query = "Delete from customer_details where id=:id";
    $statement = $connect->prepare($query);
    $statement->execute($data);

    $output = array(
        'message'=>'Customer Data Removed sucessfully!'
    );
    echo json_encode($output);

}


if($received_data->action=='fetchById'){
    $data = array(
        ':id'=>$received_data->id
    );
    $query = "select * from customer_details where id=:id";
    $statement = $connect->prepare($query);
    $statement->execute($data);

    $result = $statement->fetchAll();

    foreach($result as $row)
    {
     $data['id'] = $row['id'];
     $data['fname'] = $row['fname'];
     $data['lname'] = $row['lname'];
     $data['city']=$row['city'];
     $data['address'] = $row['address'];
     $data['country'] = $row['country'];
     $data['pin'] = $row['pin'];
    }
   
    echo json_encode($data);
}

if($received_data->action == 'updateDetails'){
    $data = array(
        ':id'=>$received_data->id,
        ':fname'=>$received_data->fname,
        ':lname'=>$received_data->lname,
        ':address'=>$received_data->address,
        ':city'=>$received_data->city,
        ':pin'=>$received_data->pin,
        ':country'=>$received_data->country
    );
    $query = "UPDATE `customer_details` 
    SET `fname`=:fname,`lname`=:lname,`address`=:address,`city`=:city,`pin`=:pin,`country`=:country 
    WHERE id=:id";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array(
        'message'=>'Customer details Updated Sucessfully!'
    );
    echo json_encode($output);

}

//search Query

if($received_data->action =='searchData'){
$searchValue= $received_data->query;  
if($searchValue!=null){
    $query = "
	SELECT * FROM customer_details 
	WHERE fname LIKE '%".$searchValue."%' 
	OR lname LIKE '%".$searchValue."%' 
	ORDER BY id DESC
	";
}
else{
    $query = "
    SELECT * FROM customer_details 
    ORDER BY id DESC
    ";
}
    $statement = $connect->prepare($query);

    $statement->execute();

    while($row = $statement->fetch(PDO::FETCH_ASSOC))
        {
            $data[] = $row;
        }

    echo json_encode($data);
}

?>
