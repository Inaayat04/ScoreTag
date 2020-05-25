<?php

declare(strict_types=1);


namespace Inaayat\ScoreTag\tag\group\defaults;


use Inaayat\ScoreTag\Main;
use Inaayat\ScoreTag\tag\ExternalPluginTag;
use Inaayat\ScoreTag\tag\group\PluginTagGroup;
use Inaayat\ScoreTag\tag\TagFactory;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class PurePermsTagGroup extends PluginTagGroup {

	/**
	 * PurePermsTagGroup constructor.
	 * @param Main $plugin
	 */
	public function __construct(Main $plugin) {
		parent::__construct($plugin, "PurePerms");
	}

	/**
	 * @param TagFactory $factory
	 * @return ExternalPluginTag[]
	 */
	public function register(TagFactory $factory): array {
		return [
			new ExternalPluginTag("rank", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return $plugin->getUserDataMgr()->getGroup($player)->getName();
			}),
			new ExternalPluginTag("prefix", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return $plugin->getUserDataMgr()->getNode($player, "prefix") ?? "";
			}),
			new ExternalPluginTag("suffix", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return $plugin->getUserDataMgr()->getNode($player, "suffix") ?? "";
			})
		];
	}
}