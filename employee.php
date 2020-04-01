<?php

class Employee
{

public function ins()
{
include_once('conn.php');
     
$nameErr = $emailErr = $genderErr = $courseErr= $fileErr= $passErr="" ;
$name = $email =  $gender = $course= $file= $password ="";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    
//$errors=array();
if (empty($_POST["name"])) {
   
$nameErr = "Name required";
echo "Name required"."<br>";
} 
else {
$name = $_POST["name"];
if (!preg_match("/^[a-zA-Z ]*$/",$_POST["name"])) {
 $nameErr= "Only letters and white space allowed";
 echo "Only letters and white space allowed";
 }
}

if (empty($_POST["email"])) {
$emailErr =  "email required";
echo "email required";
}
else {
//$email= test_input($_POST["email"]);
if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $_POST['email']))
{
$emailErr= "Invalid email format";
echo "Invalid email format";
}
}   

if (empty($_POST["password"])) {
$passErr = "password required";
echo "password required";
  } 
 else{
 $password = $_POST["password"];
    if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', $_POST['password']))
     {
        $passErr='incorrect password format!';
        echo "incorrect password format!";
    }
  }
if (empty($_POST['gender'])) {
$genderErr = "Gender is required";
 echo "Gender is required";

}
//else {
// $gender = test_input($_POST["gender"]);
// }

if (empty($_POST["course"])) {
$courseErr= "The checkbox isn't checked";
echo  "enter some course";
}


 if (empty($_FILES['file'])) {
        $fileErr = "chooose file";   
          echo "choose file"; 
          }   
          else
          {
           $file = $_FILES['file']['name'];
          $allowedExts = array("doc", "docx", "png", "jpg");
          $file_extension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
	    if (!in_array($file_extension, $allowedExts)) {
            $fileErr = "only doc,docx,png,jpg extension allowed<br />";
          echo "only doc,docx,png,jpg extension allowed";
        }}


  // insertion 
   if(isset($_POST['name'])){
       $name = $_POST['name'];
   }
   if(isset($_POST['email'])){
       $email = $_POST['email'];
   }
   if(isset($_POST['password'])){
    $password = $_POST['password'];
}	

   if(isset($_POST['gender'])){
       $gender= $_POST['gender'];
   }	
   if(isset($_POST['course'])){
       $course = $_POST['course'];
   }
   if(isset($_FILES['file'])){
    $file = $_FILES['file']['name'];

    //print_r($file);
    
}	
$query = mysqli_query($conn, "SELECT * FROM rest WHERE email='".$email."'");
$numrows = mysqli_num_rows($query);
if($numrows != 0 )
{
  echo "That Email already exists! Please try another.";
}
  else
{
   if(empty($nameErr) && empty($emailErr) && empty($passErr)  && empty($genderErr) && empty($courseErr) && empty($fileErr))
   {  
   $query="INSERT INTO rest (name,email,password,gender,course,file) Values ('$name','$email','$password','$gender','$course','$file');";
  
   $st= mysqli_query($conn,$query);

   if($st)
   {
  // if(move_uploaded_file($_FILES['file']['tmp_name'],'/images/test.png')){
  //    echo "success"; 
  // }
   $st =array(
   'status' => 1,
   'status_message' =>'Successfully signed up.'
   );
   }
   else
   {
   $st=array(
   'status' => 0,
   'status_message' =>'sign up failed'
           
   );
  }  
           echo json_encode($st);
}} 
}}
   


public function select()
{
  include_once('conn.php');
  session_start();
  if(isset($_POST['email']) and (isset($_POST['password'])))
  {
     $email =$_POST['email'];
     $password =$_POST['password'];
    $ret ="select email,password from rest where (email='" . $email."' and password= '".$password."')";
      // echo $ret;
    $sel=mysqli_query($conn,$ret);
       //echo $sel;
       
    $row=mysqli_fetch_assoc($sel);
        
    if(is_array($row)) 
    {
    $_SESSION["email"] = $row['email'];
    //$_SESSION["Password"] = $row['password'];
    echo "Success";
    header('location:show.php');
    }
    else 
    {
    echo "check email and password";
    }}
          // echo json_encode($);
    
   }


public function show()
{           
    include_once('conn.php');
    session_start();

    $query= "select * from rest";
    $data =[];
    $result= mysqli_query($conn,$query);
   
      while($rows=mysqli_fetch_assoc($result))
    {     
     $data[] =$rows;    
               // Print_r($data);
    }    
    
    foreach($data as $disp)
    {
      $disp['id']."<br>"; 
      $disp['name']."<br>";       // here Name is the name of column in database 
      $disp['email']."<br>";
      $disp['gender']."<br>";
      $disp['course']."<br>";
                      
      echo json_encode($disp);
      }
      
     
                  
      }
                    
                           

public function edit()
{
include_once('conn.php');
//echo $_GET['id'];
//echo $_POST['name'];
if(isset($_GET['id'])) 
{

$name = $_POST['name'];
$gender= $_POST['gender'];
$course= $_POST['course'];
//$email= $_POST['email'];

$query = "UPDATE rest SET name = '".$name."', gender = '". $gender ."',course ='". $course."'WHERE id = ".$_GET['id'];
// echo $query;
$str=mysqli_query($conn,$query);
if($str)
{
$str= array('status' => true, 'message' => 'Post Updated Successfully...');
}
   
else
{
$str= array('status' => false, 'message' => 'Can\'t able to update a post details...');
}

echo json_encode($str);
}
}

public function delete()
{         
   include_once('conn.php');
   if(isset($_GET['id']))
   {
       $id = $_GET['id'];
       $query ="DELETE FROM rest WHERE id = '$id'";
         $st= mysqli_query($conn,$query);
         
   if($st)
   {
       $st =array(
           'status' => 1,
           'status_message' =>'Deleted data'
       );
   }
   else
   {
       $st=array(
           'status' => 0,
           'status_message' =>'failed'
           
       );
       
   }
      echo json_encode($st);
     }
}

public function logout()
{
include_once('conn.php');

session_start();

unset($_SESSION['email']);

session_destroy();
echo "you are logout";
header("Location:insertemp.php");
}

       //echo json_encode($_SESSION);


public function forgot()
{
   include_once('conn.php');
   if(isset($_GET))
   {
    $email=$_GET['email'];
    $query = "select * from rest where (email='" .$email."')";
    echo $query;
    $result= mysqli_query($conn,$query);
    $count= mysqli_num_rows($result);
    print_r($count);
    if($count ==1)
    {
     
    $rows=mysqli_fetch_assoc($result);
    $email=$rows['email'];
    $password=$rows['password'];
    $to = $email;
    $subject = "Password";
    $txt = "Your password is : $password.";
    $headers = "From: password@studentstutorial.com" . "\r\n" .
    "CC: somebodyelse@example.com";
    if(mail($to,$subject,$txt,$headers))
    {
      echo "sent";
    }
    else{
       echo "failed";
    }
		
  }}}

  public function reset()
  {
    include_once('conn.php');
    
    if(isset($_POST['email']))
    {
     $password=$_POST['password'];
     $email=$_POST['email'];
     $newpassword=$_POST['newpassword'];
     $sql=mysqli_query($conn,"SELECT email,password FROM rest where password='$password' and email='$email'");
    // print_r($sql);
     //echo mysqli_num_rows($sql);
     if(mysqli_num_rows($sql) ==1)
     {
      //echo $_POST['email'];
      $con=mysqli_query($conn,"update rest set password=' $newpassword' where email='$email'");
      if($con)
      {

        echo "password updated";
      }
      else 
      {
        echo "failed";
      }
      
      }
       else{
               echo "error";

      }}
    }}
?>


