<!DecoType HTML>

<html lang="en">
	<head>
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<title>E-Store  </title>
        <link rel="stylesheet" type="text/css" href="estyle.css">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

        <link href="https://cdn.materialdesignicons.com/3.6.95/css/materialdesignicons.min.css" media="all" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>




		<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


      	<link style="width: 100%;height: 100%" rel="tab icon" href="store_images/logoicon.ico"/>
    
    </head>

	<?php
		ini_set('error_reporting', 'E_COMPILE_ERROR|E_RECOVERABLE_ERROR|E_ERROR|E_CORE_ERROR');

		include 'connection.php';
		$conn = OpenCon();

		session_start();

		if($_SESSION['logged_in'] == false)
		{
			echo '<script>alert("You are not logged in.") </script>.';
			echo '<meta http-equiv="refresh" content="0;url=login.php">';
			exit;
			
		}
		if($_SESSION['admin'] == true)
		{
			echo '<script>window.history.back();</script>';
			exit;
		}


		session_start();
		$un=0;
		if($_SESSION['logged_in'] == true)
		{
			$fn=$_SESSION["fullname"];
			$wallet=$_SESSION["wallet"];
			$un = $_SESSION["username"];
		}
		else
		{
			if(isset($_POST['user_name'],$_POST['password']) == false)
			{
				if($_SESSION['logged_in'] == false)
				{
					echo '<script>alert("You must login to continue.")</script>';
					echo '<meta http-equiv="refresh" content="0; URL=\'login.php\'" /> ';
					exit;
				}
			}
			$un=$_POST["user_name"];
			$p=$_POST["password"];
			//echo $un."<br>".$p."<br>";
			$query = "SELECT * FROM userss WHERE user_id = ";
			$query=$query.$un;
			$result = mysqli_query($conn, $query);
			//echo $result;
			$number_of_rows = $result->num_rows;
			if($number_of_rows == 1)
			{
					$row=$result->fetch_assoc();
					$pass=$row["password"];
					$fn=$row["first_name"];
					$ln=$row["last_name"];
					$wallet=$row["wallet"];
					$full_name=$fn." ".$ln;
					if($p == $pass)
					{
						$_SESSION["admin"]=false;
						$_SESSION["logged_in"]=true;
						$_SESSION["username"]=$un;
						$_SESSION["fullname"]=$full_name;
						$_SESSION["wallet"]=$wallet;
					}
					else
					{
						echo "Invalid Password <br>";
						exit;
					}
			}
			else
			{
				echo "Invalid User_ID <br>";
				exit;
			}		 
		}
		
		$qu = 'SELECT wallet from userss where user_id='.$un;
		$resu=mysqli_query($conn,$qu);
		$ro=$resu->fetch_assoc();
		$wallet = $ro['wallet'];
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
					   		<li><a href="#" class="mdi mdi-settings">Settings</a></li>
					   		<li><a href="logout.php" target="_self" class="mdi mdi-logout">Logout</a></li>
					   		<?php
			            		echo 
			            		'<li>
			            			<a class="mdi mdi-wallet" id="walletBtn" href="#">
			            				<font size = "2">Rs. '.$wallet.'</font>
			            			</a>
			            		</li>'
		            		?>
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
		                			echo "<li role = 'plusminus'><a href='search.php?query=".$row['category_name']."'>".$row['category_name']."</a> </li>";
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
					<option value="no_filters">No filters </option>
					<option value="price_lth">Price Low to High </option>
					<option value="price_htl">Price High to Low</option>
					<option value="date_added_r">Recent Products</option>
					<option value="date_added_o">Old Added Products</option>
				</select>
		    </form>
		    
		    <!-- Cart -->
		    <div class="cart"><a href="mycart.php"><img class="cartClass" src="store_images/cart.png"></a></div>
    	</div>
    	

    	<br>
    	<br>
    	<br>
    	<br>


	

		<?php
			$product_id = $_GET['product_id'];
			$query = 'SELECT icon_name,product_id,product_name,products.category_id,category_name,price,date_added,description FROM products,category WHERE (products.category_id=category.category_id) AND (product_id = '.$product_id.')';
			$result= mysqli_query($conn,$query);
			if($result->num_rows == 0)
			{
				echo 'Invalid Product ID <br>';
			}
			else
			{
				$row = $result->fetch_assoc();
				

				$productname=$row['product_name'];
				$productprice=$row['price'];
				$productdescription=$row['description'];
				$image=$row['icon_name'];
		?>

		<div>	<style scoped>
		        @import "style.css";</style>
		    
		<div class="container" style="border: 1px solid #262626;">
		
    		<br>
			<div class="border-0">
				<div class="row">
					<aside class="col-sm-4">
						<article class="gallery-wrap"> 
							<div class="img-big-wrap">
								<div>
									<a href="#">
										<?php
											echo '<img src="images/'.$image.'">';
										?>
									</a>
								</div>
							</div>
							
						</article>
					</aside>

					<aside class="col-sm-5">
						<article class="card-body m-0 pt-0 pl-5">
							<h3 class="title text-uppercase"><?php echo $productname ?></h3>
							<div class="rating">
								<div class="stars">
									<span class="fa fa-star checked"></span>
									<span class="fa fa-star checked"></span>
									<span class="fa fa-star checked"></span>
									<span class="fa fa-star"></span>
									<span class="fa fa-star"></span>
									<span class="review-no ml-2">(41 avis)</span>
								</div>
							</div>

							<div class="mb-3 mt-3"> 
								<span class="h7 text-success">In stock.</span>
							</div>						
							<div class="mb-3 mt-3"> 
								<span class="price-title">Price : </span>
								<span class="price color-price-waanbii"><?php echo $productprice ?> Rs.</span>
							</div>
							<dl class="item-property">
								<dt>Description</dt>
								<dd>
									<p><?php echo $productdescription ?></p>
								</dd>
							</dl>
						</article>
					</aside>

					<aside class="col-sm-3">
						<div class="row">
							<dl class="param param-inline">
								<dt>Quantity: </dt>
							</dl>
						</div>

						<div class="row ">
							<?php
								echo
								'<form method="POST" action="addtocart.php?product_id='.$product_id.'&">
									<input type="number" min="1" max="10" style="width : 100%" name="quantity" required placeholder="Enter quantity"/>
									<br>
									<br>
									<button type="submit" class="btn btn-lg color-box-waanbii"><i class="fa fa-shopping-cart"></i>Add to cart </button>
								</form>'
							?>
						</div>

						<div class="row mb-4 mt-4">
							<button class="btn btn-lg btn-success" type="button"><i class="fa fa-heart fa-beat"></i></button>
						</div>
						<hr class="m-0 p-0">
						<div class="row mb-4 mt-4">
							Il reste 3 exemplaires du produits.
						</div>
					</aside>
				</div>
			</div>
		</div>
		</div>
		<?php 
			}
		?>

		<br><br><br>
    	<br>
    	<br>
    	<br>
    	<br>


		<br><br><br>
    	<br>
    	<br>
    	<br>
    	<br>




		<script type="text/javascript"> 
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