<!DecoType HTML>

<html lang="en">
	<head>
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<title>E-Store </title>
        <link rel="stylesheet" type="text/css" href="estyle.css">

        <link href="https://cdn.materialdesignicons.com/3.6.95/css/materialdesignicons.min.css" media="all" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />






		<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<!------ Include the above in your HEAD tag ---------->

		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">






      	<link style="width: 100%;height: 100%" rel="tab icon" href="store_images/logoicon.ico"/>
    
    </head>

	<?php
		ini_set('error_reporting', 'E_COMPILE_ERROR|E_RECOVERABLE_ERROR|E_ERROR|E_CORE_ERROR');

		include 'connection.php';
		$conn = OpenCon();

		if (mysqli_connect_errno())
		{
			echo "Unable to connect to server " . mysqli_connect_error();
		}


		session_start();

		if($_SESSION['logged_in'] == true)
		{
			if($_SESSION['admin'] == true)
			{
				echo '<script>window.history.back();</script>';
				exit;
			}
			$un=$_SESSION["fullname"];
			$p=$_POST["password"];
			$wallet=$_SESSION['wallet'];
			$username=$_SESSION['username'];
			echo '<script>document.getElementById("disp_logout").style.display="block";</script>';
		}
		else
		{
			echo '<script>You are not logged in. Login to continue</script>'; 
			echo '<meta http-equiv="refresh" content="0; URL=\'login.php\'" /> ';
			exit;
		}

		$qu = 'SELECT wallet from userss where user_id='.$username;
		$result=mysqli_query($conn,$qu);
		$row=$result->fetch_assoc();
		$wallet = $row['wallet'];
		$_SESSION['wallet']=$wallet;	
	?>

	<body style="font-size: 15px;">
    	<!-- Top navigation Bar ( LOGO + Other Buttons) -->
    	<header>        
            <div class="logo"><a href="ehome.php"><img class="logoClass" src="store_images/logo.png"></a></div>
            <nav role = "header">
            	<ul>
            		<li><a href="ehome.php" class="active">HOME</a></li>
            		<li><a href="trackmyorder.php"> TRACK MY ORDER</a></li>
            		
            		

            		<label for="profile2" class="profile-dropdown">
						<input type="checkbox" id="profile2">
						<img src="https://cdn0.iconfinder.com/data/icons/avatars-3/512/avatar_hipster_guy-512.png">
					   	<span> <?php echo'<font size="1.5rem" style="color:white;">'.$fn.'</font>' ?></span>
					   	
					   	<label for="profile2"><i class="mdi mdi-menu"></i></label>
					   	<ul>
					   		<li><a href="#" class="mdi mdi-account">Account</a></li>
					   		<li><a href="#" class="mdi mdi-textbox-password" style="display: flex;"><button type="button" class="btn btn-default btn-lg" id="changepasswordBtn" style="color: inherit;background-color: inherit;border: none;margin: 0;padding: 0;width: 100%"><font size="2"><b>Change Password</b></font></button></a></li>
					   		<li><a href="logout.php" target="_self" class="mdi mdi-logout">Logout</a></li>
					   		<?php
			            		echo 
			            		'<li>
			            			<a class="mdi mdi-wallet" id="walletBtn" href="#">
			            				<font size = "2">Rs. '.$wallet.'</font>
			            			</a>
			            		</li>'
		            		?>
					   		<li><a href="addcouponcode.php" class="mdi mdi-credit-card-plus">Add Money</a></li>
					   	</ul>
					</label>
            	</ul>
            </nav>
            <div class="menu-toggle"><i class="fa fa-bars" aria-hidden="true"></i></div>
    	</header>
    	
    	<br>
    	<br>
    	<br>

    	<!-- Categories Button + Search Bar + Cart -->
    	<div class="catsearchDiv">

    		<!-- Categories Button -->
    		<nav role = "catmenu" id='categorymenu'>
		        <ul>
		            <li role = "plusminusANDtext"><a href='#' class="plusMinus" style="margin-top: 1px;height: 10px;box-sizing : border-box;border-radius: 5px;outline: none;border: 4px solid #2196f3;text-align: center;" >CATEGORIES</a>
		                <ul>
		                	<?php
		                		$query = 'SELECT category_name FROM category';
		                		$result = mysqli_query($conn,$query);
		                		
		                		while($row = $result->fetch_assoc())
		                		{
		                			echo "<li role = 'plusminus'><a href='#'>".$row['category_name']."</a> </li>";

		                		}				             
		                
			            	?>
			         
		              	</ul>
		            </li>
		        </ul>
		    </nav>

		    <!-- Search bar -->
		    <form class="search" method="POST" action="search.php">
		        <input type="text" name="sp" class="searchTerm" pattern="\S+.*"/ placeholder="Search in E-Store ...">
		        <input type="submit" class="searchButton">
		    	
		    	<select class="filterclass" name="search_filters" id="search_filters" onchange="searchfilters(this.options[this.selectedIndex].value)">
					<div style="display: flex;padding: 0;margin: 0;border-radius: 10px;">
						<option value="no_filters">No filters</option>
						<option value="price_lth">Price Low to High </option>
						<option value="price_htl">Price High to Low</option>
						<option value="date_added_r">Recent Products</option>
						<option value="date_added_o">Old Added Products</option>
					</div>
				</select>
		    </form>
		    
		    <!-- Cart -->
		    <div class="cart"><a href="mycart.php"><img class="cartClass" src="store_images/cart.png"></a></div>
    	</div>

    	<br>
    	<br>

		<div class="container" style="font-size: 25px;">
				<div class="row justify-content-center">
					<div class="col-12 col-md-8 col-lg-6 pb-5">

                    	<!--Form with header-->
                    	<form method="POST" action="addmoney.php">
                        	<div class="card border-primary rounded-0">
                            	<div class="card-header p-0">
                                	<div class="bg-info text-white text-center py-2">
                                    	<h3><i class="fas fa-money-bill-alt"></i> Add Money</h3>
                                    	<p class="m-0">using the coupon code</p>
                                	</div>
                            	</div>
                            	<div class="card-body p-3">

                                <!--Body-->
                                <div class="form-group" style="size: 50px">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i style="size: 50px" class="mdi mdi-credit-card-plus"></i></div>
                                        </div>
                                        <input name="coupon" type="text" class="form-control" id="coupon" placeholder="Enter the coupon code" required>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <input type="submit" value="Add Money" class="btn btn-info btn-block rounded-0 py-2">
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--Form with header-->
                </div>
			</div>
		</div>
	
		

		<script type="text/javascript">
			function checkpass()
			{
				var passa = document.getElementById("passaa").value;
				var passb = document.getElementById("passbb").value;
				var z=document.getElementById('oldppa').value;
					
				if(passa == passb)
				{
					if(z == "" || passa=="" || passb=="")
					{
						document.getElementById("chpp").disabled = true;
					}
					else
					{
						document.getElementById("chpp").disabled = false;
					}
				}
				else
				{
					
					document.getElementById("chpp").disabled = true;
				}
				 
			}
			function showhidepass()
			{
				var x=document.getElementById("passaa");
				var y=document.getElementById("passbb");
				if(x.type == "password")
				{
					x.type="text";
				}
				else if(x.type == "text")
				{
					x.type="password";
				}
			}
			$(document).ready(function(){
    			$('.menu-toggle').click(function(){
    				$('nav').toggleClass('active')
    			})
    		})
    		$(document).ready(function(){
				$(document).on("click", "#changepasswordBtn", function(event){
			    $("#changepasswordModal").modal();
				});
			});


			function searchfilters(name){
				 var filter;
				 filter = 'nf';
				 if(name == "price_lth")
				 {
					filter = 'plth'
				 }
				 else if(name == "price_htl")
				 {
					filter = 'phtl'; 
				 }
				 else if(name == "date_added_r")
				 {
					filter = 'rp'; 
				 }
				 else if(name == "date_added_o")
				 {
					filter = 'oap'; 
				 }
				 else if(name == "no_filters")
				 {
					filter = 'nf';
				 }
				 document.cookie="filter="+filter;
			}


		</script>






	</body>
</html>