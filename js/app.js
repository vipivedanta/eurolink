$(document).ready(function(e){

	if($('.admin-page-session').length > 0){
		isLoggedIn();
	}

	if( $('.get-news').length > 0){
		getNews(0);
	}

	if( $('.edit-news-page').length > 0){
		getIndNews();
	}

	$('body').on('click','.pagination a.clickable',function(e){
		e.preventDefault();
		var page = $(this).data('page');
		getNews(page);
	});

	$('body').on('click','.page_pagination_two a.clickable-1',function(e){
		e.preventDefault();
		var page = $(this).data('page');
		fetchGuestNews(page);
	});

	if( $('.guest-news-events').length > 0 ){
		fetchGuestNews(0);
	}

	if( $('.intro-news').length > 0 ){
		fetchIntroNews();
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

	//logout
	$('.mdi-logout-variant').closest('a').click(function(e){
		e.preventDefault();
		var link = $(this);
		$(this).html('Signing out...');
		$.post('admin.php',{ type : 'signout'},function(response){
			var response = $.parseJSON(response);
			if(response.status){
				window.location.href = '../admin/';
			}else{
				alert('Could not sign out');
				$(link).html('Sign out');
			}
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

		var btnEl = $(this);
		disableBtn(btnEl);

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
		       	enableBtn(btnEl,'Add');
		         var response = $.parseJSON(response);
					if(response.status == false){
						alert('Could not save the news');
						return false;
					}

					window.location.href = 'view-news.html';
		       }
		});	

	});

	$('.update-news').click(function(e){
		var btnEl = $(this);
		disableBtn(btnEl);
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
		formData.append('type','updateNews');
		formData.append('title',title);
		formData.append('date',date);
		formData.append('description',description);
		formData.append('id',getUrlParameter('edit-news'));

		$.ajax({
		       url : 'admin.php',
		       type : 'POST',
		       data : formData,
		       processData: false,  // tell jQuery not to process the data
		       contentType: false,  // tell jQuery not to set contentType
		       success : function(response) {
		       	enableBtn(btnEl,'Update');
		         var response = $.parseJSON(response);
					if(response.status == false){
						alert('Could not update the news');
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

	$('.change-password').click(function(e){
		e.preventDefault();
		var btnEl = $(this);
		var old_password = $('#old_password').val();
		var new_password = $('#new_password').val();
		var repeat_password = $('#repeat_password').val();

		var data = {
			type : 'changePassword',
			old_password : old_password,
			new_password : new_password,
			repeat_password : repeat_password
		};

		disableBtn(btnEl);

		$('.alert').addClass('hide');
		$.post('admin.php',data,function(response){
			enableBtn(btnEl,'Chnage Password');
			var response = $.parseJSON(response);
			if(!response.status){
				$('.alert-danger').html(response.msg).removeClass('hide');
			}else{
				$('.alert-success').html(response.msg).removeClass('hide');
			}
		});
	});

	if( $('.main-new-details').length > 0){
		getNewsDetails();
	}

	$('.contact-btn').click(function(e){
		e.preventDefault();
		var formdata = new FormData( $('.contact-form')[0] );
		formdata.append('type','contact');
		$('input,textarea').removeClass('border-red');
		var btn = $(this);
		disableBtn(btn);
		$.ajax({
		    url: 'contact.php',
		    data: formdata,
		    type: 'POST',
		    contentType: false,
		    processData: false,
		    success : function(response){
		    	enableBtn(btn,'Submit Here');
		    	var response = $.parseJSON(response);
		    	if(!response.status){
		    		$('input[name="'+response.field+'"],textarea[name="'+response.field+'"]').addClass('border-red');
		    		return false;
		    	}else{
		    		$('<div class="alert alert-success">'+response.msg+'</div>').insertBefore('form');
		    	}
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
			$('.pagination').html(response.links);
			
		}else{
			alert('No news found');
		}
	});
}

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};


function getIndNews(){
	 var newsID = getUrlParameter('edit-news');
	 $.post('admin.php',{ type : 'getIndNews', id : newsID}, function(response){
	 		var response = $.parseJSON(response);
	 		if(response.status){
	 			$('.news-fetch-alert').remove();
	 			$('#title').val(response.news.title);
	 			$('#date').val(response.news.date);
	 			$('#description').val(response.news.description);

	 			if(response.news.image != null && response.news.image != '')
	 				$('.uploaded-image').html('<img src="../uploads/' + response.news.image + '" width="100" height="100" />');
	 			else
	 				$('.uploaded-image').html('No image uploaded');
	 		}else{
	 			$('.news-fetch-alert').html('Could not fetch the news item..');
	 		}
	 });
}

function fetchGuestNews(offset){
	$.post('guest.php',{ type : 'news',offset:offset}, function(response){
		var response = $.parseJSON(response);
		if(!response.status){
			$('.no-news').removeClass('hide');
			$('.all-news').addClass('hide');
		}else{
			$('.no-news').addClass('hide');
			$('.all-news').removeClass('hide');

			var news_html = '';
			$('.news-items').html('');
			$.each(response.news,function(i,item){
				var date2 = moment(new Date(item.date));

				if(item.image == null || item.image == '')
					item.image = 'https://via.placeholder.com/150?text=no+image';
				else
					item.image = 'uploads/' + item.image;


				news_html = '<div class="news-block col-lg-4">\
                    <div class="inner-box">\
                        <div class="image">\
                            <img src="'+item.image+'" alt="">\
                            <a href="news-details.html?news='+item.id+'&'+item.slug+'" class="overlay-link"><span class="icon-search"></span></a>\
                        </div>\
                        <div class="lower-content">\
                            <ul class="post-info">\
                                <li class="category">'+date2.format('MMMM')+' '+date2.format('DD')+', '+date2.format('YYYY')+'</li>\
                            </ul>\
                            <h4><a href="news-details.html?news='+item.id+'&'+item.slug+'">'+item.title+'</a></h4>\
                            <div class="text">'+item.description.substring(1,50)+'</div>\
                        </div>\
                    </div>\
                </div> '

                $('.news-items').append(news_html);
			});

			$('.pagination-holder').html(response.links);
		}
	});
}

function getNewsDetails(){
	var newsID = getUrlParameter('news');
	$.post('guest.php',{ type:'indiNews', id : newsID},function(response){
		var response = $.parseJSON(response);
		if(!response.status){
			alert(response.msg);
			window.location.href = 'news-and-events.html';
		}

		$('.news-title').html(response.news.title);
		$('.news-body').html(response.news.description);
		$('.news-img').attr('src','uploads/'+response.news.image);
		$('.news-date').html( moment(new Date(response.news.date)).format('DD') + '<br/>' + moment(new Date(response.news.date)).format('MMMM'));
	});
}

function fetchIntroNews(){
	$.post('guest.php',{ type : 'intro_news'},function(response){
		var response_2 = $.parseJSON(response);
		if(response_2.status){
			$('.intro-news-div').removeClass('hide');
			$('.mission-n-vision').removeClass('col-lg-12').addClass('col-lg-6');
			var html = '<h4>News & Events</h4>';
			$.each(response_2.news,function(i,news){
				html += '<div class="news-item">\
                            <h4>'+news.title+'</h4>\
                            <p>'+news.description.substring(0,60)+'...</p>\
                        </div>'
				$('.intro-news-div').append(html)
				html = '';
			})
		}
	});
}