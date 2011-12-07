<html>
	<head>
		<script type = "text/javascript" src = "https://ajax.googleapis.com/ajax/libs/jquery/1.6.3/jquery.min.js"></script>
	</head>

	<body>
	                <script>
                        $(document).ready(function() {
                                $('#trigger').click(function() {
					$.post('http://huppi.info/shout/poster.php',
					       $('#message').serializeArray(),
					       function(data) {
					       		$('#posts').append('<div id = "'+data.id+'" style="display: none;">'+data.timestamp+': '+data.message+'</div>');
							$('#'+data.id).fadeIn();
			
						},
					       'json'
					);
				});
                        });
                </script>

		<div id = "form">
			Message: <input type = "text" id = "message" name = "message"></input><br />
			<input type = "submit" id = "trigger" />
		</div>
		<div id = "posts">
			<?php
				$posts = unserialize(file_get_contents('posts.txt'));
				foreach($posts AS $post) {
					echo '<div id = "'.$post['id'].'">'.$post['timestamp'].': '.$post['message'].'</div>';
				}
			?>
		</div>
	</body>
</html>
