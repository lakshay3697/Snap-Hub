<?php

$page = "sign_up";
include_once("header.php");


?>

<div class="container">
    <form>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" placeholder="Enter name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" placeholder="Enter email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" placeholder="Enter Password" required>
        </div>
        <div class="form-group">
            <label for="password2">Password</label>
            <input type="password" class="form-control" name="password2" placeholder="Confirm Password" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>


<?php

include_once("footer.php");

?>