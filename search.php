<!DecoType HTML>

<html lang="en">
	<head>
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<title>E-Store </title>
        <link rel="stylesheet" type="text/css" href="estyle.css">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

        <link href="https://cdn.materialdesignicons.com/3.6.95/css/materialdesignicons.min.css" media="all" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

      	<link style="width: 100%;height: 100%" rel="tab icon" href="store_images/logoicon.ico"/>
    
    </head>

	<?php 
		ini_set('error_reporting', 'E_COMPILE_ERROR|E_RECOVERABLE_ERROR|E_ERROR|E_CORE_ERROR');

		session_start();

		$check_login = $_SESSION["logged_in"];
		$wallet= $_SESSION['wallet'];
		$spi = $_POST['spi'];
		$spn = $_POST['sp'];
		$sci = $_POST['sci'];
		$scn = $_POST['scn'];
		$query = 'SELECT products.icon_name,products.product_id,products.product_name,products.category_id,category_name,products.description,products.price FROM products,category WHERE (products.category_id = category.category_id) AND (';
		$filter=$_COOKIE['filter'];

			/*
			if ($check_login == true) 
			{
				echo "Welcome " . $_SESSION["fullname"] . "! <br>";
			}
			*/
		include 'connection.php';
		$conn = OpenCon();

		if (mysqli_connect_errno())
		{
			echo "Unable to connect to server " . mysqli_connect_error();
			exit;
		}
		
		
		if(isset($_GET['query']) == false)
		{
			if($spi !== NULL){
				$query=$query.'products.product_id = '.$spi;
			}
			if($spn !== NULL){
				$inp = str_ireplace(' ','%',$spn);
				$query=$query."products.product_name LIKE '%".$inp."%'";
			}
			if($sci !== NULL){
				$query=$query.'products.category_id = '.$sci;
			}
			if($scn !== NULL){
				$inp = str_ireplace(' ','%',$scn);
				$query=$query."category_name LIKE '%".$inp."%'";
			}

			$query=$query.')';

			//echo $query."<br>";

			if($filter != 'nf'){
				if($filter == 'plth'){
					$query = $query.' ORDER BY price ASC';
				}
				else if($filter == 'phtl'){
					$query = $query.' ORDER BY price DESC';
				}
				else if($filter == 'rp'){
					$query = $query.' ORDER BY date_added DESC';
				}
				else if($filter == 'oap'){
					$query = $query.' ORDER BY date_added ASC';
				}
			}
		}
		else
		{
			$inp = $_GET['query'];
			$query=$query."category_name LIKE '".$inp."'";
			$query=$query.')';

		}
		
	
			
		?>
	
	
	
	<body>
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
					   	<span> <?php echo'<font size="1.5rem" style="color:white;">'.$_SESSION["fullname"].'</font>' ?></span>
					   	
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
		                		$query1 = 'SELECT category_name FROM category';
		                		$result = mysqli_query($conn,$query1);
		                		
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




		<?php
			$result = mysqli_query($conn, $query);

			//echo $result->num_rows;	

			if($result->num_rows == 0){
				echo 'No product found.<br>';
			}
			else
			{
				while($row = $result->fetch_assoc())
				{
						$productid=$row['product_id'];
						$image=$row['icon_name'];
						$data =  "Product ID = ".$productid."        "."Product Name = ".$row['product_name']."        "."Category ID = ".$row['category_id']."        "."Category Name = ".$row['category_name']."        "."Price = ".$row['price']."<br>Description = ".$row['description'];
						echo '<img src="images/'.$image.'" align="left" alt="Image" height="84" width="84" >';
						echo '<br>';
						$print =  '<a href="product.php?product_id='.$productid.'">'.$data.' </a>';
						echo $print;
						echo '<br><br><br><br><br><br>';
						
				}
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