<?php


namespace Inaayat\ScoreTag\tag\group\defaults;


use Inaayat\ScoreTag\Main;
use Inaayat\ScoreTag\tag\ExternalPluginTag;
use Inaayat\ScoreTag\tag\group\PluginTagGroup;
use Inaayat\ScoreTag\tag\TagFactory;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use function var_dump;

class RankUpTagGroup extends PluginTagGroup {

	/**
	 * RankUpTagGroup constructor.
	 * @param Main $plugin
	 */
	public function __construct(Main $plugin) {
		parent::__construct($plugin, "RankUp");
	}

	/**
	 * @param TagFactory $factory
	 * @return ExternalPluginTag[]
	 */
	public function register(TagFactory $factory): array {
		return [
			new ExternalPluginTag("rankup", $this->getExternalPlugin(), function (Player $player, Plugin $plugin): string {
				return ($group = $plugin->getPermManager()->getGroup($player)) !== false ? $group : "";
			})
		];
	}
}