<?php
// var_dump($id);
if ($error) {
    echo "<div class='alert alert-danger'>$error</div>";
}
// echo $id;
//     if($id){
//         echo "User created successfully";
//     } else {
//         echo "Failed to create user";
//     }
    // echo "cdsfdsfd";
?>

<div class="container">
    <h1>Register</h1>
    <form action="/register" method="post">
        <input type="text" name="first_name" placeholder="First Name">
        <input type="text" name="last_name" placeholder="Last Name">
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Password">
        <button type="submit">Register</button>
    </form>
</div>