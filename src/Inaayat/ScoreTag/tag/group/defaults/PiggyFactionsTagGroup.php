<?php

declare(strict_types=1);

namespace Inaayat\ScoreTag\tag\group\defaults;


use Inaayat\ScoreTag\Main;
use Inaayat\ScoreTag\tag\ExternalPluginTag;
use Inaayat\ScoreTag\tag\group\PluginTagGroup;
use Inaayat\ScoreTag\tag\TagFactory;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use function round;
use const PHP_ROUND_HALF_DOWN;

class PiggyFactionsTagGroup extends PluginTagGroup {

	/**
	 * PiggyFactionsTagGroup constructor.
	 * @param Main $plugin
	 */
	public function __construct(Main $plugin) {
		parent::__construct($plugin, "PiggyFactions");
	}

	/**
	 * @param TagFactory $factory
	 * @return ExternalPluginTag[]
	 */
	public function register(TagFactory $factory): array {
		return [
			new ExternalPluginTag("faction_name", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return ($plugin->getPlayerManager()->getPlayer($player->getUniqueId())->getFaction())->getName() ?? "None";
			}),
			new ExternalPluginTag("faction_power", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				$faction = $plugin->getPlayerManager()->getPlayer($player->getUniqueId())->getFaction();
				return $faction !== null ? (string) round($faction->getPower() ?? -1, 2, PHP_ROUND_HALF_DOWN) : "";
			}),
			new ExternalPluginTag("faction_rank", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return $plugin->getPlayerManager()->getPlayer($player->getUniqueId())->getRole() ?? "";
			})
		];
	}
}