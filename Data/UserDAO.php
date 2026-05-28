<?php
//data.UserDAO.php

namespace Data;

use \PDO;
use entities\User;

class UserDAO
{
    public function getUserByName(string $name): ?User
    {
        $sql = "select id, username, password from mw_users where username = :name";
        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':name' => $name));
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);

        $user = null;
        if ($rij) {
            $user = new User((int)$rij["id"], $rij["username"], $rij["password"]);
        }
        $dbh = null;
        return $user;
    }
    public function getUserById(int $id): ?User
    {
        $sql = "select id, username, email, password from mg_users where id = :id";
        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':id' => $id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $user = null;
        if ($row) {
            $user = new User((int)$row["id"], $row["username"], $row["email"], $row["password"]);
        }
        $dbh = null;
        return $user;
    }
    public function register(string $username, string $email, string $password)
    {
            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $stmt = $dbh->prepare("insert into mw_users (username, email, password) values (:username, :email, :password)");
            $stmt->execute([':username' => $username, ':email' => $email, ':password' => password_hash($password, PASSWORD_DEFAULT)]);
            $userId = $dbh->lastInsertId();
            $dbh = null;
            return (int)$userId;
    }
}
