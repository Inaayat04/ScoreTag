<?php

declare(strict_types=1);

namespace Inaayat\ScoreTag\tag\group\defaults;


use Inaayat\ScoreTag\Main;
use Inaayat\ScoreTag\tag\ExternalPluginTag;
use Inaayat\ScoreTag\tag\group\PluginTagGroup;
use Inaayat\ScoreTag\tag\TagFactory;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class EconomyAPITagGroup extends PluginTagGroup {

	/**
	 * EconomyAPITagGroup constructor.
	 * @param Main $plugin
	 */
	public function __construct(Main $plugin) {
		parent::__construct($plugin, "EconomyAPI");
	}

	/**
	 * @param TagFactory $factory
	 * @return ExternalPluginTag[]
	 */
	public function register(TagFactory $factory): array {
		return [
			new ExternalPluginTag("money", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return (string) $plugin->myMoney($player);
			})
		];
	}
}