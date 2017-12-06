<?php
ob_start();
session_start();
include_once 'dbconnect.php';

if (isset($_SESSION['user']) != "") {
    header("Location: index.php");
}

if (isset($_POST['signup'])) {

    $uname = trim($_POST['name']);
    $email = trim($_POST['email']);
    $upass = trim($_POST['pass']);

    $password = hash('sha256', $upass);
    $stmt = $conn->prepare("SELECT email FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $count = $result->num_rows;
    // email n'est pas trouvé, ajouter un utilisateur
    if ($count == 0) {

        $stmts = $conn->prepare("INSERT INTO users(username,email,password) VALUES(?, ?, ?)");
        $stmts->bind_param("sss", $uname, $email, $password);
        $res = $stmts->execute();
        $stmts->close();
        $user_id = mysqli_insert_id($conn);
        if ($user_id > 0) {
            $_SESSION['user'] = $user_id;
            if (isset($_SESSION['user'])) {
                header("Location: index.php"); // rediriger vers la page index
                exit;
            }
        } else {
            $errMSG = "Something went wrong, try again";
        }
    } else {
        $errMSG = "Email is already used";
    }
}
?>
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Registration</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"/>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css"/>
</head>
<body>

<div class="container">

    <div id="login-form">
        <form method="POST" autocomplete="off">
            <div class="col-md-12">
                <div class="form-group">
                    <h2 class="">Register for our Website</h2>
                </div>
                <?php
                if (isset($errMSG)) {
                ?>
                    <div class="form-group">
                        <div class="alert">
                            <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                        </div>
                    </div>
                <?php
                }
                ?>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                        <input type="text" name="name" class="form-control" placeholder="Enter Username" required/>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                        <input type="email" name="email" class="form-control" placeholder="Enter Email" required/>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                        <input type="password" name="pass" class="form-control" placeholder="Enter Password"
                               required/>
                    </div>
                </div>
                    <button type="submit" class="btn    btn-block btn-primary" name="signup" id="reg">Register</button>
                    <a href="login.php" type="button" class="btn btn-block btn-success" name="btn-login">Login</a>
            </div>
        </form>
    </div>

</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/js/tos.js"></script>

</body>
</html>
