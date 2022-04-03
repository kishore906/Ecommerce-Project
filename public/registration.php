<?php  require_once("../resources/config.php"); ?>

<?php include(TEMPLATE_FRONT . DS . "header.php"); ?>

<?php register_user(); ?>

   <center><h3 class="page-header">
      Registration
  </h3></center>

  <div class="container">

    <form action="" method="post" enctype="multipart/form-data">


  <div class="col-md-4 col-sm-offset-5">

 
      <div class="form-group">
          <label for="first name">First Name</label>
      <input type="text" name="firstname" class="form-control"   >
         
     </div> 

      <div class="form-group">
          <label for="last name">Last Name</label>
      <input type="text" name="lastname" class="form-control"   >
         
     </div> 

     <div class="form-group">
          <label for="email">Email</label>
      <input type="email" name="email" class="form-control"   >
         
     </div>

    
     <div class="form-group">
      <label for="username">Username</label>
      <input type="text" name="username" class="form-control" >
         
     </div>

      <div class="form-group">
          <label for="password">Password</label>
      <input type="password" name="password" class="form-control"  >
         
     </div>

      <div class="form-group">

      <input type="submit" name="register_user" class="btn btn-primary" value="SignUp" >
         
     </div>
      

  </div>
  
</form>

</div>


