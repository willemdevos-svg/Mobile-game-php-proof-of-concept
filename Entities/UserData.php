<?php
//entities/UserData.php

namespace Entities;

use DateTime;

class UserData
{

    private int $id;
    private int $spendEnergy_total;
    private DateTime $energy_lt;
    private int $money;
    private float $damage_outp;

    public function __construct(int $id, int $spendEnergy_total, DateTime $energy_lt, int $money, float $damage_outp)
    {
        $this->id = $id;
        $this->spendEnergy_total = $spendEnergy_total;
        $this->energy_lt = $energy_lt;
        $this->money = $money;
        $this->damage_outp = $damage_outp;
    }

    function getId(): int
    {
        return $this->id;
    }

    function getSpendEnergy(): string
    {
        return $this->spendEnergy_total;
    }

    function getEnergy_lt(): DateTime
    {
        return $this->energy_lt;
    }

    function getMoney(): int
    {
        return $this->money;
    }

    function getDamage_outp(): float
    {
        return $this->damage_outp;
    }
}
