<?php

namespace Inaayat\ScoreTag;

use pocketmine\scheduler\Task;
use Inaayat\ScoreTag\Main;

class ScoreTagTask extends Task{

	private $plugin;

	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
	
	public function onRun(int $tick):void{
	    foreach($this->plugin->getServer()->getOnlinePlayers() as $players){
            $player = $players->getPlayer();
            $name = $players->getName();
            $tps = $this->plugin->getServer()->getTicksPerSecond();
            $usage = $this->plugin->getServer()->getTickUsage();
	    $online = count($this->plugin->getServer()->getOnlinePlayers());
            $max_online = $this->plugin->getServer()->getMaxPlayers();
            $x = round($players->getX(), 0);
            $y = round($players->getY(), 0);
            $z = round($players->getZ(), 0);
            $item = $players->getInventory()->getItemInHand()->getName();
            $id = $players->getInventory()->getItemInHand()->getId();
            $ids = $players->getInventory()->getItemInHand()->getDamage();
            $level = $players->getLevel()->getName();
            $ping = $players->getPing($name);
            
            $tag = $this->plugin->config->get("ScoreTag");
            $tag = str_replace("{cps}", $this->plugin->getCPS($players), $tag);
	    $tag = str_replace("&", "§", $tag);
            $tag = str_replace("{name}", $name, $tag);
            $tag = str_replace("{tps}", $tps, $tag);
            $tag = str_replace("{usage}", $usage, $tag);
            $tag = str_replace("{online}", $online, $tag);
            $tag = str_replace("{max_online}", $ids, $tag);
            $tag = str_replace("{x}", $x, $tag);
            $tag = str_replace("{y}", $y, $tag);
            $tag = str_replace("{z}", $z, $tag);
            $tag = str_replace("{item}", $item, $tag);
            $tag = str_replace("{id}", $id, $tag);
            $tag = str_replace("{ids}", $ids, $tag);
            $tag = str_replace("{level}", $level, $tag);
            $tag = str_replace("{ping}", $ping, $tag);

	    $players->setScoreTag($tag);
		}
	}
}
