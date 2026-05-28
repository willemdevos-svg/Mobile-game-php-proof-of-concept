<?php
//data.UserDataDAO.php

namespace Data;

use \PDO;
use DateTime;
use entities\UserData;

class UserDataDAO
{
    public function getDataById(int $id): ?UserData
    {
        $sql = "select id, spendEnergy_total, energy_lt, money, damage_outp from mg_userdata where id = :id";
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
            $user = new UserData(
                (int)$row["id"],
                $row["spendEnergy_total"],
                new DateTime($row["energy_lt"]),
                $row["money"],
                $row["damage_outp"]
            );
        }
        $dbh = null;
        return $user;
    }
    public function updateEnergyToMoney(int $id, int $energyAmount)
    {
        $sql = "update mg_userdata
                set spendEnergy_total = spendEnergy_total + :energyAmount,
                energy_lt = NOW(),
                money = money + :energyAmount
                where id = :id;";
        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':id' => $id, ':energyAmount' => $energyAmount));
        //$row = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbh = null;
    }
    public function updateMoneyToDamage(int $id, int $money)
    {
        $sql = "update mg_userdata
                set damage_outp = damage_outp + :money / 100,
                money = money - :money
                where id = :id;";
        $dbh = new PDO(
            DBConfig::$DB_CONNSTRING,
            DBConfig::$DB_USERNAME,
            DBConfig::$DB_PASSWORD
        );
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':id' => $id, ':money' => $money));
        $dbh = null;
    }
    public function calculateBattleOutcome(int $userId, int $enemyId)
{
    $dbh = new PDO(
        DBConfig::$DB_CONNSTRING,
        DBConfig::$DB_USERNAME,
        DBConfig::$DB_PASSWORD
    );

    // UPDATE user cooldown/energy timer
    $updateSql = "
        UPDATE mg_userdata
        SET energy_lt = DATE_ADD(energy_lt, INTERVAL 20 SECOND)
        WHERE id = :user_id
    ";

    $updateStmt = $dbh->prepare($updateSql);

    $updateStmt->execute([
        ':user_id' => $userId
    ]);

    // SELECT battle outcome
    $selectSql = "
        SELECT
            u.id AS user_id,
            e.id AS enemy_id,

            u.damage_outp AS user_damage,
            e.damage_outp AS enemy_damage,

            (u.damage_outp > e.damage_outp) AS user_wins,
            (e.damage_outp > u.damage_outp) AS enemy_wins,

            (u.damage_outp = e.damage_outp) AS draw

        FROM mg_userdata u
        CROSS JOIN mg_userdata e

        WHERE u.id = :user_id
        AND e.id = :enemy_id
    ";

    $stmt = $dbh->prepare($selectSql);

    $stmt->execute([
        ':user_id' => $userId,
        ':enemy_id' => $enemyId
    ]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $dbh = null;

    // Safety check
    if (!$result) {
        return 'draw';
    }

    if ($result['user_wins']) {
        return 'user';
    }

    if ($result['enemy_wins']) {
        return 'enemy';
    }

    return 'draw';
}
}
