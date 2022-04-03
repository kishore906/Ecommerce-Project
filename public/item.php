<?php  require_once("../resources/config.php"); ?>

<?php include(TEMPLATE_FRONT . DS . "header.php"); ?>

<?php 

    if(!isset($_SESSION['username'])){

        redirect("userlogin.php");
    }


 ?>

    <!-- Page Content -->
<div class="container">

       <!-- Side Navigation -->

            <?php include(TEMPLATE_FRONT . DS. "side_nav.php"); ?>

            <?php 

    $query = query("SELECT * FROM products WHERE product_id = " . escape_string($_GET['id']) . " ");
    confirm($query);

    while($row = fetch_array($query)):

   ?>

<div class="col-md-9">

<!--Row For Image and Short Description-->

<div class="row">

    <div class="col-md-7">
       <img class="img-responsive" src="../resources/<?php echo display_image($row['product_image']); ?>" alt="">

    </div>

    <div class="col-md-5">

        <div class="thumbnail">
         

    <div class="caption-full">
        <h4><a href="#"><?php echo $row['product_title']; ?></a> </h4>
        <hr>
        <h4 class=""><?php echo "&#36;" . $row['product_price']; ?></h4>

        <p><?php echo $row['short_desc']?></p>

   
    <form action="">
        <div class="form-group">
            <a href="../resources/cart.php?add=<?php echo $row['product_id']; ?>" class="btn btn-primary">ADD TO CART</a>
        </div>
    </form>

    </div>
 
</div>

</div>


</div><!--Row For Image and Short Description-->


        <hr>


<!--Row for Tab Panel-->

<div class="row">

<div role="tabpanel">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Description</a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Reviews</a></li>

  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">

<p></p>
           
    <p><?php echo $row['product_description']?></p>

    </div>
    <div role="tabpanel" class="tab-pane" id="profile">

  <div class="col-md-6">

       <h3>All reviews</h3>

        <hr>

        <!------- ---------Comment Functionality ------------------------------->
        <!--------- INSERTING COMMENT ------------------------>
        <?php 
        
            if(isset($_POST['post_comment'])){

                $product_id = mysqli_real_escape_string($connection,$_GET['id']);
                $comment_name = mysqli_real_escape_string($connection,$_POST['comment_name']);
                $comment_email = mysqli_real_escape_string($connection,$_POST['comment_email']);
                $comment_content = mysqli_real_escape_string($connection,$_POST['comment_content']);

                $comment_query = "INSERT INTO comments(comment_product_id,comment_name,comment_email,comment_content) VALUES('$product_id','$comment_name','$comment_email','$comment_content')";
                $result_query = mysqli_query($connection,$comment_query);

                if(!$result_query){
                    echo 'Query unsuccessful'. mysqli_error($connection);
                }
            }
        
        
        ?>

        <!-----------------DISPLAYING COMMENT --------------------->

        <?php 
        
            $comment_display = "SELECT * FROM comments WHERE comment_product_id = " . escape_string($_GET['id']) . " ";
            $comment_result = mysqli_query($connection,$comment_display);
            
            if(!$comment_result){
                echo "Query Unsuccessful" . mysqli_error($connection);
            }

            $count_reviews = mysqli_num_rows($comment_result);
            while($row1 = mysqli_fetch_assoc($comment_result)){
        ?>


        <div class="row">
            <div class="col-md-12">
                <p><b><?php echo $row1['comment_name']; ?></b></p>
                <p><?php echo $row1['comment_content']; ?></p>
                <p><i><b>Commented on:</b> <?php echo $row1['comment_date']; ?></i></p>
            </div>
        </div>

        <hr>

        <?php } ?>

    </div>
    


    <div class="col-md-6">
        <h3>Write a review</h3>

     <form action="" method="POST" class="form-inline">
        <div class="form-group">
            <label for="">Name</label>
                <input type="text" class="form-control" name="comment_name" required>
            </div>
             <div class="form-group">
            <label for="">Email</label>
                <input type="test" class="form-control" name="comment_email" required>
            </div>

        <div>
            <h3>Your Review</h3>
        </div>

            <br>
            
             <div class="form-group">
             <textarea id="" cols="60" rows="10" class="form-control" name="comment_content" required></textarea>
            </div>

             <br>
              <br>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="SUBMIT" name="post_comment">
            </div>
        </form>

    </div>

 </div>

 </div>

</div>


</div><!--Row for Tab Panel-->




</div><!-- col-md-9 ends here -->

<?php endwhile; ?>

</div>
    <!-- /.container -->

    <?php include(TEMPLATE_FRONT . DS . "footer.php"); ?>
