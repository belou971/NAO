function logInFacebook() {
	FB.login(function (response) {
		if(response.status === 'connected') {

			FB.api('/me/permissions', function(response) {
				if(response.data[1].status === 'declined') {
					$(function() {
						$('<div class="alert alert-danger alert-dismissable mb-5"><button type="button" class="close" data-dismiss="alert">&times;</button>Pour continuer avec Facebook, nous avons besoin d\'avoir accès à votre adresse e-mail</div>').prependTo('.transparent');
					});

					FB.api('/me/permissions', 'delete', function(result) {
						console.log(result);
					});
				}
				else
				{
					FB.api('/me?fields=first_name,last_name,email', function (userData) {
						$(function() {
							$('#ts_naobundle_user_name').attr({value: userData.last_name, readonly: 'readonly'});
							$('#ts_naobundle_user_surname').attr({value: userData.first_name, readonly: 'readonly'});
							$('#ts_naobundle_user_email_first').attr({value: userData.email, readonly: 'readonly'});
							$('#ts_naobundle_user_email_second').attr({value: userData.email, readonly: 'readonly'});
						});
					})
				}
			})
		}
		else {

			$(function() {
				$('<div class="alert alert-danger alert-dismissable mb-5"><button type="button" class="close" data-dismiss="alert">&times;</button>Impossible récupérer vos informations Facebook</div>').prependTo('.transparent');
			});
		}
	}, {scope: 'public_profile,email'});
}

(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;

	js = d.createElement(s); js.id = id;
	js.src = 'https://connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v3.0&appId=154192048755869&autoLogAppEvents=1';
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

function logInGoogle() {

	gapi.load('auth2', function() {
		auth2 = gapi.auth2.init({
			client_id: '912937728148-kuafnku19kulcgn8l1b7970nd8jrcrcn.apps.googleusercontent.com',
			cookiepolicy: 'single_host_origin',
			scope: 'profile email'
		});

	auth2.attachClickHandler(document.getElementById('gButton'), {},
		function(googleUser) {
			var profile = googleUser.getBasicProfile();
			$(function() {
				$('#ts_naobundle_user_name').attr({value: profile.getFamilyName(), readonly: 'readonly'});
				$('#ts_naobundle_user_surname').attr({value: profile.getGivenName(), readonly: 'readonly'});
				$('#ts_naobundle_user_email_first').attr({value: profile.getEmail(), readonly: 'readonly'});
				$('#ts_naobundle_user_email_second').attr({value: profile.getEmail(), readonly: 'readonly'});
			});
		}, function(error) {
			$(function() {
				$('<div class="alert alert-danger alert-dismissable mb-5"><button type="button" class="close" data-dismiss="alert">&times;</button>Impossible récupérer vos informations Google</div>').prependTo('.transparent');
			});
		});
	});
}
