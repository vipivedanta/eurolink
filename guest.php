<?php 
	
	include_once 'database.php';
	ini_set('display_errors',1);
	error_reporting(E_ALL);

	$post = $_POST;

	if($post['type'] == 'news'){
		$result = fetchNews($post['offset']);
	}else if($post['type'] == 'indiNews'){
		$result = fetchInidividualNews($post);
	}else if($post['type'] == 'intro_news'){
		$result = fetchIntroNews();
	}else if($post['type'] == 'contact'){
		$result = sendContact($post);
	}

	die(json_encode($result));


	function createLinks($total,$per_page,$offset){

		if($total <= $per_page) return '';

		$total_pages = ceil($total/$per_page);
		$links = '<ul class="page_pagination_two center">';
		for($i=1;$i<=$total_pages;$i++){
			if($i == ($offset+1)){
				$j = $i;
				if($i <= 1) $j = 0;
				if($i > $total_pages) $j = $total_pages;
				$links .= "<li><a class='tran3s active' href='#'>$i</a></li>";
			}
			else{
				$j = $i;
				if($i == 1) $j = 0;
				if($i > $total_pages) $j = $total_pages;
				$links .= "<li><a data-page='$j' class='tran3s clickable-1' href='#'>$i</a></li>";
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
		$per_page = 10;
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

	function fetchInidividualNews($post){
		global $pdo;
		$query = "select * from news where id=?";
		$builder = $pdo->prepare($query);
		$builder->execute([$post['id']]);
		$news = $builder->fetch();

		if(empty($news)){
			return ['status' => false,'msg' => 'No news found!'];
		}

		return ['status' => true,'news' => $news];
	}

	function fetchIntroNews(){
		global $pdo;
		$query = "select * from news where status = 1 order by id desc limit 0,2";
		$builder = $pdo->prepare($query);
		$builder->execute();
		$news = $builder->fetchAll();
	
		if(empty($news)){
			return ['status' => false,'msg' => 'No news found'];
		}

		return ['status' => true, 'news' => $news ];
	}

?>