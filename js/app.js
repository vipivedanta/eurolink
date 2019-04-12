$(document).ready(function(e){

	if($('.admin-page-session').length > 0){
		isLoggedIn();
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

		var data = {
			type : 'save',
			title : title,
			date : date,
			description : description
		};

		$.post('admin.php',data,function(response){
				var response = $.parseJSON(response);
				if(response.status == false){
					alert('Could not save the news');
					return false;
				}

				window.location.href = 'view-news.html';
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