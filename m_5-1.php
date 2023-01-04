<!DOCTYPE html>
<html lang = "ja">
    <head>
        <meta charset = "UTF-8">
        <title>mission5-1</title>
    </head>
    
    <body>
        <?php
        //データベース接続 + テーブルの作成 
        echo "<hr>";
        $dsn = 'データベース名';
        $user = 'ユーザーネーム名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        $sql = "CREATE TABLE IF NOT EXISTS tech"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date TEXT,"
        . "pw char(32)"
        .");";
        $stmt = $pdo->query($sql);
        
        //データベース構成詳細
        $sql2 = 'SHOW CREATE TABLE tech';
        $result = $pdo -> query ($sql2);
        foreach ($result as $row) {
            echo $row[1];
            echo '<br>';
        }
        echo "<hr>";
        
        //編集番号の取得
        if (!empty ($_POST ["edit"] && $_POST["pw3"])) {
            $edit = $_POST["edit"];
            $pw3 = $_POST["pw3"];
            $sql_edit = 'SELECT * FROM tech';
            $stmt_edit = $pdo -> query ($sql_edit);
            $result_edit = $stmt_edit -> fetchALL ();
            foreach ($result_edit as $line) {
                if ($line['id'] == $edit && $line['pw'] == $pw3) {
                    $name = $line['name'];
                    $comment = $line['comment'];
                }
            }
        }
        ?>
        
        <form method = "POST" action = "">
            【投稿フォーム】<br>
            <input type = "text" name = "nm" placeholder = "名前の入力" value = <?php
            if (!empty ($name)) {
                echo $name;
            }
            ?>><br>
            <input type = "text" name = "come" placeholder = "コメントの入力" value = <?php
            if (!empty ($comment)) {
                echo $comment;
            }
            ?>><br>
            <input type = "text" name = "pw" placeholder = "パスワードの入力">
            <input type = "hidden" name = "hide" placeholder = "編集番号の取得" value = <?php
            if (!empty ($edit)) {
                echo $edit;
            }
            ?>>
            <input type = "submit" name = submit value = "投稿">
            <br><br>
            【削除フォーム】<br>
            <input type = "number" name = "del" placeholder = "削除番号"><br>
            <input type = "password" name = "pw2" placeholder = "パスワードの入力">
            <input type = "submit" name = "not" value = "削除">
            <br><br>
            【編集フォーム】<br>
            <input type = "number" name = "edit" placeholder = "編集番号"><br>
            <input type = "password" name = "pw3" placeholder = "パスワードの入力">
            <input type = "submit" name = "edi" value = "編集"><br>
        </form>
        
        <?php
        $nm   = $_POST["nm"];
        $come = $_POST["come"];
        $pw   = $_POST["pw"];
        $hide = $_POST["hide"];
        $del  = $_POST["del"];
        $pw2  = $_POST["pw2"];
        $date = date ("Y/m/d H:i:s");
        
        //削除機能
        if (!empty ($del && $pw2)) {
            $sql_del = 'SELECT * FROM tech';
            $stmt_del = $pdo -> query ($sql_del);
            $result_del = $stmt_del -> fetchALL ();
            foreach ($result_del as $line2) {
                if ($line2['id'] == $del && $line2['pw'] == $pw2) {
                    $id = $del;
                    $sql_del = 'delete from tech where id=:id';
                    $stmt_del = $pdo -> prepare($sql_del);
                    $stmt_del -> bindParam (':id', $id, PDO::PARAM_INT);
                    $stmt_del -> execute ();
            
                    $sql_del = 'SELECT id, name, comment, date FROM tech';
                    $stmt_del = $pdo -> query ($sql_del);
                    $result_del = $stmt_del -> fetchALL ();
                    foreach ($result_del as $row) {
                        echo $row['id']. "<>";
                        echo $row['name']. "<>";
                        echo $row['comment']. "<>";
                        echo $row['date']. "<br>";
                        echo "<hr>";
                    }
                    echo "<br>";
                }
            } 
        }
        //投稿機能
        // }else
        if (!empty ($nm && $come && $pw ) && empty ($hide)) {
            $sql = $pdo -> prepare ("INSERT INTO tech (name, comment, date, pw) 
            VALUES (:name, :comment, :date, :pw)");
            $sql -> bindParam (':name', $nm, PDO::PARAM_STR);
            $sql -> bindParam (':comment', $come, PDO::PARAM_STR);
            $sql -> bindParam (':date', $date, PDO::PARAM_STR);
            $sql -> bindParam (':pw', $pw, PDO::PARAM_STR);
            $sql -> execute ();
        
            $sql = 'SELECT id, name, comment, date FROM tech';
            $stmt = $pdo -> query ($sql);
            $result = $stmt -> fetchALL ();
            foreach ($result as $row) {
                echo $row['id']. "<>";
                echo $row['name']. "<>";
                echo $row['comment']. "<>";
                echo $row['date']. "<br>";
                echo "<hr>";
            }
            echo "<br>";
        }
        
        //編集モード
        if (!empty ($nm && $come && $pw && $hide)) {
            $sql_up = 'SELECT * FROM tech';
            $stmt_up = $pdo -> query ($sql_up);
            $result_up = $stmt_up -> fetchALL ();
            foreach ($result_up as $line3) {
                if ($line3['id'] == $hide) {
                    $id_up = $hide;
                    $name_up = $nm;
                    $comment_up = $come;
                    $sql_up = 'UPDATE tech SET name=:name,comment=:comment WHERE id=:id';
                    $stmt_up = $pdo -> prepare ($sql_up);
                    $stmt_up -> bindParam (':name', $name_up, PDO::PARAM_STR);
                    $stmt_up -> bindParam (':comment', $comment_up, PDO::PARAM_STR);
                    $stmt_up -> bindParam (':id', $id_up, PDO::PARAM_INT);
                    $stmt_up -> execute ();
                    
                    $sql_up = 'SELECT id, name, comment, date FROM tech';
                    $stmt_up = $pdo -> query ($sql_up);
                    $result_up = $stmt_up -> fetchALL ();
                    foreach ($result_up as $row) {
                        echo $row['id']. "<>";
                        echo $row['name']. "<>";
                        echo $row['comment']. "<>";
                        echo $row['date']. "<br>";
                        echo "<hr>";
                    }
                    echo "<br>";
                }
            }
        }
        ?>
    </body>
</html>