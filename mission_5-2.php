<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UFT-8">
        <title>mission_3-5</title>
    </head>
    <body>
        <h1><span style="background-color: pink">掲示板</span></h1>
        <form method="POST" action="">
            <h3>新規投稿フォーム</h3>
            
            <p>名前:<input type="text" name="str" placeholder="名前を入力してください">
             投稿内容:<input type="text" name="com" style="width: 200px;" placeholder="コメントを入力してください"></p>
            <p>パスワード:<input type="password" name="pass" style="width: 220px;" placeholder="パスワードを決めてください">
            <input type="submit" value="送信"></p>
            
            <br>
            <h3>削除フォーム</h3>
            <p>削除対象の投稿番号:<input type="number" name="del" placeholder="削除対象番号"></p>
            <p>パスワード:<input type="password" name="pass1" style="width: 200px;" placeholder="パスワードを入力してください">
            <input type="submit" value="削除"></p>
            <br>
            <h3>編集フォーム</h3>
            <p>編集したい投稿番号:<input type="number" name="num" placeholder="編集対象番号">
            新しいコメント:<input type="text" name="rem" style="width: 220px;" placeholder="編集コメントを入力してください"></p>
            <p>パスワード:<input type="password" name="pass2" style="width: 200px;" placeholder="パスワードを入力してください">
            <input type="submit" value="編集"></p>
            
        </form>
        <?php
        //DB接続設定
        $dsn='mysql:dbname=******; host=localhost'; //接続先
        $user='******'; //ユーザー名
        $pass='******'; //パスワード
        //PDOのコンストラクタを呼び出す。MySQL接続
        $pdo= new PDO($dsn, $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        $sql = "CREATE TABLE IF NOT EXISTS boardtb"
        ." ("
        ."id INT AUTO_INCREMENT PRIMARY KEY,"
        ."name CHAR(32),"
        ."com TEXT,"
        ."dt datetime,"
        ."pass CHAR(32)"
        .");";
        $stmt = $pdo->query($sql);
        
        //$sql ='SHOW TABLES';
        //$result = $pdo -> query($sql);
        //foreach ($result as $row){
            //echo $row[0];
            //echo '<br>';
        //}
        //echo "<hr>";
        
        //$sql ='SHOW CREATE TABLE boardtb';
        //$result = $pdo -> query($sql);
        //foreach ($result as $row){
            //echo $row[1];
        //}
        //echo "<hr>";
        
        $date=date("Y/m/d H:i:s");
        if(!empty($_POST["str"]) && !empty($_POST["com"]) ){
            $name=$_POST["str"];
            $com=$_POST["com"];
            $pass=$_POST["pass"];
           if(!empty($_POST["pass"])){
                if(!empty("id")){
                    $sql = "INSERT INTO boardtb (name, com, dt, pass) VALUES (:name, :com, :dt, :pass)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR); 
                    $stmt->bindParam(':com', $com, PDO::PARAM_STR);
                    $stmt->bindParam(':dt', $date, PDO::PARAM_STR); 
                    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                    $stmt->execute();
                }
           }else{
               echo "<b>パスワードを決めてください<br></b>";
           }
        }
        
        //$sql ='SHOW CREATE TABLE boardtb';
        //$result = $pdo -> query($sql);
        //foreach ($result as $row){
            //echo $row[1];
        //}
        //echo "<hr>";
        
        
        if(!empty($_POST["del"])){
            $del=$_POST["del"];
            $pass1=$_POST["pass1"];
            $sql = 'delete from boardtb where id=:id and pass=:pass';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $del, PDO::PARAM_INT);
            $stmt->bindParam(':pass', $pass1, PDO::PARAM_STR);
            $stmt->execute();
            if(empty($pass1)){
                echo "<b>パスワードを入力してください<br></b>";
            }
        }
        
        if(!empty($_POST["num"])){
            $num=$_POST["num"];
            $pass2=$_POST["pass2"];
            $sql = 'UPDATE boardtb SET com=:com,dt=:dt WHERE id=:id and pass=:pass';
            $stmt = $pdo->prepare($sql);
            $editcom=$_POST["rem"];
            if(!empty($editcom)){
                $stmt->bindParam(':com', $editcom, PDO::PARAM_STR);
                $stmt->bindParam(':dt', $date, PDO::PARAM_STR); 
                $stmt->bindParam(':id', $num, PDO::PARAM_INT);
                $stmt->bindParam(':pass', $pass2, PDO::PARAM_STR); 
                $stmt->execute();
            }
            if(empty($pass2)){
                echo "<b>パスワードを入力してください<br></b>";
            }
        }
        
        
        echo "<b><br>掲示板コメント↓<br></b>";
        
        $sql = 'SELECT * FROM boardtb';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['com'].',';
            echo $row['dt'];
            //.',';
            //echo $row['pass'].'<br>';
            echo "<hr>";            
        }
        
        ?>
    </body>
</html>