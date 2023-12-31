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


    	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">


    
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

		if($_SESSION['admin'] == true)
		{
			echo '<script>window.history.back()</script>';
			exit;
		}
				
		$fn=$_SESSION['fullname'];
		$wallet=$_SESSION['wallet'];
		$logged_in=$_SESSION["logged_in"];	
		$username = $_SESSION["username"];
		if($logged_in == false)
		{
			
			echo '<script>alert("You are not logged in.")</script>';
			echo '<meta http-equiv="refresh" content="0; URL=\'login.php\'" /> ';
			exit;
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
    	



		<div id="disp_cart">
			<h2 style="margin-left: 100px;margin-bottom: 25px;margin-right: 25px"><i style="margin-left: 25px" class="fab fa-opencart">Products</i></h2>
			<div class="shopping-cart">

				  	<div class="column-labels">
				    	<label class="product-image">Image</label>
				    	<label class="product-details">Product</label>
				    	<label class="product-price">Price</label>
				    	<label class="product-quantity">Quantity</label>
				    	<label class="product-removal">Remove</label>
				   		<label class="product-line-price">Total</label>
				  	</div>
			
			<?php
				$query='SELECT products.product_name,cart.quantity,products.price,products.description,cart.total,products.icon_name FROM cart,products WHERE (cart.product_id=products.product_id) AND (cart.user_id='.$username.')';
				$result=mysqli_query($conn,$query);
				if($result->num_rows == 0)
				{
					echo '<script> hidediv("buycartprod"); </script>';
					echo '</br></br><h3>You have nothing in cart.</h3>';
					exit;
				}
				
				while($row=$result->fetch_assoc())
				{
					$productname=$row['product_name'];
					$productquantity=$row['quantity'];
					$productprice=$row['price'];
					$productdescription=$row['description'];
					$carttotal=$row['total'];
					$image=$row['icon_name'];

					$subtotal += $productprice * $productquantity;
			?>

				  	<div class="product">
				    	<div class="product-image">
				      		<?php
				               	echo '<img class="pic-1" src="images/'.$image.'">';
				            ?>
				    	</div>
				    	<div class="product-details">
				      		<div class="product-title"><?php echo $productname ?></div>
				      		<p class="product-description"><?php echo $productdescription ?>.</p>
				    	</div>
				    	<div class="product-price"><?php echo $productprice ?></div>
				    	<div class="product-quantity">
				    		<?php echo '<input type="number" value = "'.$productquantity.'" min="1">' ?> 
				    	</div>
				    	<div class="product-removal">
				    		<button class="remove-product">
				        		Remove
				      		</button>
				    	</div>
				    	<div class="product-line-price"><?php echo $productprice * $productquantity ?></div>
				  	</div>
				<?php		
					}
				?>

				  	<div class="totals">
				    	<div class="totals-item">
				      		<label>Subtotal</label>
				      		<div class="totals-value" id="cart-subtotal"><?php echo $subtotal ?></div>
				    	</div>
				    	<div class="totals-item">
				      		<label>Tax (5%)</label>
				      		<div class="totals-value" id="cart-tax"><?php echo $subtotal / 100 * 5 ?></div>
				    	</div>
				    	<div class="totals-item">
				      		<label>Shipping</label>
				      		<div class="totals-value" id="cart-shipping">15.00</div>
				    	</div>
				    	<div class="totals-item totals-item-total">
				      		<label>Grand Total</label>
				      		<div class="totals-value" id="cart-total"><?php echo $subtotal +  $subtotal / 100 * 5 + 15?></div>
				    	</div>
				  	</div>
				      
				   	<button class="checkout" onclick="window.location.href='buycartproducts.php'">Checkout</button>

				</div>

			
			
		</div>



		



		<script type="text/javascript">
				/* Set rates + misc */
				var taxRate = 0.05;
				var shippingRate = 15.00; 
				var fadeTime = 300;


				/* Assign actions */
				$('.product-quantity input').change( function() {
				  updateQuantity(this);
				});

				$('.product-removal button').click( function() {
				  removeItem(this);
				});


				/* Recalculate cart */
				function recalculateCart()
				{
				  var subtotal = 0;
				  
				  /* Sum up row totals */
				  $('.product').each(function () {
				    subtotal += parseFloat($(this).children('.product-line-price').text());
				  });
				  
				  /* Calculate totals */
				  var tax = subtotal * taxRate;
				  var shipping = (subtotal > 0 ? shippingRate : 0);
				  var total = subtotal + tax + shipping;
				  
				  /* Update totals display */
				  $('.totals-value').fadeOut(fadeTime, function() {
				    $('#cart-subtotal').html(subtotal.toFixed(2));
				    $('#cart-tax').html(tax.toFixed(2));
				    $('#cart-shipping').html(shipping.toFixed(2));
				    $('#cart-total').html(total.toFixed(2));
				    if(total == 0){
				      $('.checkout').fadeOut(fadeTime);
				    }else{
				      $('.checkout').fadeIn(fadeTime);
				    }
				    $('.totals-value').fadeIn(fadeTime);
				  });
				}


				/* Update quantity */
				function updateQuantity(quantityInput)
				{
				  /* Calculate line price */
				  var productRow = $(quantityInput).parent().parent();
				  var price = parseFloat(productRow.children('.product-price').text());
				  var quantity = $(quantityInput).val();
				  var linePrice = price * quantity;
				  
				  /* Update line price display and recalc cart totals */
				  productRow.children('.product-line-price').each(function () {
				    $(this).fadeOut(fadeTime, function() {
				      $(this).text(linePrice.toFixed(2));
				      recalculateCart();
				      $(this).fadeIn(fadeTime);
				    });
				  });  
				}


				/* Remove item from cart */
				function removeItem(removeButton)
				{
				  /* Remove row from DOM and recalc cart total */
				  var productRow = $(removeButton).parent().parent();
				  productRow.slideUp(fadeTime, function() {
				    productRow.remove();
				    recalculateCart();
				  });
				}

				
			</script>



	</body>

</html>