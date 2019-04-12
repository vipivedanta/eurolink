<?php 
	
	include '../database.php';
	$post = $_POST;
	ini_set('display_errors',1);

	if($post['type'] == 'login'){
		$result = doLogin($post);

	}else if($post['type'] == 'save'){
		$result = saveNews($post);

	}else if($post['type'] == 'loginCheck'){
		$result = doLoginCheck();
	}

	die(json_encode($result));


	function doLogin($post){
		global $pdo;
		$query = "select * from admin where username=? and password=?";
		$builder = $pdo->prepare($query);
		$builder->execute([ $post['username'], md5($post['password'])]);
		$user = $builder->fetch();
		
		if(empty($user)){
			return [
			'status' => false,
			'msg' => 'Sorry! Could not login.Please check your credentials.'
			];
		}

		session_start();
		$_SESSION['admin_logged_in'] = true;
		$_SESSION['admin_id'] = 1;

		return [
			'status' => true,
			'msg' => 'You have logged in successfully!'
		];		
	}

	function saveNews($post){
		session_start();
		global $pdo;
		extract($post);
		if(empty($title) || empty($description) || empty($date)){
			return [
			'status' => false,
			'msg' => 'Please fill all the mandatory fields'
		];	
		}

		$query = "insert into news(title,description,date,admin_id) values(?,?,?,?)";
		$builder = $pdo->prepare($query);
		$builder->execute([
			$title,
			$description,
			$date,
			$_SESSION['admin_id']
		]);

		return [
			'status' => true,
			'msg' => 'New story has been saved!'
		];		
	}

	function doLoginCheck(){
		session_start();
		return [
			'status' => isset($_SESSION['admin_logged_in'])
		];
	}

?>