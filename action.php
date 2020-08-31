<?php

//action.php

$connect = new PDO("mysql:host=localhost;dbname=crud_vue", "root", "");
$received_data = json_decode(file_get_contents("php://input"));
$data = array();
$image = '';
if($received_data->action == 'fetchall')
{
 $query = "
 SELECT * FROM customer_table 
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
        ':lname'=>$received_data->lname,
        ':fname'=>$received_data->fname,
        ':address'=>$received_data->address,
        ':email'=>$received_data->email,
        ':phone'=>$received_data->phone,
        ':profile'=>$received_data->profile
    );

    $query = "insert into customer_table(id,fname,lname,address,email,phone,profile) 
     values (null,:fname, :lname, :address, :email, :phone, :profile)";
     
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
    $query = "Delete from customer_table where id=:id";
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
    $query = "select * from customer_table where id=:id";
    $statement = $connect->prepare($query);
    $statement->execute($data);

    $result = $statement->fetchAll();

    foreach($result as $row)
    {
     $data['id'] = $row['id'];
     $data['fname'] = $row['fname'];
     $data['lname'] = $row['lname'];
     $data['email']=$row['email'];
     $data['address'] = $row['address'];
     $data['profile'] = $row['profile'];
     $data['phone'] = $row['phone'];
    }
   
    echo json_encode($data);
}

if($received_data->action == 'updateDetails'){
    $data = array(
        ':id'=>$received_data->id,
        ':lname'=>$received_data->lname,
        ':fname'=>$received_data->fname,
        ':address'=>$received_data->address,
        ':email'=>$received_data->email,
        ':phone'=>$received_data->phone,
        ':profile'=>$received_data->profile
    );
    $query = "UPDATE `customer_table` 
    SET `fname`=:fname,`lname`=:lname,`address`=:address,`email`=:email,`phone`=:phone,`profile`=:profile 
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
	SELECT * FROM customer_table 
	WHERE fname LIKE '%".$searchValue."%' 
	OR lname LIKE '%".$searchValue."%' 
	ORDER BY id DESC
	";
}
else{
    $query = "
    SELECT * FROM customer_table 
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

// if($received_data->action =='upload'){
// if(isset($_FILES['file']['name']))
// {
//  $image_name = $_FILES['file']['name'];
//  $valid_extensions = array("jpg","jpeg","png");
//  $extension = pathinfo($image_name, PATHINFO_EXTENSION);
//  if(in_array($extension, $valid_extensions))
//  {
//   $upload_path = 'upload/' . time() . '.' . $extension;
//   if(move_uploaded_file($_FILES['file']['tmp_name'], $upload_path))
//   {
//    $message = 'Image Uploaded';
//    $image = $upload_path;
//   }
//   else
//   {
//    $message = 'There is an error while uploading image';
//   }
//  }
//  else
//  {
//   $message = 'Only .jpg, .jpeg and .png Image allowed to upload';
//  }
// }
// else
// {
//  $message = 'Select Image';
// }

// $output = array(
//  'message'  => $message,
//  'image'   => $image
// );

// echo json_encode($output);
// }

?>
