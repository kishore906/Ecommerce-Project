
<?php 

if(isset($_GET['id'])){

$query = query("SELECT * FROM users WHERE user_id = " . escape_string($_GET['id']) . " ");
confirm($query);

while($row = fetch_array($query)){

    $firstname = escape_string($row['firstname']);
    $lastname = escape_string($row['lastname']);
    $username = escape_string($row['username']);
    $email = escape_string($row['email']);
    $password = escape_string($row['password']);
}


update_user();

}

 ?>





                        <h1 class="page-header">
                            Edit User
                        </h1>

                    <form action="" method="post" enctype="multipart/form-data">


                        <div class="col-md-6">


                            <div class="form-group">
                                <label for="first name">First Name</label>
                            <input type="text" name="firstname" class="form-control" value="<?php echo $firstname; ?>">
                               
                           </div>

                            <div class="form-group">
                                <label for="last name">Last Name</label>
                            <input type="text" name="lastname" class="form-control" value="<?php echo $lastname; ?>">
                               
                           </div>

                           <div class="form-group">
                             <label for="last name">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                               
                           </div>

                           <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control"  value="<?php echo $username; ?>">
                               
                           </div>


                            <div class="form-group">
                                <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                               
                           </div>

                            <div class="form-group">

                            <input type="submit" name="update_user" class="btn btn-primary pull-right" value="Update" >
                               
                           </div>


                            

                        </div>

                      

            </form>





    