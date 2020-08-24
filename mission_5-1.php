<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>

<?php
    //接続
    $dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS ggg"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
    . "comment TEXT,"
    . "datetime datetime,"
    . "password char(32)"
	.");";
    $stmt = $pdo->query($sql);

    
    //入力
    if(isset($_POST["Ssubmit"])&&empty($_POST["longnumber"])&&!empty($_POST["pass"]))
    {
        
        $sql = $pdo -> prepare("INSERT INTO ggg (name,comment,datetime,password) VALUES (:name, :comment, :datetime, :password)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':datetime', $date, PDO::PARAM_STR);
        $sql -> bindParam(':password', $pass, PDO::PARAM_STR);
        $name =$_POST["name"];
        $comment=$_POST["comment"];
        $date= date("Y/m/d/. H:i:s");
        $pass=$_POST["pass"];
        $sql -> execute();
    }
	
    
    //削除
    if(isset($_POST["Dsubmit"]))
    {
        $id=$_POST["delete"];
        $password=$_POST["Dpass"];
        $sql = 'SELECT * FROM ggg WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();  
	    $results = $stmt->fetchAll();
        foreach ($results as $row)
        {
            //$rowの中にはテーブルのカラム名が入る
            if($row['password']==$password)
            {
                $sql = 'delete from ggg where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
		    
        }
        //$all=$stmt->fetchAll();
        //foreach($all as $loop)
        //{
            //if($id==$id&&$pass==$Dpass)
            //{
                //echo "削除済み".'<br>';
            //}else
                //{
                    //echo $loop['id'].',';
                    //echo $loop['name'].',';
                    //echo $loop['comment'].',';
                    //echo $loop["datetime"].'<br>'; 
                //}   

        //}
       
    }

    //編集
    if(isset($_POST["Esubmit"]))
    {
        $id =$_POST["edit"]; //変更する投稿番号

        $sql = 'SELECT * FROM ggg';
	    $stmt = $pdo->query($sql);
	    $all = $stmt->fetchAll();
        foreach ($all as $loop)
        {
            if($loop['id']==$id)
            {
                
		        $Xid=$loop['id'];
		        $Xname=$loop['name'];
                $Xcomment=$loop['comment'];
                $Xpass=$loop['password'];
            }   
                
        }
    }    
       
    if(isset($_POST["Ssubmit"])&&isset($_POST["longnumber"]) && !empty($_POST["pass"]))
    {
        $id = $_POST["longnumber"];
        $name = $_POST["name"];
	    $comment =$_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
	    $sql = 'UPDATE ggg SET name=:name,comment=:comment WHERE id=:id';
	    $stmt = $pdo->prepare($sql);
	    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
    }
    
    
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <h1>みんなの掲示板</h1>
    <h2>ディズニー作品、お勧めがあれば教えて！</h2>
    
    <form action="mission_5-1.php" method="post">
        【 投稿フォーム 】<br>
        <input type="text" name="name" placeholder="名前" value="<?php if(isset($_POST["Esubmit"])){ echo $Xname; }?>">
        <input type="text" name="comment" size="50" placeholder="コメント" value="<?php if(isset($_POST["Esubmit"])){ echo $Xcomment; }?>">
        <br><input type="text" name="pass" size="20" placeholder="パスワード" value="<?php if(isset($_POST["Esubmit"])){ echo $Xpass; }?>" >
        <input type="submit" name="Ssubmit"><br><br>
        
        【 削除フォーム 】<br>
        <input type="number" name="delete" placeholder="対象番号">
        <input type="text" name="Dpass" placeholder="パスワード">
        <input type="submit" name="Dsubmit" value="削除"><br><br>
        
        【 編集フォーム 】<br>
        <input type="hidden" name="longnumber"  placeholder="不可視" value="<?php if(isset($Xid)){ echo $Xid; }?>">
        <input type="number" name="edit" placeholder="対象番号">
        <input type="text" name="Epass" placeholder="パスワード">
        <input type="submit" name="Esubmit" value="編集"><br><br>
        
    </form>
</body>
</html>

<?php
//表示

    echo"【 投稿一覧 】"."<br>";
    echo"☆------------------------------------------------------------------------------------☆"."<br>";
    
    //表示
    $sql = 'SELECT * FROM ggg';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
    foreach ($results as $row)
    {
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
        echo $row['comment'].',';
        echo $row["datetime"].'<br>';
	echo "<hr>";
    }

   
    
    echo "☆----------------------------------------------------------------------------------☆";
?>
</body>
</html>