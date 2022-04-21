<!DOCTYPE html>
<html lang="en">
<head>
    <title>Real Ajax Uploader - a jQuery multi uploader</title>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/ajaxupload-min.js"></script>
	<link rel="stylesheet" href="css/classicTheme/style.css" type="text/css" media="all" />
</head>
<body>
	<div class="demo"></div>
</body>
<script type="text/javascript">
	$('.demo').ajaxupload({
		url: 'upload.php',
		autoStart: true,
		remotePath: 'my',
		thumbWidth: 150,
		thumbPostfix: '',
		allowExt: ['jpg'],
		thumbPath:'my/thumbs/',
		maxFiles: 3,
		onInit: function()
		{
			this.removeFiles.hide();
			this.uploadFiles.hide();
		}
	});
</script>
</html>