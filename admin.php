<!DOCTYPE html>
<html>
<head>
	<title>Admin</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/admin.css">
	<link rel="stylesheet" href="css/resources.css">
	<script src="js/management.js"></script>
	<script src="js/author.js"></script>
</head>
<body>

 	<?php
		include_once "db_connection.php";
		include_once "management.php";

	    session_start();

		// If user is not logged
		if(!isset($_SESSION["iduser"]))
			header('Location: login.php');

	    // If user clicked on unlogin button
		if(isset($_POST["unlogin"])){
			session_destroy();
			header('Location: login.php');
		}

		$listProductCategory = getAllProductCategory($connection);

		// User data from logged customer
		$userData = getUserData($connection, $_SESSION['iduser']);
		$username = $userData['username'];

		// Product data from all product
		$productsData = [[]];

        if(isset($_GET['categ']))
            $productsData = getProductCategory($connection, $_GET['categ']);
        else
            $productsData = getAllProduct($connection);

		$productID    = $productsData['id'];
		$productName  = $productsData['name'];
		$productPrice = $productsData['price'];
		$productImage = $productsData['urlimage'];

		// Actions of shopping cart
		refreshCart($connection);

		if(isset($_POST["delete"])){
			deleteProductFromCart($connection, $_POST["cartProductID"]);
			showCart();
			refreshCart($connection);
		}
		if(isset($_POST["clear"])){
			clearCart($connection);
			showCart();
			refreshCart($connection);
		}
		if(isset($_POST["buy"]) || isset($_POST["buyDirectly"])){
			if(isset($_POST["buy"]))
				makePurchase($connection, $_SESSION['iduser'], $cartProductsNumber, $cartTotalPrice);
			// else if(isset($_POST["buyDirectly"])){	// Products within cart + current product
			// 	$money = $cartTotalPrice + ($productPrice * $_POST["amountToAdd"]);
			// 	makePurchase($connection, $_SESSION['iduser'], $cartProductsNumber + 1, $money);
			// }
			clearCart($connection);
			refreshCart($connection);
            showToast("Purchase made with success");
		}
		toggleDesignCart();
	?>

	<div id="wrapper">
		<!-- Level 1 -->
		<div id="basic">
	        <div id="user">
			    <div class="dropdown">
			        <button class="flatButton dropbtnCategory">Category</button>
			        <div class="dropdown-content">
 						<?php
							for($i=0;$i<count($listProductCategory);$i++){
								echo "<a href='menu.php?categ=".strtolower($listProductCategory[$i])."' ?>";
								echo $listProductCategory[$i]."</a>";
							}
						?>
			        </div>
			    </div>
	        </div>
			<div id="title"><h1>Administration</h1></div>
			<div id="cart">
				<label><?php echo $cartProductsNumber; ?></label><img class="myBtn" src="resources/img/cart.png">
			</div>
		</div>
        <hr>
		<!-- Level 2 -->
        <div id="infobar">
	        <div id="left">
		        <div id="path">
		        	<label>Manage your online store easily</label>
		        </div>
	        </div>
	        <div id="right">

	        	<div id="btnUser" class="dropdown">
			        <button class="dropbtnUser"><?php echo $username; ?></button>
			        <div class="dropdown-content-user">
			        	<a class="myBtn">Shopping Cart</a>
			        	<a href="order.php">Orders</a>
						<form method="post"><input type="submit" name="unlogin" value="Logout"></form>
			        </div>
			    </div>

	        </div>
        </div>
        <!-- Level 3 -->
        <br>
		<div id="content">
			<div id="img">
				<a href="admin_customer.php"><div><img src="resources/img/test/1.png"></div></a>
				<a href="admin_product.php"><div><img src="resources/img/test/13.png"></div></a>
				<a href="admin_order.php"><div><img src="resources/img/test/131.png"></div></a>
			</div>
			<div id="text">
				<div><p>Customer</p></div>
				<div><p>Product</p></div>
				<div><p>Order</p></div>
			</div>
		</div>
	</div>
</body>
</html>

