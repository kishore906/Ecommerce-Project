<?php 

$upload_directory = "uploads";

function last_id(){

global $connection;

return mysqli_insert_id($connection);

}

function set_message($msg){

	if(!empty($msg)){

		$_SESSION['message'] = $msg;

	}else {

		$msg = "";
	}
}

function display_message(){

	if(isset($_SESSION['message'])){

		echo $_SESSION['message'];
		unset($_SESSION['message']);
	}
}

function redirect($location){

	header("Location: $location ");
}

function query($sql){

	global $connection;

	return mysqli_query($connection, $sql);
}

function confirm($result){

	global $connection;

	if(!$result){

		die("QUERY FAILED" . mysqli_error($connection));
	}
}

function escape_string($string){

global $connection;

return mysqli_real_escape_string($connection, $string);

}

function fetch_array($result){

	return mysqli_fetch_array($result);
}


//*************************************FRONT END FUNCTIONS******************************************************


// get products function

function get_products(){

	 $query = query(" SELECT * FROM products");
    confirm($query);

    $rows = mysqli_num_rows($query); // Get total of mumber of rows from the database


    if(isset($_GET['page'])){ //get page from URL if its there

        $page = preg_replace('#[^0-9]#', '', $_GET['page']);//filter everything but numbers



    } else{// If the page url variable is not present force it to be number 1

        $page = 1;

    }


    $perPage = 6; // Items per page here

    $lastPage = ceil($rows / $perPage); // Get the value of the last page


// Be sure URL variable $page(page number) is no lower than page 1 and no higher than $lastpage

    if($page < 1){ // If it is less than 1

        $page = 1; // force if to be 1

    }elseif($page > $lastPage){ // if it is greater than $lastpage

        $page = $lastPage; // force it to be $lastpage's value

    }



    $middleNumbers = ''; // Initialize this variable

// This creates the numbers to click in between the next and back buttons


    $sub1 = $page - 1;
    $sub2 = $page - 2;
    $add1 = $page + 1;
    $add2 = $page + 2;



    if($page == 1){

        $middleNumbers .= '<li class="page-item active"><a>' .$page. '</a></li>';

        $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$add1.'">' .$add1. '</a></li>';

    } elseif ($page == $lastPage) {

        $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$sub1.'">' .$sub1. '</a></li>';
        $middleNumbers .= '<li class="page-item active"><a>' .$page. '</a></li>';

    }elseif ($page > 2 && $page < ($lastPage -1)) {

        $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$sub2.'">' .$sub2. '</a></li>';

        $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$sub1.'">' .$sub1. '</a></li>';

        $middleNumbers .= '<li class="page-item active"><a>' .$page. '</a></li>';

        $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$add1.'">' .$add1. '</a></li>';

        $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$add2.'">' .$add2. '</a></li>';




    } elseif($page > 1 && $page < $lastPage){

        $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$sub1.'">' .$sub1. '</a></li>';

        $middleNumbers .= '<li class="page-item active"><a>' .$page. '</a></li>';

        $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$add1.'">' .$add1. '</a></li>';





    }


// This line sets the "LIMIT" range... the 2 values we place to choose a range of rows from database in our query


    $limit = 'LIMIT ' . ($page-1) * $perPage . ',' . $perPage;




// $query2 is what we will use to to display products with out $limit variable

    $query2 = query(" SELECT * FROM products $limit");
    confirm($query2);


    $outputPagination = ""; // Initialize the pagination output variable


// if($lastPage != 1){

//    echo "Page $page of $lastPage";


// }


    // If we are not on page one we place the back link

    if($page != 1){


        $prev  = $page - 1;

        $outputPagination .='<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$prev.'">Back</a></li>';
    }

    // Lets append all our links to this variable that we can use this output pagination

    $outputPagination .= $middleNumbers;


// If we are not on the very last page we the place the next link

    if($page != $lastPage){


        $next = $page + 1;

        $outputPagination .='<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$next.'">Next</a></li>';

    }


// Doen with pagination



// Remember we use query 2 below :)

    while($row = fetch_array($query2)) {

        $product_image = display_image($row['product_image']);

        $product = <<<DELIMETER

<div class="col-sm-4 col-lg-4 col-md-4">
    <div class="thumbnail">
        <a href="item.php?id={$row['product_id']}"><img style="height:150px" src="../resources/{$product_image}" alt=""></a>
        <div class="caption">
            <h4 class="pull-right">&#36;{$row['product_price']}</h4>
            <h4><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
            </h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
             <a class="btn btn-primary" target="_blank" href="../resources/cart.php?add={$row['product_id']}">Add to cart</a>
        </div>


       
    </div>
</div>

DELIMETER;

        echo $product;


    }


    echo "<div class='text-center'><ul class='pagination'>{$outputPagination}</ul></div>";
}

function get_categories(){

 $query = query("SELECT * FROM categories");
     confirm($query);
		while($row = fetch_array($query)){

$categories_links = <<<DELIMETER
<a href='category.php?id={$row['cat_id']}' class='list-group-item'>{$row['cat_title']}</a>
DELIMETER;
echo $categories_links;      		

     }
}

function get_products_in_cat_page(){

	$query = query("SELECT * FROM products WHERE product_category_id = " . escape_string($_GET['id']) . " AND product_quantity >= 1");
	confirm($query);

	while($row = fetch_array($query)) {

	$product_image = display_image($row['product_image']);	

	$product = <<<DELIMETER
	<div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="../resources/{$product_image}" alt="">
                    <div class="caption">
                        <h3>{$row['product_title']}</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                        <p>
                            <a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>
DELIMETER;
	echo $product;

	}
}


function get_products_in_shop_page(){

	$query = query("SELECT * FROM products WHERE product_quantity >= 1");
	confirm($query);

	while($row = fetch_array($query)) {

	$product_image = display_image($row['product_image']);	

	$product = <<<DELIMETER
	<div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="../resources/{$product_image}" alt="" style="height:150px;">
                    <div class="caption">
                        <h3>{$row['product_title']}</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                        <p>
                            <a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>
DELIMETER;
echo $product;

	}
}

function login_admin(){

if(isset($_POST['submit'])){

	$username = escape_string($_POST['username']);
	$password = escape_string($_POST['password']);

	$query = query("SELECT * FROM users WHERE username = '{$username}' AND password = '{$password}' ");
	confirm($query);

	if(mysqli_num_rows($query) == 0){

		set_message("Your Username or Password are wrong!!");
		redirect("login.php");

	}else {

		$_SESSION['username'] = $username;

		set_message("Welcome to admin {$username}");
		redirect("admin");
	}
}

}

function login_user(){

    if(isset($_POST['submit'])){
    
        $username = escape_string($_POST['username']);
        $password = escape_string($_POST['password']);

    
        $query = query("SELECT * FROM users WHERE username = '{$username}'");
        confirm($query);
        $row = fetch_array($query);
       // echo $row['password']; 
    
        if(mysqli_num_rows($query) == 0){
    
            set_message("Your Username or Password are wrong!!");
            redirect("login.php");
    
        }else if(password_verify($password, $row['password'])){
            $_SESSION['username'] = $username;
            //echo $username;
            $_SESSION['status'] = true;
            redirect("shopping.php");
        }else{
            $error = "Inavlid username and password..";
        }
    }
    
    }

function send_message(){

	if(isset($_POST['submit'])){

		$to = "kkishore906@gmail.com";
		$from_name 	= $_POST['name'];
		$subject 	= $_POST['subject'];
		$email 		= $_POST['email'];
		$message 	= $_POST['message'];  

		$headers = "From: {$from_name} {$email}";

		$result = mail($to, $subject, $message, $headers);

		if(!$result){

			echo "ERROR";
			//set_message("Your message was unable to send");
			redirect("contact.php");
		}else {

			echo "SENT";
			//set_message("Your message was sent successfully!!");
			redirect("contact.php");
		}
	}
}

//#####################BACK END FUNCTIONS#########################

function display_orders(){

$query = query("SELECT * FROM orders");
confirm($query);

while($row = fetch_array($query)){

$orders = <<<DELIMETER

		<tr>
           <td>{$row['order_id']}</td>
           <td>{$row['order_amount']}</td>
           <td>{$row['order_transaction']}</td>
           <td>{$row['order_currency']}</td>
           <td>{$row['order_status']}</td>
           <td><a class="btn btn-danger" href="index.php?delete_order_id={$row['order_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
      </tr>


DELIMETER;

echo $orders;



}


}

//**********************ADMIN ORDERS********************************************

function display_image($picture){

global $upload_directory;

return $upload_directory . DS . $picture;


}

function get_products_in_admin(){

$query = query("SELECT * FROM products");
	confirm($query);

	while($row = fetch_array($query)) {

	$category = show_product_category_title($row['product_category_id']);

	$product_image = display_image($row['product_image']);	

	$product = <<<DELIMETER
	
		<tr>
            <td>{$row['product_id']}</td>
            <td>{$row['product_title']}<br>
             <img width='100' src="../../resources/{$product_image}" alt=""></a>
            </td>
            <td>{$category}</td>
            <td>{$row['product_price']}</td>
            <td>{$row['product_quantity']}</td>
            <td><a class="btn btn-primary" href="index.php?edit_product&id={$row['product_id']}"><span class="glyphicon glyphicon-pencil"></span></a></td>
            <td><a class="btn btn-danger" href="../../resources/templates/back/delete_product.php?id={$row['product_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>
DELIMETER;
	echo $product;

	}

}


function show_product_category_title($product_category_id){


$category_query = query("SELECT * FROM categories WHERE cat_id = '{$product_category_id}' ");
confirm($category_query);

while($category_row = fetch_array($category_query)){

return $category_row['cat_title'];

}


}

//*****************************ADDING PRODUCTS IN ADMIN**************************

function add_product(){

	if(isset($_POST['publish'])){

		$product_title 			= escape_string($_POST['product_title']);
		$product_category_id 	= escape_string($_POST['product_category_id']);
		$product_price 			= escape_string($_POST['product_price']);
		$product_description 	= escape_string($_POST['product_description']);
		$short_desc 			= escape_string($_POST['short_desc']);
		$product_quantity 		= escape_string($_POST['product_quantity']);
		$product_image 			= escape_string($_FILES['file']['name']);
		$image_temp_location 	= escape_string($_FILES['file']['tmp_name']);  

		move_uploaded_file($image_temp_location, UPLOAD_DIRECTORY . DS . $product_image);

		$query = query("INSERT INTO products(product_title, product_category_id, product_price, product_description, short_desc, product_quantity, product_image) VALUES('{$product_title}', '{$product_category_id}', '{$product_price}', '{$product_description}', '{$short_desc}', '{$product_quantity}', '{$product_image}')");
		$last_id = last_id();
		confirm($query);

		set_message("New Product with id {$last_id} Was Added");
		redirect("index.php?products");

	}
}

function show_categories_in_add_product_page(){

 $query = query("SELECT * FROM categories");
     confirm($query);
		while($row = fetch_array($query)){

$categories_options = <<<DELIMETER

<option value="{$row['cat_id']}">{$row['cat_title']}</option>

DELIMETER;
echo $categories_options;      		

     }
}

//&&&&&&&&&&&&&&&&&&&UPDATING THE PRODUCT&&&&&&&&&&&&&&&&&&&&

function update_product(){

	if(isset($_POST['update'])){

		$product_title 			= escape_string($_POST['product_title']);
		$product_category_id 	= escape_string($_POST['product_category_id']);
		$product_price 			= escape_string($_POST['product_price']);
		$product_description 	= escape_string($_POST['product_description']);
		$short_desc 			= escape_string($_POST['short_desc']);
		$product_quantity 		= escape_string($_POST['product_quantity']);
		$product_image 			= escape_string($_FILES['file']['name']);
		$image_temp_location 	= escape_string($_FILES['file']['tmp_name']); 


		if(empty($product_image)){

		$get_pic = query("SELECT product_image FROM products WHERE product_id = " . escape_string($_GET['id']) . " ");
		confirm($get_pic);

		while($pic = fetch_array($get_pic)){

		$product_image = $pic['product_image'];

		}	

		} 

		move_uploaded_file($image_temp_location, UPLOAD_DIRECTORY . DS . $product_image);

		$query = "UPDATE products SET ";
		$query .= "product_title 		= '{$product_title}', ";
		$query .= "product_category_id 	= '{$product_category_id}', ";
		$query .= "product_price 		= '{$product_price}', ";
		$query .= "product_description 	= '{$product_description}', ";
		$query .= "short_desc 			= '{$short_desc}', ";
		$query .= "product_quantity 	= '{$product_quantity}', ";
		$query .= "product_image 		= '{$product_image}' ";
		$query .= "WHERE product_id=" . escape_string($_GET['id']);

		$send_update_query = query($query);

		confirm($send_update_query);

		set_message("Product has been Updated !!");
		redirect("index.php?products");

	}
}

//*********************************CATEGORIES IN ADMIN****************************************

function show_categories_in_admin(){

$category_query = query("SELECT * FROM categories");
confirm($category_query);

while($row = fetch_array($category_query)){

$cat_id = $row['cat_id'];
$cat_title = $row['cat_title'];

$category = <<<DELIMETER

		<tr>
            <td>{$cat_id}</td>
            <td>{$cat_title}</td>
            <td><a class="btn btn-danger" href="../../resources/templates/back/delete_category.php?id={$row['cat_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>

DELIMETER;

echo $category;


}

}

function add_category(){

if(isset($_POST['add_category'])){

$cat_title = escape_string($_POST['cat_title']);

if(empty($cat_title) || $cat_title == " "){

echo "<p class='bg-danger'>This Field Cannot be Empty</p>";

}else {


$insert_cat = query("INSERT INTO categories(cat_title) VALUES('{$cat_title}') ");
confirm($insert_cat);

set_message("Category Created");

//redirect("index.php?categories");

}

}

}

//*********************************ADMIN USERS*************************************


function display_users(){

$category_query = query("SELECT * FROM users");
confirm($category_query);

while($row = fetch_array($category_query)){

$user_id = $row['user_id'];
$firstname = $row['firstname'];
$lastname = $row['lastname'];
$username = $row['username'];
$email = $row['email'];
$password = $row['password'];

$user = <<<DELIMETER

		<tr>
            <td>{$user_id}</td>
            <td>{$firstname}</td>
            <td>{$lastname}</td>
            <td>{$username}</td>
            <td>{$email}</td>
            <td><a class="btn btn-primary" href="index.php?edit_user&id={$row['user_id']}"><span class="glyphicon glyphicon-pencil"></span></a></td>
            <td><a class="btn btn-danger" href="../../resources/templates/back/delete_user.php?id={$row['user_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>

DELIMETER;

echo $user;


}

} 

function register_user(){

    if(isset($_POST['register_user'])){
        $firstname 	= escape_string($_POST['firstname']);
        $lastname	= escape_string($_POST['lastname']);
        $email 		= escape_string($_POST['email']);
        $username 	= escape_string($_POST['username']);
        $password 	= escape_string($_POST['password']);
        $password_encrypt = password_hash($password, PASSWORD_DEFAULT);

        $query = query("INSERT INTO users(firstname,lastname,username,email,password) VALUES('{$firstname}','{$lastname}','{$username}','{$email}','{$password_encrypt}')");
        confirm($query);
        set_message("Registration Successful..");
        redirect("shop.php");
    }
}

function add_user(){

if(isset($_POST['add_user'])){

$username 	= escape_string($_POST['username']);
$email 		= escape_string($_POST['email']);
$password 	= escape_string($_POST['password']);
//$user_photo = escape_string($_POST['file']['name']);
//$photo_temp = escape_string($_POST['file']['tmp_name']);

//move_uploaded_file($photo_temp, UPLOAD_DIRECTORY . DS . $user_photo);

$query = query("INSERT INTO users(username,email,password) VALUES('{$username}','{$email}','{$password}')");

confirm($query);
set_message("User Created");
redirect("index.php?users");

}

}

function update_user(){

    if(isset($_POST['update_user'])){
        $firstname = escape_string($_POST['firstname']);
        $lastname = escape_string($_POST['lastname']);
        $username = escape_string($_POST['username']);
        $email = escape_string($_POST['email']);
        $password = escape_string($_POST['password']);
        $password_encrypt = password_hash($password, PASSWORD_DEFAULT);

        $query = query("UPDATE users SET firstname='{$firstname}',lastname='{$lastname}',username='{$username}',email='{$email}',password='{$password_encrypt}' WHERE user_id = " . escape_string($_GET['id']) . " ");

        confirm($query);
        set_message("User Updated");
        redirect("index.php?users");
    }
}


//******************************REPORTS IN ADMIN***********************************

function get_reports(){

$query = query("SELECT * FROM reports");
	confirm($query);

	while($row = fetch_array($query)) {	

	$report = <<<DELIMETER
	
		<tr>
            <td>{$row['report_id']}</td>
            <td>{$row['product_id']}</td>
            <td>{$row['order_id']}</td>
            <td>{$row['product_price']}</td>
            <td>{$row['product_title']}</td>
            <td>{$row['product_quantity']}</td>
            <td><a class="btn btn-danger" href="../../resources/templates/back/delete_report.php?id={$row['report_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>
DELIMETER;

	echo $report;

	}

}

?>