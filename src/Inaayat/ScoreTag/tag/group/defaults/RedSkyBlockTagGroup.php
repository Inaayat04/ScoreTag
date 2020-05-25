<?php

declare(strict_types=1);

namespace Inaayat\ScoreTag\tag\group\defaults;


use Inaayat\ScoreTag\Main;
use Inaayat\ScoreTag\tag\ExternalPluginTag;
use Inaayat\ScoreTag\tag\group\PluginTagGroup;
use Inaayat\ScoreTag\tag\TagFactory;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class RedSkyBlockTagGroup extends PluginTagGroup {

	/**
	 * RedSkyBlockTagGroup constructor.
	 * @param Main $plugin
	 */
	public function __construct(Main $plugin) {
		parent::__construct($plugin, "RedSkyBlock");
	}

	/**
	 * @param TagFactory $factory
	 * @return ExternalPluginTag[]
	 */
	public function register(TagFactory $factory): array {
		return [
			new ExternalPluginTag("island_name", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return $plugin->getIslandName($player);
			}),
			new ExternalPluginTag("island_rank", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return $plugin->calcRank($player->getLowerCaseName());
			}),
			new ExternalPluginTag("island_value", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return (string) $plugin->getValue($player);
			})
		];
	}
}