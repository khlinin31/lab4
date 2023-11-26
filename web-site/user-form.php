<?php
    require_once './boot.php';

    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $patronymic = $_POST["patronymic"];
    $address = $_POST["address"];
    $telephone = $_POST["telephone"];
    $email = $_POST["email"];
    $message = $_POST["message"];
    $goods = $_POST["goods"];

    try {
        pdo()->beginTransaction();
        $stmt = pdo()->prepare("SELECT u.id FROM site.user u WHERE u.login = :login");
        $stmt->execute(["login" => $_SESSION["user_id"]]);
        $user_id = null;
        while ($row = $stmt->fetch()) {
            $user_id = $row["id"];
        }
        $good_names = get_in_list($goods);
        $stmt = pdo()->prepare("SELECT g.id FROM site.good g WHERE g.name IN ($good_names)");
        $stmt->execute();
        $goods_ids = [];
        while ($row = $stmt->fetch()) {
            $goods_ids[] = $row["id"];
        }

        $stmt = pdo()->prepare(
            "INSERT INTO site.order(user_id, name, surname, patronymic, address, number, email, comment)".PHP_EOL.
            "VALUES(:user_id, :name, :surname, :patronymic, :address, :number, :email, :comment)"
        );
        $stmt->execute(
            [
                "user_id" => $user_id,
                "name" => $name,
                "surname" => $surname,
                "patronymic" => $patronymic,
                "address" => $address,
                "number" => $telephone,
                "email" => $email,
                "comment" => $message
            ]
        );
        $order_id = pdo()->lastInsertId();

        $order_to_good_query = "INSERT INTO site.order_to_good(order_id, good_id) VALUES";
        if (sizeof($goods_ids) == 0) {
            throw new PDOException("Order must have goods!");
        }

        foreach ($goods_ids as $index => $good_id) {
            if ($index == sizeof($goods_ids) - 1) {
                $order_to_good_query .= "($order_id, $good_id)";
            } else {
                $order_to_good_query .= "($order_id, $good_id),";
            }
        }
        echo $order_to_good_query;
        pdo()->exec($order_to_good_query);
        pdo()->commit();
    } catch (PDOException $ex) {
        pdo()->rollBack();
        throw $ex;
    }

    header('Location: /web-site/index.php');

