<?php
include '../PagesFunction/connection.php';




use PHPMailer\PHPMailer\PHPMailer;

if (isset($_POST['sendOrder'])) {




  $sql = "SELECT id FROM pending_order_list";
  $result=mysqli_query($con,$sql);
  $pendingCount=mysqli_num_rows($result);
  $pendingCount++;


  $orderNum_order=date("Ymd-His-") . 0 .$pendingCount;

    $fname = $_POST['fname_order']; // required
    $lname = $_POST['lname_order']; 
    $email_from = $_POST['email_order']; // required
    $contact = $_POST['contact_order']; // not required
    $address = $_POST['address_order']; // required
    $msg = $_POST['message_order']; // required
    


    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';





    include_once 'PHPMailer/PHPMailer.php';

    $email_to_admin = "admin@zerterra.com";
    $email_subject = "PRE-ORDER";




    $mail = new PHPMailer();
    $mail->setFrom($email_from);
    $mail->addAddress($email_to_admin);
    $mail->Subject = $email_subject;
    $mail->isHTML(true);
    $mail->Body ="PRE-ORDER EMAIL FROM <br>
    ORDER NUMBER: $orderNum_order <br> 
    EMAIL: $email_from <br> 
    FIRSTNAME:  $fname <br> 
    LASTNAME:  $lname <br> 
    CONTACT:  $contact <br> 
    ADDRESS:  $address <br> 
    MESSAGE: $msg";
    $mail->Header = implode("\r\n". $headers);

    if ($mail->send()){

      $subject_from_support = "Pre-Order\t[" . $orderNum_order . "]";
      $mailfromadmin = new PHPMailer();
      $mailfromadmin->setFrom("no-reply@zerterra.com");
      $mailfromadmin->addAddress($email_from);
      $mailfromadmin->Subject = $subject_from_support;
      $mailfromadmin->isHTML(true);
      $mailfromadmin->Body = "
      Please CLICK the link below for PRE-ORDER DETAILS:<br><br>

      <a href='http://zerterra.com/content/Pre-Order_Details.php?fname=$fname&lname=$lname&email=$email_from&contact=$contact&address=$address&message=$message&OrderNumber=$orderNum'>Click Here</a>
      ";
      
      $mailfromadmin->Header = implode("\r\n", $headers);

      if ($mailfromadmin->send()){


       $cmdsql= "INSERT INTO pending_order_list(OrderNumber,Firstname,Lastname,Email,Contact,Address,Message,is_approved) VALUES ('$orderNum','$fname','$lname','$email_from','$contact','$address','$message','0')";        
        if($con->query($cmdsql) === TRUE){
            
           echo "<script>alert('Return Email Sent!'); </script>";

        }else{
          echo "<script>alert('QUERY FAILED!'); </script>";
        }
      }else{
        
         echo "<script>alert('Return Mail Not Sent!'); </script>";
      }
    }else{

      
    echo "<script>alert('Sending Request Failed!'); </script>";


    }
     echo '<script>window.location.href="../"</script>';
}

  ?>