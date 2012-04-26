<html>
	<head>
		<title>_{site.name} - ${title}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="url{css/bootstrap.min.css}" rel="stylesheet">
		<link href="url{css/bootstrap-responsive.min.css}" rel="stylesheet">
		<script src="url{js/jquery-1.7.1.min.js}"></script>
		<script src="url{js/bootstrap.min.js}"></script>
		<script src="url{js/main.js}"></script>
	</head>
	<body>
		<parts: admin/navbar />
		<div class="container">
		${_innerHtml}
		</div>
	</body>
</html>