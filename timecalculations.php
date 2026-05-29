<?php
//spelen.php

declare(strict_types=1);
session_start();
class DBDataHandler
{
    private string $dbConn;
    private string $dbUsername;
    private string $dbPassword;

    private int $userId;

    private int $timeTokensCap = 20;

    public function __construct($userId)
    {
        $this->dbConn = "mysql:host=localhost;dbname=153willem;charset=utf8";
        $this->dbUsername = "root";
        $this->dbPassword = "";
        $this->userId = $userId;
    }
    public function getUserId()
    {
        return $this->userId;
    }
    public function timeToMoney()
    {
        $this->changeMoney($this->getSecondsSinceLastTrackedTime());
        $this->updateTrackedTime();
    }
    public function getSecondsSinceLastTrackedTime()
    {
        $dbh = new PDO($this->dbConn, $this->dbUsername, $this->dbPassword);
        $stmt = $dbh->prepare("select lastTrackedTime from userdata where id = :userId");
        $stmt->bindValue(':userId', $this->userId);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbh = null;
        return (new DateTime())->getTimestamp() - (new DateTime($data["lastTrackedTime"]))->getTimestamp();
    }
    public function getData($query): array
    {
        $dbh = new PDO($this->dbConn, $this->dbUsername, $this->dbPassword);
        $stmt = $dbh->prepare($query);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $dbh = null;
        return $data;
    }

    public function addData($query, $arrData)
    {
        $dbh = new PDO($this->dbConn, $this->dbUsername, $this->dbPassword);
        $stmt = $dbh->prepare($query);
        $stmt->execute($arrData);
        $dbh = null;
    }
    public function changeMoney(int $secondsSinceLastTrackedTime)
    {
        if ($secondsSinceLastTrackedTime > $this->timeTokensCap) {
            $secondsSinceLastTrackedTime = $this->timeTokensCap;
        }
        $moneyToAdd = $secondsSinceLastTrackedTime;

        $dbh = new PDO($this->dbConn, $this->dbUsername, $this->dbPassword);
        $stmt = $dbh->prepare("update userdata SET money = money + :money WHERE id = :userId");
        $stmt->execute([
            ':money' => $moneyToAdd,
            ':userId' => $this->userId
        ]);
        $dbh = null;
    }
    public function resetTimeTokens()
    {
        $dbh = new PDO($this->dbConn, $this->dbUsername, $this->dbPassword);
        $stmt = $dbh->prepare("update userdata SET timetokens = 0 WHERE id = :userId");
        $stmt->bindValue(':userId', $this->userId);
        $stmt->execute();
        $dbh = null;
    }
    public function updateTrackedTime()
    {
        $dbh = new PDO($this->dbConn, $this->dbUsername, $this->dbPassword);
        $stmt = $dbh->prepare("update userdata SET lastTrackedTime = :newDate WHERE id = :userId");
        $stmt->execute([':newDate' => date('Y-m-d H:i:s'), ':userId' => $this->userId]);
        $dbh = null;
    }
}


$dbdh = new DBDataHandler(2);
if (isset($_GET["action"])) {
    switch ($_GET["action"]) {
        case 'timeToMoney':
            $dbdh->timeToMoney();
            break;
        default:
            echo "jan";
    }

    // switch ($variable) {
    //     case 'value':
    //         # code...
    //         break;
        
    //     default:
    //         # code...
    //         break;
    // }
    header("Location:timecalculations.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Time calculations!</title>
</head>

<body>
    <h1>Mobile game idle mechanic database proof of concept</h1>
    <?php foreach ($dbdh->getData("select * from userdata where id = 2") as $data) { ?>
        <ul>
            <li>Your userId= <?php print($dbdh->getUserId()) ?></li>
            <li>Last time you converted time to money: <?php print($data["lastTrackedTime"]) ?></li>
            <li>
                Money: <?php print($data["money"]) ?>
            </li>
            <li>
                Timetokens: <span id="counter"><?php print($dbdh->getSecondsSinceLastTrackedTime()); ?></span>
            </li>
        </ul>
        <a href="timecalculations.php?action=timeToMoney">Turn your time into money.</a>
    <?php } ?>

    <script>
        // Arteficial display of timeTokens counter.
        const counterElement = document.getElementById("counter");
        let count = parseInt(counterElement.textContent, 10);
        if (count > 20) {
            count = 20;
            counterElement.textContent = count;
        } else {
            const interval = setInterval(() => {
                count++;
                counterElement.textContent = count;
                if (count >= 20) {
                    clearInterval(interval);
                }
            }, 1000);
        }
    </script>
</body>

</html>