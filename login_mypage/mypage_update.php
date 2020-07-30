<?php
mb_internal_encoding("utf8");
session_start();

try{
//try catch文。DBに接続出来なければエラーメッセージを表示
    $pdo = new PDO("mysql:dbname=suzuki;host=localhost;","root","");
}catch(PDOException $e){
    die("<p>申し訳ございません。現在サーバーが混みあっており一時的にアクセスが出来ません。<br>しばらくしてから再度ログインをしてください。</p>
    <a href='http://localhost/login_mypage/login.php'>ログイン画面へ</a>"
       );
}

//preared statement SQL文
$stmt = $pdo->prepare("update login_mypage set name= ?, mail = ?, password= ?, comments= ? where id= ?;");

//bindValue
$stmt->bindValue(1,$_POST["name"]);
$stmt->bindValue(2,$_POST["mail"]);
$stmt->bindValue(3,$_POST["password"]);
$stmt->bindValue(4,$_POST["comments"]);
$stmt->bindValue(5,$_SESSION["id"]);

$stmt->execute();

$stmt=$pdo->prepare("select * from login_mypage where mail = ? $$ password = ?");

$stmt->bindValue(1,$_POST["mail"]);
$stmt->bindValue(2,$_POST["password"]);

$stmt->execute();

while($row=$stmt->fetch()){
    $_SESSION['id']=$row['id'];
    $_SESSION['name']=$row['name'];
    $_SESSION['mail']=$row['mail'];
    $_SESSION['password']=$row['password'];
    $_SESSION['picture']=$row['picture'];
    $_SESSION['comments']=$row['comments'];
}

$stmt = NULL;

header("Location:mypage.php");

?>
