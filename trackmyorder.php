<!DOCTYPE html>
<html lang="en">
	<head>
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<title>E-Store </title>
        <link rel="stylesheet" type="text/css" href="estyle.css">

        <link href="https://cdn.materialdesignicons.com/3.6.95/css/materialdesignicons.min.css" media="all" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<!------ Include the above in your HEAD tag ---------->

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />



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

	?>
	
	
	<style>
	.trackorder
	{
		padding: 100px;
		text-align: center;
		border-radius:16px;
	}
	
	
	table 
	{
		text-align: center;
		border-collapse: collapse;
		width: 96%;
		margin: 2%;
	}

	th 
	{
		text-align: center;
		font-weight: 400;
		font-size: 13px;
		text-transform: uppercase;
		border-bottom: 1px solid rgba(0, 0, 0, 0.1);
		padding: 0 10px;
		padding-bottom: 14px;
	}

	tr:not(:first-child):hover 
	{
	  background: rgba(0, 0, 0, 0.1);
	}

	td 
	{
	  text-align: center;
	  line-height: 40px;
	  font-weight: 300;
	  padding: 0 10px;
	}

	.priceclass
	{
		text-align: center;
		margin-right: 90px;
		
	}
	</style>
	
	
	 <body style="color: #f1f2f7">
    	<!-- Top navigation Bar ( LOGO + Other Buttons) -->
    	<header>        
            <div class="logo"><a href="ehome.php"><img class="logoClass" src="store_images/logo.png"></a></div>
            <nav role = "header">
            	<ul>
            		<li><a href="ehome.php" class="active">HOME</a></li>
            		<li><a href="trackmyorder.php"> TRACK MY ORDER</a></li>
            		

            		
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
    
	
	
	<!-- Track MY ORDER -->
	<form method="POST" class="trackorder" style="align:centered; color:black" action="trackmyorder.php">
		<input type="number" name="orderid" placeholder="Enter Order ID" min="1" style="height: 35px; width: 600px; border-radius:16px; padding: 10px;"/>
		<button type="submit" style="border-radius:16px; height: 35px; width: 150px">Track my Order</button>
	</form>
	
	
	<?php
		if(isset($_POST['orderid']) == true)
		{
			$oquery = 'SELECT userss.user_id,first_name,last_name,price,quantity,total,address,status,buy.product_id,product_name from orders, buy, userss,products where (orders.user_id=userss.user_id) AND (orders.order_id=buy.order_id) AND (buy.product_id=products.product_id) AND orders.order_id='.$_POST['orderid'];
			$result = mysqli_query($conn,$oquery);
			if($result->num_rows == 0)
			{
				echo '<div style="text-align: center;padding: -80px; color: black">No order found with this ID</div>';
			}
			else
			{
				$x=1;
				$sum=0;
				echo '<div style="color:black;  margin-right: auto; margin-left:auto; text-align: center">';
				while($row = $result->fetch_assoc())
				{
					if($x == 1)
					{
						echo 'Name: '.$row['first_name'].''.$row['last_name'].'</br>Address: '.$row['address'].'</br>Status: '.$row['status'].'</br>Order ID: '.$_POST['orderid'].'</br>';
						echo '<table style="align:center; margin-right: auto; margin-left: auto;"><th>Product ID</th><th>Product Name</th><th>Price per Unit</th><th>Quantity</th><th>Total</th>';
						$x = $x + 1;
					}
					$sum = $sum + $row['total'];
					echo '<tr><td>'.$row['product_id'].'</td><td>'.$row['product_name'].'</td><td>'.$row['price'].'</td><td>'.$row['quantity'].'</td><td class="priceclass">'.$row['total'].'</td></tr>';
				}
				echo '</table></br></div><div style="margin-right: 90px; text-align:right; color:black;">Total Amount = Rs.'.$sum.'</div>';
			}
		}
	?>
	
	

		</body>
</html>