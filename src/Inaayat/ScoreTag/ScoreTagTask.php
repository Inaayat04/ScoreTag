<?php

namespace Inaayat\ScoreTag;

use pocketmine\scheduler\Task;
use Inaayat\ScoreTag\Main;
use DaPigGuy\PiggyFactions\players\PlayerManager;

class ScoreTagTask extends Task{

	private $plugin;

	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
	
	public function onRun(int $tick){
		foreach($this->plugin->getServer()->getOnlinePlayers() as $players){
		    $player = $players->getPlayer();
            $name = $players->getName();
            $tps = $this->plugin->getServer()->getTicksPerSecond();
            $usage = $this->plugin->getServer()->getTickUsage();
            $online = $online = count($this->plugin->getServer()->getOnlinePlayers());
            $max_online = $this->plugin->getServer()->getMaxPlayers();
            $x = round($players->getX(), 0);
            $y = round($players->getY(), 0);
            $z = round($players->getZ(), 0);
            $item = $players->getInventory()->getItemInHand()->getName();
            $id = $players->getInventory()->getItemInHand()->getId();
            $ids = $players->getInventory()->getItemInHand()->getDamage();
            $level = $players->getLevel()->getName();
            $ping = $players->getPing($name);
            $hp = $players->getHealth();
            $max_hp = $players->getMaxHealth();
            $line = "\n";
            
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
            $tag = str_replace("{hp}", $hp, $tag);
            $tag = str_replace("{max_hp}", $hp, $tag);
            $tag = str_replace("{line}", $line, $tag);

            $EconomyAPI = $this->plugin->getServer()->getPluginManager()->getPlugin("EconomyAPI");
            if (!is_null($EconomyAPI)) {
                $tag = str_replace('{money}', $EconomyAPI->myMoney($players), $tag);
            }

            $PurePerms = $this->plugin->getServer()->getPluginManager()->getPlugin("PurePerms");
            if (!is_null($PurePerms)) {
                $tag = str_replace('{rank}', $PurePerms->getUserDataMgr()->getGroup($players)->getName(), $tag);
                $tag = str_replace('{prefix}', $PurePerms->getUserDataMgr()->getNode($players, "prefix"), $tag);
                $tag = str_replace('{suffix}', $PurePerms->getUserDataMgr()->getNode($players, "suffix"), $tag);
            }

            $FactionsPro = $this->plugin->getServer()->getPluginManager()->getPlugin("FactionsPro");
            $factionName = $FactionsPro->getPlayerFaction($players->getName());
            if(!is_null($FactionsPro)){
                $tag = str_replace('{faction}', $FactionsPro->getPlayerFaction($players->getName()), $tag);
                $tag = str_replace('{fpower}', $FactionsPro->getFactionPower($factionName), $tag);
            }

            $Logger = $this->plugin->getServer()->getPluginManager()->getPlugin("CombatLogger");
            if (!is_null($Logger)) {
                $tag = str_replace('{combatlogger}', $Logger->getTagDuration($players), $tag);
            }

            $RedSkyBlock = $this->plugin->getServer()->getPluginManager()->getPlugin("RedSkyBlock");
            if (!is_null($RedSkyBlock)) {
                $tag = str_replace('{island_name}', $RedSkyBlock->getIslandName($players), $tag);
                $tag = str_replace('{island_rank}', $RedSkyBlock->calcRank(strtolower($players->getName())), $tag);
                $tag = str_replace('{island_value}', $RedSkyBlock->getValue($players), $tag);
            }
			
	    $PiggyFactions = $this->plugin->getServer()->getPluginManager()->getPlugin("PiggyFactions");
            if (!is_null($PiggyFactions)) {
		$member = PlayerManager::getInstance()->getPlayer($players->getUniqueId());
		$faction = $member->getFaction();
                $tag = str_replace('{faction_name}', $faction->getName(), $tag);
                $tag = str_replace('{faction_power}', round($faction->getPower(), 2, PHP_ROUND_HALF_DOWN), $tag);
		$tag = str_replace('{faction_rank}', $member->getRole(), $tag);
            }


		    $players->setScoreTag($tag);
		}
	}
}
