<?php
$conn = mysqli_connect('localhost','root','','contact_db') or die('connection failed');
$db = "contact_db";
mysqli_select_db($conn,$db);

global $conn;
?>


<?php

 
require_once 'config.php';

$permissions = ['email']; //optional

if (isset($accessToken))
{
	if (!isset($_SESSION['facebook_access_token'])) 
	{
		//get short-lived access token
		$_SESSION['facebook_access_token'] = (string) $accessToken;
		
		//OAuth 2.0 client handler
		$oAuth2Client = $fb->getOAuth2Client();
		
		//Exchanges a short-lived access token for a long-lived one
		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
		$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
		
		//setting default access token to be used in script
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	} 
	else 
	{
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	}
	
	
	//redirect the user to the index page if it has $_GET['code']
	if (isset($_GET['code'])) 
	{
		header('Location: ./');
	}
	
	
	try {
		$fb_response = $fb->get('/me?fields=name,first_name,last_name,email');
		$fb_response_picture = $fb->get('/me/picture?redirect=false&height=200');
		
		$fb_user = $fb_response->getGraphUser();
		$picture = $fb_response_picture->getGraphUser();
		
		$_SESSION['fb_user_id'] = $fb_user->getProperty('id');
		$_SESSION['fb_user_name'] = $fb_user->getProperty('name');
		$_SESSION['fb_user_email'] = $fb_user->getProperty('email');
		$_SESSION['fb_user_pic'] = $picture['url'];
		
		
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		echo 'Facebook API Error: ' . $e->getMessage();
		session_destroy();
		// redirecting user back to app login page
		header("Location: ./");
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		echo 'Facebook SDK Error: ' . $e->getMessage();
		exit;
	}
} 
else 
{	
	// replace your website URL same as added in the developers.Facebook.com/apps e.g. if you used http instead of https and you used
	$fb_login_url = $fb_helper->getLoginUrl('http://localhost:3000/Documents/Lap_Trinh_API/Web_API_NhaKhoa/login.php', $permissions);
}
?>




<!DOCTYPE html>
<html lang="en">

<head>


    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">



<!-- Custom fonts for this template-->
<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

<!-- Custom styles for this template-->
<link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>




<!-- login success -->
<?php if(isset($_SESSION['fb_user_id'])): ?>
	<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
	  <a class="navbar-brand" href="<?php echo BASE_URL; ?>">HOME</a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
		<span class="navbar-toggler-icon"></span>
	  </button>
	  <div class="collapse navbar-collapse" id="collapsibleNavbar">
		<ul class="navbar-nav">
		  <li class="nav-item">
			<a class="nav-link" href="https://www.youtube.com/@lethienkhang6568/videos"><i class="fa fa-youtube"> YouTube</i></a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" href="https://www.facebook.com/le.thienkhang.1/"><i class="fa fa-facebook">Facebook</i></a>
		  </li>
		  
		  <li class="nav-item">
			<a class="nav-link" href="logout.php">Logout</a>
		  </li>    
		</ul>
	  </div>  
	</nav>

	<div class="container" style="margin-top:30px">
	  <div class="row">
		<div class="col-sm-2">
		  <h2>About Me</h2>
		  <h5>Profile Picture:</h5>
		  <div class="fakeimg"><?php echo'<h3><img src='.$_SESSION['fb_user_pic'].'/><h3/>'; ?></div>
		  <hr class="d-sm-none">
		</div>
		<div class="col-sm-2"></div>
		<div class="col-sm-8">


		  <h3>User Info</h3>
		  <ul class="nav nav-pills flex-column">
			<li class="nav-item">
			  <a class="nav-link" >Facebook ID: <?php echo  $_SESSION['fb_user_id']; ?></a>
			</li>
			<li class="nav-item">
			  <a class="nav-link">Full Name: <?php echo $_SESSION['fb_user_name']; ?></a>
			</li>
			<li class="nav-item">
			  <a class="nav-link">Email: <?php echo $_SESSION['fb_user_email']; ?></a>
			</li>
		  </ul>
		  
		</div>
	  </div>
	</div>
<!-- NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN --> 


<!-- if user not login --> 
<?php else: ?>
    <title>Login</title>

<body class="bg-gradient-primary">

<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5" style="border: 5px solid;margin: auto;width: 50%;padding: 10px;">
                <div class="card-body p-0" >
                    <!-- Nested Row within Card Body -->
                    
                            <div class="p-5">
                            <?php
                            if($_POST){

                                $user_name=$_POST['user_name'];
                                $user_pass=$_POST['user_pass'];
                                $result=mysqli_query($conn,"SELECT * from contact_form where email='$user_name' and password='$user_pass'");
                                $row=mysqli_fetch_assoc($result);
                                
                                if($row){
                                  header("Location:index.php");
                                }else{
                                    echo '<p style="color:red">Tên đăng nhập hoặc mật khẩu không đúng!</p>';
                                }
                                }
                                ?>							
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Đăng nhập</h1>
                                </div>


                                <form class="user" action="login.php" method="post">
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user" required
                                            id="exampleInputEmail" aria-describedby="emailHelp"
                                            placeholder="Enter Email Address..." name="user_name">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" required
                                            id="exampleInputPassword" placeholder="Password"  name="user_pass">
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" id="customCheck">
                                            <label class="custom-control-label" for="customCheck">Remember
                                                Me</label>
                                        </div>
                                    </div>
                                    <button type="submit"class="btn btn-primary btn-user btn-block">
                                        Login
                                    </button>
                                    <hr>
                                    <div class="login-form">
		                                <form action="" method="post">
			                                <div class="text-center social-btn">
		                            		    <a href="<?php echo $fb_login_url;?>" class="btn btn-primary btn-block"><i class="fa fa-facebook"></i> Sign in with <b>Facebook</b></a>
		                            	    </div>
		                                </form>
	                                </div>
                                    <?php endif ?>     
                                </form>

                                <hr>
                                
                            </div>
                        
                </div>
            </div>

        </div>

    </div>

</div>

	

    
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>













