<!DOCTYPE html>
<html>
	<head>
	 <meta http-equiv="X-UA-Compatible" content="IE=Edge">
	  <meta charset="UTF-8">
	  <title>
		  Online bahis - Futbol, Basketbol, Tenis ve daha birçok spor dalında Spor Bahisleri  Canlı Casino'da Oynayın, Slot'lar, Oyunlar ve Poker   Betin.com
	  </title>

 	  <link rel="stylesheet/less" type="text/css" href="/lib/less/base-less/bootstrap.less" />
 	  <link rel="stylesheet/less" type="text/css" href="/lib/less/register/register.less" />

	  <script src="/lib/js/base-js/jquery-1.11.0.min.js" type="text/javascript"></script>
 	  <script src="/lib/js/base-js/less-1.6.3.min.js" type="text/javascript"></script>
 	  <script src="/lib/js/base-js/bootstrap.min.js" type="text/javascript"></script>
 	  <script src="/lib/js/header/header.js" type="text/javascript"></script>
 	  <script src="/lib/js/register/register.js" type="text/javascript"></script>
 	  <script src="/assets/js/underscore-min.js" type="text/javascript"></script>

 	  <script>

 	  	function refreshCaptcha()
		{
		    return false; refreshCaptchaUrl = '/tutorials/hello_captcha_create/'+Math.random();
		    //console.log(refreshCaptchaUrl);
		    document.getElementById("captchaImg").src = refreshCaptchaUrl;     
		    console.log($('#captchaImg').attr('src'));
		    return false; // Do not do form submit;
		}


		$( document ).delegate(".captcha_link", "click", function() {
		  console.log($('#captchaImg').attr('src'));
		});

 	  </script>

	</head>
	<body>
<div class="page-wrapper"> 

<!-- Header Language Modal -->
<div class="modal fade" id="header-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
       		 <span class="header-modal-title">Bölgesel Ayarlar</span>
      </div>
      <div class="modal-body">
				<div class="header-modal-left">
							<span>Dil</span>

							<ul class="modal-language-table">
							<div class="modal-language-table-scroll">
								
									<li val="en" class="en"><span class="language-flag"><a href="#">English</a></span></li>
									<li val="it" class="it"><span class="language-flag"><a href="#">Italiano</a></span></li>
									<li val="de" class="de"><span class="language-flag"><a href="#">Deutsch</a></span></li>
									<li val="el" class="el"><span class="language-flag"><a href="#">Ελληνικά</a></span></li>
									<li val="nl" class="nl"><span class="language-flag"><a href="#">Nederlands</a></span></li>
									<li val="bg" class="bg"><span class="language-flag"><a href="#">Български</a></span></li>
									<li val="sr" class="sr"><span class="language-flag"><a href="#">Srpski</a></span></li>
									<li val="tr" class="tr selected"><span class="language-flag"><a href="#">Türkçe</a></span></li>
									<li val="ro" class="ro"><span class="language-flag"><a href="#">Romana</a></span></li>

							</div>
							</ul>
				</div>
				<div class="header-modal-right">
							<span>Saat dilimi seç</span>

							<ul class="modal-language-table-right">
							<div class="modal-language-table-scroll-right">
								<li> <strong> (GMT -12:00 hours) </strong> Eniwetok, Kwajalein</li>
								<li> <strong> (GMT -11:00 hours) </strong> Midway Island, Samoa</li>
								<li> <strong> (GMT -10:00 hours) </strong> Hawaii</li>
								<li> <strong> (GMT -9:00 hours) </strong> Alaska</li>
								<li> <strong> (GMT -8:00 hours) </strong> Pacific Time (US &amp; Canada)</li>
								<li> <strong> (GMT -7:00 hours) </strong> Mountain Time (US &amp; Canada)</li>
								<li> <strong> (GMT -6:00 hours) </strong> Central Time (US &amp; Canada), Mexico City</li>
								<li> <strong> (GMT -5:00 hours) </strong> Eastern Time (US &amp; Canada), Bogota, Lima, Quito</li>
								<li> <strong> (GMT -4:00 hours) </strong> Atlantic Time (Canada), Caracas, La Paz</li>
								<li> <strong> (GMT -3:30 hours) </strong> Newfoundland</li>
								<li> <strong> (GMT -3:00 hours) </strong> Brazil, Buenos Aires, Georgetown</li>
								<li> <strong> (GMT -2:00 hours) </strong> Mid-Atlantic</li>
								<li> <strong> (GMT -1:00 hours) </strong> Azores, Cape Verde Islands</li>
								<li> <strong> (GMT)</strong> Western  Europe Time, London, Lisbon, Casablanca, Monrovia</li>
								<li> <strong> (GMT +1:00 hours) </strong> CET(Central Europe Time), Brussels, Copenhagen, Madrid, Paris</li>
								<li> <strong> (GMT +2:00 hours) </strong> EET(Eastern Europe Time), Kaliningrad, South Africa</li>
								<li> <strong> (GMT +3:00 hours) </strong> Baghdad, Kuwait, Riyadh, Moscow, St. Petersburg, Volgograd, Nairobi</li>
								<li> <strong> (GMT +3:30 hours) </strong> Tehran</li>
								<li> <strong> (GMT +4:00 hours) </strong> Abu Dhabi, Muscat, Baku, Tbilisi</li>
								<li> <strong> (GMT +4:30 hours) </strong> Kabul</li>
								<li> <strong> (GMT +5:00 hours) </strong> Ekaterinburg, Islamabad, Karachi, Tashkent</li>
								<li> <strong> (GMT +5:30 hours) </strong> Bombay, Calcutta, Madras, New Delhi</li>
								<li> <strong> (GMT +6:00 hours) </strong> Almaty, Dhaka, Colombo</li>
								<li> <strong> (GMT +7:00 hours) </strong> Bangkok, Hanoi, Jakarta</li>
								<li> <strong> (GMT +8:00 hours) </strong> Beijing, Perth, Singapore, Hong Kong, Chongqing, Urumqi, Taipei</li>
								<li> <strong> (GMT +9:00 hours) </strong> Tokyo, Seoul, Osaka, Sapporo, Yakutsk</li>
								<li> <strong> (GMT +9:30 hours) </strong> Adelaide, Darwin</li>
								<li> <strong> (GMT +10:00 hours) </strong> EAST(East Australian Standard), Guam, Papua New Guinea, Vladivostok</li>
								<li> <strong> (GMT +11:00 hours) </strong> Magadan, Solomon Islands, New Caledonia</li>
								<li> <strong> (GMT +12:00 hours) </strong> Auckland, Wellington, Fiji, Kamchatka, Marshall Island</li>
								</div>
							</ul>

				</div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary siz">Kaydet</button>
      </div>
    </div>
  </div>
</div>
<!-- Header Language Modal -->

<!-- Header Login Error Modal -->

<div class="modal header-login-error" id="login-error-modal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h2>Login Error</h2>
	</div>
	<div class="modal-body">
    	<div class="header-login-error-modal-left">
    		<p class="header-login-error-message">Üzgünüz, oturum açma bilgilerinizi tanıyamadık. Lütfen, tekrar deneyin</p>
			<form method="post" action="">
 				<div class="control-field">
					<label class="control-label" for="">Kullanıcı adı</label>
					<div class="controls">
						<input type="text" class="login-error-input" id="" name="">
					</div>
				</div>
				<div class="control-field">
					<label class="control-label" for="">Parola</label>
					<div class="controls">
						<input type="password" class="login-error-input" id="" name="">
					</div>
				</div>
				<div class="control-field">
					<div class="controls">
						<button type="submit" class="btn btn-primary" id="header-submit" name="header-submit">Oturum Aç</button>
						<span class="login-modal-login-error">Oturum Açılamadı. Lütfen tekrar deneyin</span>
					</div>
					<a href="#" class="login-modal-forgotten-details">Kullanıcı Adınızı/Parolanızı mı unuttunuz?</a>
				</div>
			</form>
		</div>
		<div class="header-login-error-modal-right">
			<div class="modal-register-container">
				<h3>Hesap açmaya ne dersiniz?</h3>
				<button class="getRegister-button">Hemen Üye Ol</button>
			</div>
		</div>
  </div>
</div>

<!-- Header Login Error Modal -->	
	
	<!-- Header Start -->		
		<div class="headerWrapper">
			<div class="headerContainer">

				<div class="header-left">
						<div class="header-left-top">
							<div class="logo"> <img src="../lib/images/logoSmall.png" alt="" class="logo"></div>
							<div class="getRegister">
								<strong>Betin'de hesabınız yok mu?</strong>
								<button class="getRegister-button">Hemen Üye Ol</button>
							</div>
						</div>
						<div class="header-left-bottom">
							<div class="menu">
								<ul class="navigation">
									<li><a href="#">Spor Bahisleri</a></li>
									<li><a href="#">Canlı Bahis</a></li>
									<li><a href="#">Casino</a></li>
									<li><a href="#">Oyunlar</a></li>
									<li><a href="#">Poker</a></li>
									<li><a href="#">Sanal Futbol</a></li>
									<li><a href="#">Canlı Casino</a></li>
								</ul>
							</div>
						</div>
				</div>

				<div class="header-right">
						<div class="header-right-top">
							<ul class="header-tools">
								<li class="time"><a href="#">14:05:00 GMT</a></li>
								<li class="dropdown">
									<a class="dropdown-toggle" data-toggle="dropdown" href="#">Oranlar<b class="caret"></b></a>
											<ul class="dropdown-menu pull-right">
												<li><a data-target="#" class="selected" val="B">Ondalıklı Bahis Oranları</a></li>
												<li><a data-target="#" class="" val="A">Kesirli Bahis Oranları</a></li>
											</ul>
								</li>
								<li class="tr"><a href="#" data-toggle="modal" data-target="#header-modal"><span class="flag-img"> </span>Türkçe</a></li>
								<li class="help"><a href="#">Yardım</a></li>		
							</ul>
						</div>
						<div class="header-right-bottom">
							<form action="#" method="post" class="header-login-form">
								<input type="text" name="username" placeholder="Kullanıcı adı">
								<input type="password" name="password" placeholder="Parola">
								<button type="submit" class="btn btn-primary login-button" id="header-submit" name="header-submit">Oturum Aç</button>
							</form>
							<div class="header-remember">
								<input type="checkbox" name="remember"> 
								<label for="rememberme" class="header-rememberme">Beni hatırla</label>
							</div>
							<p class="header-forgotten"><a href="#" class="header-forgetten-text">Kullanıcı Adınızı/Parolanızı mı unuttunuz?</a></p>
						</div>
				</div>

			</div>
			<div class="header-border"> </div>
		</div>
	<!-- Header End -->

<!-- Content Start -->

<div class="contentContainer">
	<div class="register-left">
		<div class="register-title"> Kaydol </div>
		<div class="register-level">
			<ul>
				<li class="current">Kaydol</li>
				<li>Para Yatır</li>
				<li>Doğrulama</li>
			</ul>
		</div>
		<br clear="all">
		<p class="page-hint">Lütfen, bu formu yalnızca Latin alfabesini kullanarak, Türkçe karakterlere yer vermeden doldurun.</p>

		<script type="text/template" class="template">

			<form id="testForm" class="form-horizontal register-form" style='width:80%; margin:0' role="form" method="post" action="<%- formData.postUrl %>">
				<% _.each( formData.inputs, function( input ){

					switch(input.type) { 
						case 'textbox':
				%>
							<div class='row'>
								<div class='form-group col-sm-12 has-feedback'>
									<div class='col-sm-3'> 
										<label for="<%- input.name %>" class="control-label"> <%- input.label %></label>
										<span class="form-required"> *</span>
									</div>
									<div class='col-sm-6'  data-toggle="tooltip" data-placement="right" title="<%- input.title %>">
										<input type="text" name="<%- input.name %>" value="<%- input.value %>" id="<%- input.name %>" <%= input.attr %> /> 
									</div>
								</div>
							</div>
			  	<% 
		  				break; 
		  				case 'password': 
				%>
						  	<div class='row'>
								<div class='form-group col-sm-12 has-feedback'>
									<div class='col-sm-3'> 
										<label for="<%- input.name %>" class="control-label"> <%- input.label %></label>
										<span class="form-required"> *</span>
									</div>
									<div class='col-sm-6' data-toggle="tooltip" data-placement="right" title="<%- input.title %>">
										<input type="password" name="<%- input.name %>" id="<%- input.name %>" value="<%- input.value %>" <%= input.attr %>  /> 
									</div>
								</div>
							</div>
			  	<% 
				  		break;
				  		case 'dropdown':
		  		%>
				  			<div class='row'>
								<div class='form-group col-sm-12 has-feedback'>
									<div class='col-sm-3'> 
										<label for="<%- input.name %>" class="control-label"> <%- input.label %> </label>
										<span class="form-required"> *</span>
									</div>
									<div class='col-sm-6' title="<%- input.title %>">
										<select name="<%- input.name %>" id="<%- input.name %>" <%= input.attr %>>

		  		<%
		  									_.each(input.dataDropdown, function ( value, key ) {
		  										var selected;
		  										if(input.value == key){ selected = "selected='selected'" }
		  		%>
				  								<option <%= selected %> value = "<%- key %>"><%- value %></option>
		  		<%
				  							})
				%>
										</select>
									</div>
								</div>
							</div>
				<% 
						break;
						case 'groupDropdown' :
				%>
							<div class='row'>
								<div class='form-group col-sm-12 has-feedback'>
									<div class='col-sm-3'> 
										<label for="birthday" class="control-label"> <%- input.label %> </label>
										<span class="form-required"> *</span>
									</div>
									<div class='col-sm-6 date-container'  data-toggle="tooltip" data-placement="right" title="<%- input.title %>">
										
										<% _.each(input.groupDropdown, function ( value ) { %>
											<select name="<%- value.name %>" <%= value.formAttr %> >
												<option value=""> <%- value.firstOption %></option>
												<% _.each(value.dataDropdown, function ( row, key ) { var selected = ""; %>
												<% if(value.value == key){ selected = "selected='selected'" } %>
													<option <%= selected %> value="<%- key %>"> <%- row %></option>
												<% }) %>
											</select>

										<% }) %>
									</div>
								</div>
							</div>
				<%
						break;
						case 'subheader' : 
				%>
						<br clear="all"><h3 class="form-sectionTitle"><%= input.data %></h3>
				<%
						break;
						case 'verify' : 
				%>
							<div class='row form-pb25'>
								<div class='form-group col-sm-12 has-feedback'>
									<div class='col-sm-3'> 
										<label for="<%- input.name %>" class="control-label"><%- input.label %></label>
										<span class="form-required"> *</span>
									</div>
									<div class='col-sm-6'>
										<input type="text" name="<%- input.name %>" value="" id="<%- input.name %>"  class="form-control input-sm validation" /> 
										<input type="button" value="<%- input.bttn_value %>" id="verifybtn" class="" name="verifybtn" focused="0" onClick="verify()" />
									</div>
								</div>
							</div>
				<%
						break;
						case 'captcha' :
				%>
						<div class='row'>
							<div class='form-group col-sm-12 has-feedback'>
								
								<div class='col-sm-2'>
									<img src="/tutorials/hello_captcha_create" id="captchaImg" class="captcha_img"> 
								</div>
								<div class='col-sm-4'>
									<a href="#" class="capthca_link" onclick="refreshCaptcha()">Yeni bir güvenlik kodu al</a>
								</div>

							</div>
						</div>

				<%
						break;
					}
				%>

				<% }) %>

			</form>
		</script>
		<div id="registerForm"></div>

	</div> 



	<!-- Register Left End -->
		<div class="register-right-top"> 
			<a href="#">
			<img src="../lib/images/open_account_security.png" width="68" height="85" alt="Security">
			</a>
	          <h3>Güvenlik ve Gizlilik</h3>
	          <p>Güvenlik, tarafımızca çok ciddiye alınan bir konudur</p>
        </div>
        <div class="register-right-bottom">
        <a href="#">
        <img src="../lib/images/open_account_cs.png" width="68" height="85" alt="Contact Us">
        </a>
          <h3>İletişim</h3>
          <b class="register-right-bottom-email">e-posta</b>
          <span class="register-right-bottom-text">yardim@betin.com</span>
          <span class="register-right-bottom-text">
          Tüm iletişim detaylarımız için lütfen yukarıdaki imaja tıklayınız.
          </span>
        </div>
			
<br clear="all">
</div>

<!-- Content End -->
</div>

<script type="text/javascript">
	function createHtml(formData)
	{	
		//console.log(formData);
		var template = $(".template").html();
	    $("#registerForm").html(_.template(template,{formData:formData}));
	}

	createHtml(<?php echo $jsonData;?>);
	function formpost(){
		$.post( $('#testForm').attr( "action" ), $( "#testForm" ).serialize(), function(data){
	    	//console.log(data);
	    });
	}

	$( document ).ready(function() {
		//refreshCaptcha();
	});

</script>

</body>
</html>