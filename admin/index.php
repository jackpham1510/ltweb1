<?php
	require_once "../server/index.php";

	session_start();

	if (isset($_SESSION['username'])){
		header('Location: '.Config::getValue('admin_base').'/Dashboard.php');
	}
	
	require_once "component/Head.php";
?>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="login-panel panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Please Sign In</h3>
					</div>
					<div class="panel-body">
						<?php if (isset($_SESSION['login_error'])){ ?>
							<div class="alert alert-danger" role="alert">
								Login failed, please try again!
							</div>
						<?php } ?>
						<form role="form" action="./Login.php" method="POST">
							<fieldset>
								<div class="form-group">
									<input class="form-control" placeholder="Username" name="username" type="text" autofocus>
								</div>
								<div class="form-group">
									<input class="form-control" placeholder="Password" name="password" type="password" value="">
								</div>
								<!-- Change this to a button or input when using this as a form -->
								<button type="submit" class="btn btn-lg btn-success btn-block">Submit</button>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
