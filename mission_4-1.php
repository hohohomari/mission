

<?php
//接続
$dsn ='データベース名;bdhost=ホスト名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo =new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
?>
<?php
//データベース内にテーブルを作成する
$sql = "CREATE TABLE IF NOT EXISTS tbtest5"
."("
."id int not null AUTO_INCREMENT,"
."name char(32),"
."comment TEXT,"
."created DateTime,"
."password TEXT,"
."PRIMARY KEY(id)"
.");";
$stmt = $pdo -> query($sql);
 ?>
 <?php
 //テーブルの中身を確認して、意図して内容のテーブルが作成されているか確認
 //$sql ='SHOW CREATE TABLE tbtest5';
 //$result = $pdo -> query($sql);
 //foreach ($result as $row)
 //{
   //echo $row[1];
 //}
 //echo "<hr>"; ?>


<?php
/*新しい投稿*/
if(empty($_POST['hidden'])){
//コメントが空じゃないとき
if(!empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['password'])) {
//echo"hiddenは空";
//echo"書き込みました";


 //代入
 $name = $_POST['name'];
 $comment = $_POST['comment'];
 $created = date("Y/m/j G:i");
 $db_password = $_POST['password'];


//作成したテーブルにinsertを使って、データを入力する
$sql = $pdo -> prepare("INSERT INTO tbtest5 (name,comment,created,password)VALUES(:name,:comment,:created,:password)");
$sql -> bindParam(':name', $name, PDO::PARAM_STR);
$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
$sql -> bindParam(':created', $created, PDO::PARAM_STR);
$sql -> bindParam(':password', $db_password, PDO::PARAM_STR);
$name = $_POST['name'];
$comment = $_POST['comment'];
$created = date("Y/m/j G:i");
$db_password = $_POST['password'];

$sql -> execute();
}

else {
  echo "空欄があります";
}
}
if(!empty($_POST['hidden'])){
  if(!empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['password'])) {
    echo "上書きしました";

    //代入
    $id2=$_POST['hidden'];
    $ediname=$_POST['name'];
    $edicomment=$_POST['comment'];
    $created=date("Y/m/j G:i");
    $edipass=$_POST['password'];

    //テーブルにupdateで上書きする
    $sql ='update tbtest5 set name=:name,comment=:comment,created=:created,password=:password where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $ediname, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $edicomment, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id2, PDO::PARAM_INT);
    $stmt -> bindParam(':created', $created, PDO::PARAM_STR);
    $stmt -> bindParam(':password', $edipass, PDO::PARAM_STR);
    $stmt->execute();

}
else {
  echo "空欄があります。";
}
}

?>

<?php
header('Content-Type: text/html; charset=UTF-8');
/******削除用***********/
if(!empty($_POST['delno']) && !empty($_POST['delpass'])){
  //echo"削除分岐したよ<br/><br/>";
  $delno = $_POST['delno'];
  $delpass = $_POST["delpass"];

  $sql = 'SELECT password FROM tbtest5 order by id ASC';//passよみこむ
  $stmt = $pdo -> query($sql);
  while($results = $stmt->fetch(PDO::FETCH_ASSOC)){
    $db_password = $results['password'];
  }
 /*echo"<br>"."配列のパスワード";
  var_dump($db_password).'<br>';*/

  if($id = $delno && $db_password == $delpass ){//一致したら
   echo"削除したよ";

   $id = $delno;
   $sql ='delete from tbtest5 where id=:id';//idの部分けす
   $stmt = $pdo->prepare($sql);
   $stmt->bindParam(':id', $id, PDO::PARAM_INT);
   $stmt->execute();

  }else{
    echo "一致しません。";
 }

 /*echo'<br>'."配列の数字";
 var_dump($id).'<br>';
 echo"<br>"."入力した数字";
 var_dump($delno).'<br>';*/
}
//入力データをdeleteで削除する
?>




<?php
//編集
//入力データをupdateで編集する
if(!empty($_POST["hensyuNo"]) && !empty($_POST["edipass"])){
  //echo "編集の分岐にはいりました";
  $hensyu = $_POST["hensyuNo"];
  $edipass = $_POST["edipass"];

  if(is_numeric($hensyu)&&!empty($edipass))
  {

  //passよみこむ
  $sql = 'SELECT * FROM tbtest5 order by id ASC';//passよみこむ
  $stmt = $pdo -> query($sql);
  $results=$stmt->fetchAll();
  /***$stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id2, PDO::PARAM_INT);
    $stmt -> bindParam(':created', $created, PDO::PARAM_STR);
    $stmt -> bindParam(':password', $db_password, PDO::PARAM_STR);
     $stmt -> execute();***/
   foreach($results as $row){
    // echo "よみこむ";
     if($hensyu==$row['id']){
      // echo "表示用";
      //$id2 = $row['id'];
       $editpass = $row['password'];
     }
   }
//パス確認
if($editpass == $edipass){//一致したら～～
//echo"一致しています！";
$sql = 'SELECT * FROM tbtest5 order by id ASC';//passよみこむ
$stmt = $pdo -> query($sql);
$results=$stmt->fetchAll();
foreach($results as $row){
  //echo "よみこむ";
  if($hensyu==$row['id']){
$editname = $row['name'];
$editcomment = $row['comment'];
$hensyupass = $row['password'];
$henhidden = $rou['id'];
}
}
}
else{
  echo"パスワードが間違っています";
}
/**echo'<br>'."pas";
var_dump($editpass).'<br>';
echo"<br>"."kome";
var_dump($editcomment).'<br>';
echo'<br>'."name";
var_dump($editname).'<br>';
echo"<br>"."is_numeric id";
var_dump(is_numeric($id2)).'<br>';
echo"<br>"."id3";
var_dump($id2).'<br>';
echo"<br>"."入力したNo";
var_dump($hensyu)."<br>";
var_dump(is_numeric($hensyu)).'<br>';**/
}
}
?>




<?php
$dsn ='データベース名;bdhost=ホスト名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo =new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE =>
PDO::ERRMODE_WARNING));

//placeholder="fill in password"
?>

/***フォーム欄***/
<!DOCTYPE html>
<html lang = "ja">
<head>
<meta http-equiv="content-type" charset = "utf-8">
</head>
<body>
 <form action = "mission_4-1.php" method = "post">
名前：<input type = "text"  name = "name" value="<?php echo  $editname; ?>"><br>
こめ：<input type = "text"  name = "comment" value="<?php echo  $editcomment; ?>"><br>
暗号：<input type="password" name="password"  value="<?php echo  $hensyupass; ?>"/>
  <input type = "text" value = "<?php echo $henhidden?>" name = "hidden">
　<input type = "submit" name="sousin" value = "送信">
</form>

　<form method = "post" action="">
 削除指定番号:
   <input type = "text" name = "delno">
   <input type="password" name="delpass" size ="30" /><br/>
   <input type = "submit" value = "削除" name = "delete"><br>
</br>

<form method = "post" action="">
 へんしゅう！:
   <input type = "text"  name = "hensyuNo">
   <input type="password" name="edipass" size ="30" /><br/>
   <input type = "submit" value = "編集" name = "hensyub"><br>
</br>
</form>



<?php
//入力データをselectで表示する
header('Content-Type: text/html; charset=UTF-8');//PHP文字化け対策
$sql = 'SELECT * FROM tbtest5 order by id ASC';
$stmt = $pdo -> query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row)
{
  echo $row['id'].',';
  echo $row['name'].',';
  echo $row['comment'].',';
  echo $row['created'].'<br>';
} ?>

</body>
</html>
