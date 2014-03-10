<html>
	<head>
		<title><?php echo $title ?></title>
		<link  href="/assets/css/style.css" rel="stylesheet" type="text/css" />

		<meta charset="utf-8">
	</head>

<body>
		<?php echo $header; //echo $jsonData;?>

		<div id="clear"></div>
		<div id="containerbox">
 			<div id="content">
				
				<div id="navigation">
					<?php echo $this->url->anchor('/home', 'Home') ?> » <b> Contact</b>
				</div>

				<h1 class="h1">Contact</h1>

				<div id="createpost">
					<i>Fields with * are required.</i>
					
					<script type="text/template" class="template">

						<form id='testform'  action="<%- formData.postUrl %>">
						<% _.each( formData.inputs, function( input ){ %>

						<% switch(input.type) { 
							case 'textbox': %>
								<label><%- input.name%></label>
							  	<input type="textbox" />
						  <% break; case 'password': %>
							  	<label><%- input.name%></label>
							  	<input type="password" />
						  <% break; case 'dropdown': %>
						  		<select>

						  		<% 
						  		 //_.each( formData.inputs, function( input ){ 
						  			_.each(input.dataDropdown, function (item) { 
						  		%>

						  			<option value="<%- item.key %>"><%- item.name %></option>

						  		<%
						  			})
					  			%>

								</select>
						<% break; } %>

						<% }) %>

						<input type="button" value="Gönder" onClick="formpost()" />

						</form>
					</script>
					
				</div>
				
				<div id="target">&nbsp;</div>

			</div>

			<script src="http://code.jquery.com/jquery-1.9.0.js"></script>
			<script type="text/javascript" src="http://underscorejs.org/underscore-min.js"></script>

			<script type="text/javascript">

				

				function createHtml(formData)
		        {	
		        	//console.log(formData);
		        	var template = $(".template").html();
		            $("#target").html(_.template(template,{formData:formData}));    
		        }

		        
	            createHtml(<?php echo $jsonData;?>);
		        
	            function formpost(){
	            	
	            	$.post( $('#testform').attr( "action" ), $( "#testform" ).serialize(), function(data){
		            	console.log(data);
		            });	alert('test');
	            }
	            

			</script>

			<?php echo $sidebar ?>
			<?php echo $footer ?>
		</div>
</body>

</html>