<?php

namespace jblusitsme;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\world\particle\FloatingTextParticle;
use pocketmine\world\World;

class Main extends PluginBase implements Listener {

    protected function onEnable(): void {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onJoin(PlayerJoinEvent $event) {
        $sender = $event->getPlayer();
        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        foreach($config->get("messages") as $ft) {
            $mess = explode(":", $ft);
            $x = $mess[0]; $y = $mess[1]; $z = $mess[2]; $world = $mess[3]; $message = $mess[4];
            $replace = str_replace(
                array("{n}"),
                array("\n"),
                $message
            );
            $this->set(
                $sender, $x, $y, $z,
                $this->getServer()->getWorldManager()->getWorldByName($world),
                $replace
            );
        }
    }

    private function set(Player $sender, $x, $y, $z, World $world, string $text) {
        $world->addParticle(
            new Vector3($x, $y, $z),
            new FloatingTextParticle("", $text),
            [$sender]
        );
    }

}