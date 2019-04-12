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
	}else if($post['type'] == 'getNews'){
		$result = fetchNews($post['offset']);
	}else if($post['type'] == 'deleteNews'){
		$result = deleteNews($post);
	}else if($post['type'] == 'getIndNews'){
		$result = getIndNews($post);
	}else if($post['type'] == 'updateNews'){
		$result = updateNews($post);
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

		if(!isset($_SESSION['admin_logged_inm'])){
			return [
				'status' => false,
				'msg' => 'Session expired'
			];
		}

		global $pdo;
		extract($post);
		if(empty($title) || empty($description) || empty($date)){
			return [
				'status' => false,
				'msg' => 'Please fill all the mandatory fields'
			];	
		}
		if(!empty($_FILES)){
			$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			$filename = date('ymdhis').rand(10000,900000).'.'.$ext;
			move_uploaded_file($_FILES['file']['tmp_name'], '../uploads/'.$filename);
		}else{
			$filename = '';
		}

		$date = date('Y-m-d',strtotime($date));
		$slug = strtolower(str_replace(' ','-',$title));

		$query = "insert into news(title,description,date,admin_id,slug,image) values(?,?,?,?,?,?)";
		$builder = $pdo->prepare($query);
		$builder->execute([
			$title,
			$description,
			$date,
			$_SESSION['admin_id'],
			$slug,
			$filename
		]);

		return [
			'status' => true,
			'msg' => 'New story has been saved!'
		];		
	}

	function updateNews($post){
		session_start();

		if(!isset($_SESSION['admin_logged_in'])){
			return [
				'status' => false,
				'msg' => 'Session expired'
			];
		}

		global $pdo;
		extract($post);
		if(empty($title) || empty($description) || empty($date)){
			return [
				'status' => false,
				'msg' => 'Please fill all the mandatory fields'
			];	
		}
		if(!empty($_FILES)){
			$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			$filename = date('ymdhis').rand(10000,900000).'.'.$ext;
			move_uploaded_file($_FILES['file']['tmp_name'], '../uploads/'.$filename);
		}else{

			$query = "select image from news where id=?";
			$builder = $pdo->prepare($query);
			$builder->execute([$post['id']]);
			$news = $builder->fetch();
			$filename = $news['image'];
		}

		$date = date('Y-m-d',strtotime($date));
		$slug = strtolower(str_replace(' ','-',$title));

		$query = "update news set title=?,description=?,date=?,admin_id=?,slug=?,image=? where id=?";
		$builder = $pdo->prepare($query);
		$builder->execute([
			$title,
			$description,
			$date,
			$_SESSION['admin_id'],
			$slug,
			$filename,
			$post['id']
		]);

		return [
			'status' => true,
			'msg' => 'New story has been updated!'
		];		
	}

	function doLoginCheck(){
		session_start();
		return [
			'status' => isset($_SESSION['admin_logged_in'])
		];
	}

	function fetchNews($offset){
		global $pdo;
		$query = "select * from news where status=1 order by created_at desc limit $offset,10";
		$builder = $pdo->prepare($query);
		$builder->execute();
		$news = $builder->fetchAll();
		return [
			'status' => true,
			'news' => $news
		];
	}

	function deleteNews($post){
		global $pdo;
		$query = "update news set status=0 where id=?";
		$builder = $pdo->prepare($query);
		$builder->execute([$post['id']]);
		return [
			'status' => true,
			'msg' => 'Selected news has been deleted successfully!'
		];
	}

	function getIndNews($post){
		global $pdo;
		$query = "select * from news where id =?";
		$builder = $pdo->prepare($query);
		$builder->execute([$post['id']]);
		$news = $builder->fetch();

		if(empty($news)){
			return [ 'status' => false, 'msg' => 'Invalid news item'];
		}

		return ['status' => true, 'news' => $news ];
	}

?>