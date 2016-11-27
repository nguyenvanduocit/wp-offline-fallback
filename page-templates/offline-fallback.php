<?php
/**
 * Template Name: Offline Fallback
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php the_title() ?></title>
	<!-- Styles -->
	<style>
		html, body {
			background-color: #fff;
			color: #636b6f;
			font-family: 'Open Sans', sans-serif;
			font-weight: 100;
			height: 100vh;
			margin: 0;
		}

		.full-height {
			height: 100vh;
		}

		.flex-center {
			align-items: center;
			display: flex;
			justify-content: center;
		}

		.position-ref {
			position: relative;
		}

		.content {
			text-align: center;
			max-width: 600px;
		}

		.title{
			font-size: 60px;
			line-height: 65px;
			text-decoration: none;
			color: #636b6f;
			text-transform: uppercase;
		}

		.text p{
			color: #636b6f;
			font-size: 16px;
			font-weight: 400;
			letter-spacing: 1px;
			margin: 0;
			line-height: 25px;
		}

		.m-b-md {
			margin-bottom: 30px;
		}
	</style>
</head>
<body>
<div class="flex-center position-ref full-height">
	<div class="content">
		<?php while (have_posts()):the_post() ?>
		<div class="title m-b-md"><?php the_title() ?></div>
		<div class="text">
			<?php the_content() ?>
		</div>
		<?php endwhile; ?>
	</div>
</div>
</body>
</html>

