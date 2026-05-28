<?php
//login.php
require_once("bootstrap.php");
session_start();

use Business\UserService;
use Business\UserDataService;

$currentUserId = 1;
$enemyId = 2;

$userSvc = new UserService;
$currentUser = $userSvc->getUserById($currentUserId);

$userDataSvc = new UserDataService;
$currentUserData = $userDataSvc->getDataById($currentUserId);

$secondsSinceLastEnergy = time() - $currentUserData->getEnergy_lt()->getTimeStamp();

switch ($currentUser->getSkin()) {
    case 'fg':
        $skin = 'femaleknight_grey';
        break;
    case 'fb':
        $skin = 'femaleknight_blue';
        break;
    case 'fp':
        $skin = 'femaleknight_pink';
        break;
    case 'mg':
        $skin = 'maleknight_grey';
        break;
    case 'mb':
        $skin = 'maleknight_blue';
        break;
    case 'mp':
        $skin = 'maleknight_pink';
        break;
    default:
        $skin = 'femaleknight_grey';
        break;
}

//URL ACTIONS
$pageFlag = 'home';
if (isset($_GET["action"])) {
    switch ($_GET["action"]) {
        case 'energyToMoney':
            if ($secondsSinceLastEnergy > 1) {
                $userDataSvc->updateEnergyToMoney($currentUserId, $secondsSinceLastEnergy);
                header("Location:index.php");
                exit;
            }
            break;
        case 'moneyToDamage':
            $money = $currentUserData->getMoney();
            if ($money > 5) {
                $userDataSvc->updateMoneyToDamage($currentUserId, $money);
                header("Location:index.php");
                exit;
            }
            break;
        case 'startBattle':
            if ($secondsSinceLastEnergy > 20) {
                $pageFlag = 'battle';
            } else {
                header("Location:index.php");
                exit;
            }
            break;
        case 'changeSkin':
            if ($skin != $currentUser->getSkin()) {
                $userSvc->updateSkin($currentUserId, $_POST["skin"]);
                header("Location:index.php");
                exit;
            };
            break;
        default:
            header("Location:index.php");
            exit;
    }
}

//PRINT PAGE
print $twig->render("header.twig", array("pagetitle" => 'Mobile Game', "userId" => $currentUserId));

switch ($pageFlag) {
    case 'home':
        print $twig->render(
            "home.twig",
            array("data" => $currentUserData, "lastEnergy" => $secondsSinceLastEnergy, "skin" => $skin)
        );
        break;
    case 'battle':
        print $twig->render(
            "battle.twig",
            array("outcome" => $userDataSvc->calculateBattleOutcome($currentUserId, $enemyId), "enemyId" => $enemyId)
        );
        break;
    default:
        print $twig->render(
            "home.twig",
            array("data" => $currentUserData, "lastEnergy" => $secondsSinceLastEnergy)
        );
}

print $twig->render("footer.twig");
