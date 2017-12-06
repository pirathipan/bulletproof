<?php
ob_start();
session_start();
include_once 'dbconnect.php';

if (isset($_SESSION['user']) != "") {
    header("Location: index.php");
}

if(isset($_SESSION['user'])){
    $stmt = $db->prepare(' SELECT name, id FROM users ');
    $stmt->execute();
    $users = $stmt->fetchAll();

    if(isset($_POST['delete-User'])){
        $deleleteUser = trim($_POST['delete-User']);
        $stmt = $conn->prepare("DELETE  id, username, password FROM users WHERE username= :username ");
        $stmt->bind_param("s", $deleleteUame);
        $result = $stmt->execute();

        if($result !== 0){
            if($_SESSION['user'] === $DeleleteUser){
                session_destroy();
            }
        }
    }

    $stmt = $db->prepare(' SELECT * FROM post ');
    $stmt->execute();
    $result = $stmt->fetchAll();
    if(sizeof($result) !== 0) {
        echo "List des postes";
    }else{
        echo "no posts yet !";
    }
}
?>
    <!DOCTYPE html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Login</title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"/>
        <link rel="stylesheet" href="assets/css/style.css" type="text/css"/>
    </head>
    <body>

    <div class="container">
        <div id="list-form" >
            <div class="form-group">
                <h2 class="">list:</h2>
            </div>
            <?php
            if(sizeof($users) !== 0) {
                ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <select id="select" name="user_to_delete" required>
                        <?php
                        foreach($users as $user){
                            ?>
                            <option value=<?php echo $user["username"] ?>><?php echo $user["username"] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <input type="submit" value="delete-User">

                </form>
                <?php
            }else{
                echo "No users yet !";
            }
            ?>
        </div>
    </div>
    <form method="post">
        <textarea name="message" required> </textarea>
        <input type="submit" value="send-msg">
    </form>


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
</body>
</html>
