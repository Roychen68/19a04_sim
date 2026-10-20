<?php
$pdo = new PDO ("mysql:host=localhost;charset=utf8;dbname=19a04_sim","root","");

$action = $_POST['action'];
switch ($action) {
    case 'locations':
        echo json_encode($pdo->query("SELECT * FROM `search`")->fetchAll());
        break;

    case 'review':
        echo json_encode($pdo->query("SELECT * FROM `review`")->fetchAll());
        break;

    case 'record':
    echo $pdo->query("SELECT `deleted` FROM `record`")->fetchColumn();
    break;
    
    case 'search':
        [$key,$order] = explode("+",$_POST['sorting']);
        $location = $pdo->quote($_POST['location']);
        $sql = "SELECT * FROM `search` WHERE ($location = 'all' OR `city` = $location) ORDER BY $key $order";
        echo json_encode($pdo->query($sql)->fetchAll());
        break;

    case 'admin':
        [$key,$order] = explode("+",$_POST['sorting']);
        $location = $pdo->quote($_POST['location']);
        $keyword = $pdo->quote("%{$_POST['keyword']}%");
        $sql = "SELECT * FROM `review` WHERE ($location = 'all' OR `nightmarket` = $location) AND ($keyword = '%%' OR `name` LIKE $keyword OR `email` LIKE $keyword OR `stand` LIKE $keyword) ORDER BY $key $order";
        echo json_encode($pdo->query($sql)->fetchAll());
        break;
    
    case 'like':
        $id = $pdo->quote($_POST['id']);
        $pdo->query("UPDATE `review` SET `likes`=`likes`+1 WHERE `id` = $id");
        echo $pdo->query("SELECT `likes` FROM `review` WHERE `id` = $id")->fetchColumn();
        break;

    case 'del':
        $id = $pdo->quote($_POST['id']);
        $pdo->query("UPDATE `record` SET `deleted`=`deleted`+1 WHERE 1");
        $pdo->query("DELETE FROM `review` WHERE `id` = $id");
        break;
    case 'submit':
        $path = "images/".$_FILES['image']['name'];
        $image = $_FILES['image']['tmp_name'];
        if ($_FILES['image']['size'] > 1024*1024*2) {
            echo json_encode(array("success" => 0,"message" => "投稿失敗"));
        } else {
            move_uploaded_file($image,$path);
            $stmt = $pdo->prepare("INSERT INTO `review`(`name`, `email`, `nightmarket`, `stand`, `price`, `rate`, `date`, `feedback`, `image`, `likes`) VALUES (?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([
                $_POST['name'],
                $_POST['email'],
                $_POST['market'],
                $_POST['stand'],
                $_POST['price'],
                $_POST['rate'],
                $_POST['date'],
                $_POST['feedback'],
                $path,
                0
            ]);
            echo json_encode(array("success" => 1,"message" => "投稿成功"));
        }
        break;
}
?>