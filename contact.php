<?php 
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	if(!isset($_POST)){
		header('location:index.html');
	}


	$post = $_POST;
	extract($post);

	if($first_name == ''){
		die(json_encode(['status' => false,'field' => 'first_name','msg' => 'please fill first name']));
	}
	if($last_name == ''){
		die(json_encode(['status' => false,'field' => 'last_name','msg' => 'please fill last name']));
	}
	if($email == ''){
		die(json_encode(['status' => false,'field' => 'email','msg' => 'please fill email']));
	}
	if($phone == ''){
		die(json_encode(['status' => false,'field' => 'phone','msg' => 'please fill phone']));
	}
	if($message == ''){
		die(json_encode(['status' => false,'field' => 'message','msg' => 'please fill message']));
	}

	

	require 'phpmailer/src/Exception.php';
	require 'phpmailer/src/PHPMailer.php';
	require 'phpmailer/src/SMTP.php';

	$mail = new PHPMailer(true);                             
	
	try {

		$table  = 'Hi, you have new contact request! <h3>New enquiry</h3>';
		$table .= '<p>Please see the details below</p><hr/>';
		$table .= "<table class='mail-table'>";
		$table .= "<tr><td>Name:</td><td>".$first_name." ".$last_name."</td></tr>";
		$table .= "<tr><td>Phone:</td><td>".$phone."</td></tr>";
		$table .= "<tr><td>Email:</td><td>".$email."</td></tr>";
		$table .= "<tr><td>Contact request on </td><td>".date('F d,Y h:i A')."</td></tr>";
		$table .= "</table>";
		$table .= "<p>".$message."</p>";

		

	    #$mail->SMTPDebug = 2;                                 // Enable verbose debug output
	    $mail->isSMTP();                                      // Set mailer to use SMTP
	    $mail->Host = 'ssl://smtp.googlemail.com';  // Specify main and backup SMTP servers
	    $mail->SMTPAuth = true;                               // Enable SMTP authentication
	    #$mail->Username = 'hknweb19@gmail.com';                 // SMTP username
	    #$mail->Password = 'Ml@php54156';   
	    $mail->Username = 'kshemabuildcon@gmail.com';                 // SMTP username
	    $mail->Password = 'PVk@0604';                         // SMTP password
	    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	    $mail->Port = 465; 
	    $mail->IsHTML(true);                                    // TCP port to connect to

	    //Recipients
	    $mail->setFrom('kshemabuildcon@gmail.com ', 'Eurolink Consultancy services');
	    $mail->addAddress('vipins110@gmail.com');               // Name is optional
	    #$mail->addBCC('vipins110@gmail.com');
	    $mail->Subject = 'New contact request received - Eurolink.';
   
	    $mail->Body    = $table;
	    $mail->send();
	    
	    die(json_encode(['status' => true,'msg' => 'Your message has been sent!']));

	} catch (Exception $e) {
	    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
	}
?>