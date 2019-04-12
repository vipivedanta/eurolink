$(document).ready(function(e){

	if($('.admin-page-session').length > 0){
		isLoggedIn();
	}

	if( $('.get-news').length > 0){
		getNews(0);
	}

	//login
	$('.admin-login').click(function(e){
		e.preventDefault();

		disableBtn( $(this) );
		var username = $('#username').val();
		var password = $('#password').val();

		if(username == ''){
			alert('Please enter the username');
			return false;
		}

		if(password == ''){
			alert('Please enter the password');
			return false;
		}


		var data = {
			  type : 'login',
				username : username, 
				password : password
		};

		$('.login-error').addClass('hide');
		var btn = $(this);

		$.post('admin.php', data, function(response){
			  var response = $.parseJSON(response);
				if(!response.status){
					enableBtn(btn,'Login');
					 $('.login-error').removeClass('hide').html(response.msg);
					 return false;
				}

				$('.login-success').html(response.msg);
				window.location.href = 'homepage.html';
		});


	});

	//add news
	$('.add-news').click(function(e){
		e.preventDefault();
		var title = $('#title').val();
		var date = $('#date').val();
		var description = $('#description').val();

		if(title == ''){
			alert('Please enter the title');
			return false;
		}

		if(date == ''){
			alert('Please select the date');
			return false;
		}

		if(description == ''){
			alert('Please enter the description');
			return false;
		}

		var formData = new FormData( );
		formData.append('file', $('#image')[0].files[0]);
		formData.append('type','save');
		formData.append('title',title);
		formData.append('date',date);
		formData.append('description',description);

		$.ajax({
		       url : 'admin.php',
		       type : 'POST',
		       data : formData,
		       processData: false,  // tell jQuery not to process the data
		       contentType: false,  // tell jQuery not to set contentType
		       success : function(response) {
		         var response = $.parseJSON(response);
					if(response.status == false){
						alert('Could not save the news');
						return false;
					}

					window.location.href = 'view-news.html';
		       }
		});

		

	});

	$('body').on('click','.delete-news',function(e){
		e.preventDefault();
		$('.process-wait').html('Please wait while deleting the news...');
		$.post('admin.php',{ type : 'deleteNews', id : $(this).data('id')},function(response){
			var response = $.parseJSON(response);
			if(response.status){
				$('.process-wait').html(response.msg);
				location.reload();
			}
		});
	});

});


function disableBtn(btnEl){
	$(btnEl).prop('disabled',true).val('Processing...').html('Processing..');
}

function enableBtn(btnEl,btnText){
	$(btnEl).prop('disabled',false).val(btnText).html(btnText);
}

function isLoggedIn(){
	$('.admin-page-session').addClass('hide');
	$.post('admin.php',{ type : 'loginCheck'},function(response){
		var response = $.parseJSON(response);
		if(!response.status){
			window.location.href = '../admin/';
		}
		$('.admin-page-session').removeClass('hide');
	});
}

function getNews(offset){
	$.post('admin.php',{ type : 'getNews', offset : offset},function(response){
		var response = $.parseJSON(response);
		if(response.status){
			var newsBody = '';

			var count = 1;
			$.each(response.news,function(i,item){
				newsBody += '<tr class="border-top-0">';
				newsBody += '<td>'+ count++ +'</td>';
				newsBody += '<td>'+item.date+'</td>';
				newsBody += '<td>'+item.title+'</td>';
				newsBody += '<td width="400">'+item.description.substring(1,40)+'...</td>';

				if(item.image != null)
					newsBody += '<td><img src="../uploads/'+item.image+'" width="100" alt=""/></td>';
				else
					newsBody += '<td>--</td>';
				newsBody += '<td>';
				newsBody += '<a href="edit-news.html?edit-news='+item.id+'" class="btn btn-sm"><i class="fa fa-edit"></i></a>';
				newsBody += '<a href="#" data-id="'+item.id+'" class="btn btn-sm delete-news"><i class="fa fa-trash"></i></a></td>';
				newsBody += '</tr>';
			});

			$('.news-tbody').html(newsBody);
			
		}else{
			alert('No news found');
		}
	});
}