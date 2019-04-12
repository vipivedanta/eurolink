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
	}else if($post['type'] == 'signout'){
		$result = doSignout();
	}else if($post['type'] == 'changePassword'){
		$result = changePassword($post);
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

	function createLinks($total,$per_page,$offset){

		if($total <= $per_page) return '';

		$total_pages = ceil($total/$per_page);
		$links = '<ul class="pagination mg-b-0 page-0">';
		for($i=1;$i<=$total_pages;$i++){
			if($i == ($offset+1)){
				$j = $i;
				if($i <= 1) $j = 0;
				if($i > $total_pages) $j = $total_pages;
				$links .= "<li class='page-item'><a class='page-link' href='#'>$i</a></li>";
			}
			else{
				$j = $i;
				if($i == 1) $j = 0;
				if($i > $total_pages) $j = $total_pages;
				$links .= "<li class='page-item'><a data-page='$j' class='page-link clickable' href='#'>$i</a></li>";
			}
		}
		$links .= '</ul>';
		return $links;
	}

	function fetchNews($offset){
		global $pdo;

		#get count
		$query = "select count(id) as total from news where status=?";
		$builder = $pdo->prepare($query);
		$builder->execute([1]);
		$row = $builder->fetch();
		$total = $row['total'];
		$per_page = 20;
		$links = createLinks($total,$per_page,$offset);

		#$offset = $offset * $per_page;

		$query = "select * from news where status=1 order by created_at desc limit $offset,$per_page";
		$builder = $pdo->prepare($query);
		$builder->execute();
		$news = $builder->fetchAll();
		return [
			'status' => true,
			'news' => $news,
			'links' => $links,
			'query' => $query
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

	function doSignout(){
		session_start();
		unset($_SESSION['admin_logged_in']);
		unset($_SESSION['admin_id']);
		session_destroy();
		return [
			'status' => true
		];
	}

	function changePassword($post){
		global $pdo;
		extract($post);

		if(empty($old_password) || empty($new_password) || empty($repeat_password)){
			return ['status' => false,'msg' => 'Please fill all the three fields'];
		}

		if($new_password != $repeat_password){
			return ['status' => false,'msg' => 'Passwords do not match'];
		}

		$query = "select id from admin where password=?";
		$builder = $pdo->prepare($query);
		$builder->execute([md5($old_password)]);
		$admin = $builder->fetch();

		if(empty($admin)){
			return ['status' => false, 'msg' => 'Please type your old password correct'];
		}

		$query = 'update admin set password=? where id=?';
		$builder = $pdo->prepare($query);
		$builder->execute([ md5($new_password),1]);
		return ['status' => true,'msg' => 'Your password has been changed successfully!'];
	}

?>