<?php

namespace Enzo\NoHunger;

use pocketmine\block\utils\SignLikeRotationTrait;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class NoHunger extends PluginBase implements Listener
{
    use SingletonTrait;

    protected function onEnable(): void
    {
        self::setInstance($this);
        $this->getLogger()->info("§aPlugin Enabled.");
        $this->saveDefaultConfig();
        $config = new Config($this->getDataFolder() . "config.yml");

        if($config->get("food-diff", 0) > 0)
            $this->getScheduler()->scheduleRepeatingTask(new TickingTask(), 1);

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function canLoseHunger(Player $player): bool
    {
        $config = new Config($this->getDataFolder() . "config.yml");
        $worlds = $config->get("worlds", null);

        if(is_null($worlds))
            return false;

        return !in_array($player->getWorld()->getFolderName(), array_values($worlds));
    }

    public function onHungerLoss(PlayerExhaustEvent $event)
    {
        $player = $event->getPlayer();
        if(!$player instanceof Player)
            return;

        if(!$this->canLoseHunger($player))
        {
            $event->cancel();
            $player->getHungerManager()->setSaturation(20);
        }
    }
}