<?php
$host = getenv('DB_HOST');
$db_name = getenv('MYSQL_DATABASE');
$username = getenv('DB_USER');
$password = getenv('MYSQL_PASSWORD');
$option = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  );

# Catch errors
try{
    $connection = new PDO("mysql:host=" . $host . ";dbname=" . $db_name, $username, $password, $option);
    $connection->exec("set names utf8");
} catch(PDOException $exception){
    echo "Connection error: " . $exception->getMessage();
}


function saveData($name, $email, $message){
    global $connection;
    $query = "INSERT INTO test(name, email, message) VALUES( :name, :email, :message)";

    try {
        $callToDb = $connection->prepare( $query );
        $name=htmlspecialchars(strip_tags($name));
        $email=htmlspecialchars(strip_tags($email));
        $message=htmlspecialchars(strip_tags($message));
        $callToDb->bindParam(":name",$name);
        $callToDb->bindParam(":email",$email);
        $callToDb->bindParam(":message",$message);

        if($callToDb->execute()){
            return '<h3 style="text-align:center;">Your information has been submitted to the database successfully!</h3>';
        }   else {
            return '<h3 style="text-align:center;">Failed to save data.</h3>';
        }
    } catch (PDOException $exception) {
        return '<h3 style="text-align:center;">Error: ' . $exception->getMessage() . '</h3>';
    }
}


if( isset($_POST['submit'])){
    $name = htmlentities($_POST['name']);
    $email = htmlentities($_POST['email']);
    $message = htmlentities($_POST['message']);

    //then you can use them in a PHP function. 
    $result = saveData($name, $email, $message);
    echo $result;
} else{
    echo '<h3 style="text-align:center;">A very detailed error message ( ͡° ͜ʖ ͡°)</h3>';
}
?>