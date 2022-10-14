<?php

namespace Enzo\NoHunger;

use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\Config;

class TickingTask extends Task
{
    public function onRun(): void
    {
        $config = new Config(NoHunger::getInstance()->getDataFolder() . "config.yml");

        $diff = $config->get("food-diff", 0);

        if($diff > 0)
        {
            foreach(Server::getInstance()->getOnlinePlayers() as $player)
            {
                $h = $player->getHungerManager();
                $h->setFood(20 - $diff);
                $h->setSaturation(20);
                $h->setEnabled(false);
            }
        }
    }
}