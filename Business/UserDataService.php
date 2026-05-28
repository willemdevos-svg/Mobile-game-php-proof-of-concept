<?php
//business/UserDataService.php

namespace Business;

use Entities\UserData;
use Data\UserDataDAO;

class UserDataService
{
    public function getDataById(int $id): UserData
    {
        $userDataDAO = new UserDataDAO();
        return $userDataDAO->getDataById($id);
    }
    public function updateEnergyToMoney(int $id, int $energyAmount)
    {
        if ($energyAmount > 100) {
            $energyAmount = 100;
        }
        $userDataDAO = new UserDataDAO();
        $userDataDAO->updateEnergyToMoney($id, $energyAmount);
    }
    public function updateMoneyToDamage(int $id, int $money)
    {
        $userDataDAO = new UserDataDAO();
        $userDataDAO->updateMoneyToDamage($id, $money);
    }
    public function calculateBattleOutcome(int $userId, int $enemyId): string
    {
        $userDataDAO = new UserDataDAO();
        $outcome = $userDataDAO->calculateBattleOutcome($userId, $enemyId);
        switch ($outcome) {
            case 'user':
                return 'won';
            case 'enemy':
                return 'lost';
            case 'unknown':
                return 'unknown';
            case 'end':
                return 'end';
            default:
                return 'draw';
        }
    }
}
