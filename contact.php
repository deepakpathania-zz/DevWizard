<?php

session_start();

require_once 'libs/phpmailer/PHPMailerAutoload.php';

$errors = [];

if(isset($_POST['name'], $_POST['email'], $_POST['message'])){
	$fields = [
		'name' => $_POST['name'],
		'email' => $_POST['email'],
		'phone' => $_POST['phone'],
		'webDesign' => $_POST['webDesign'],
		'webDevelopment' => $_POST['webDevelopment'],
		'content' => $_POST['content'],
		'SEO' => $_POST['SEO'],
		'apps' => $_POST['apps'],
		'bots' => $_POST['bots'],
		'budget' => $_POST['budget'],
		'message' => $_POST['message']
	];

	$checkFields = [
		'webDesign' => $_POST['webDesign'],
		'webDevelopment' => $_POST['webDevelopment'],
		'content' => $_POST['content'],
		'SEO' => $_POST['SEO'],
		'apps' => $_POST['apps'],
		'bots' => $_POST['bots'],
		'message' => $_POST['message']
	];

	$requiredFields = [
		'name' => $_POST['name'],
		'email' => $_POST['email'],
		'phone' => $_POST['phone']
	];

	foreach ($requiredFields as $field => $data) {
		if (empty($data)) {
			$errors[] = 'The '. $field .' field is required.';
		}
	}

	if (empty($errors)) {
		$m = new PHPMailer;

		$m->isSMTP();
		$m->SMTPAuth = true;

		$m->Host = 'smtp.gmail.com';
		$m->Username = 'overratededitors@gmail.com';
		$m->Password = 'wearethemillers';
		$m->SMTPSecure = 'ssl';
		$m->Port = 465;

		$m->isHTML();

		$m->Subject = 'Query!';
		$r="";
		foreach ($checkFields as $field => $data) {
			if ($data=='on') {
				// var_dump($field);
				// die();
				// $requirements += $field;
				if ($r == "") {
					$r = $field;
				}
				else{
					$r = $r . ", " . $field;
				}
			}
		}

		// var_dump($r);
		// die();

		$m->Body = 'From: '. $fields['name'] .' (' .$fields['email']. ')<p>'. $fields['message'] ."</p><br><p>Requirements:</p><br>".$r."<br><br><p>Budget:</p><br>".$fields['budget'];

		$m->FromName = $fields['name'];

		$m->AddAddress('overratededitors@gmail.com','Himanshu Tiwari');

		$m->addReplyTo($fields['email'], $fields['name']);

		if ($m->send()) {
			$m1 = new PHPMailer;

			$m1->isSMTP();
			$m1->SMTPAuth = true;

			$m1->Host = 'smtp.gmail.com';
			$m1->Username = 'overratededitors@gmail.com';
			$m1->Password = 'wearethemillers';
			$m1->SMTPSecure = 'ssl';
			$m1->Port = 465;

			$m1->isHTML();

			$m1->Subject = 'Response!';
			$m1->Body = 'Thanks, we got your message and will reply soon.';

			$m1->FromName = 'Himanshu Tiwari';

			$m1->AddAddress($fields['email'], $fields['name']);

			$m1->addReplyTo('overratededitors@gmail.com','Himanshu Tiwari');

			if ($m1->send()) {
				header('Location: index.php');
				die();
			}else{
				$errors[] = 'Sorry, we could not send the email. Please try again later.';
			}
		}else{
			$errors[] = 'Sorry, we could not send the email. Please try again later.';
		}
	}
}
else{
	$errors[] = "There's something wrong!";
}

$_SESSION['errors'] = $errors;
$_SESSION['fields'] = $fields;

header('Location: index.php');

?>