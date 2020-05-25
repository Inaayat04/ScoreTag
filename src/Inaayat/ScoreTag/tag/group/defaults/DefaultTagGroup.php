<?php

declare(strict_types=1);

namespace Inaayat\ScoreTag\tag\group\defaults;


use Inaayat\ScoreTag\tag\group\TagGroup;
use Inaayat\ScoreTag\tag\Tag;
use Inaayat\ScoreTag\tag\TagFactory;
use pocketmine\Player;
use function count;
use function round;

class DefaultTagGroup extends TagGroup {

	/**
	 * @param TagFactory $factory
	 * @return array
	 */
	public function load(TagFactory $factory): array {
		return [
			new Tag("name", function (Player $player): string {
				return $player->getName();
			}),
			new Tag("tps", function (Player $player): string {
				return (string) $this->getPlugin()->getServer()->getTicksPerSecond();
			}),
			new Tag("online", function (Player $player): string {
				return (string) count($this->getPlugin()->getServer()->getOnlinePlayers());
			}),
			new Tag("max", function (Player $player): string {
				return (string) $this->getPlugin()->getServer()->getMaxPlayers();
			}),
			new Tag("x", function (Player $player): string {
				return (string) $player->getFloorX();
			}),
			new Tag("y", function (Player $player): string {
				return (string) $player->getFloorY();
			}),
			new Tag("z", function (Player $player): string {
				return (string) $player->getFloorZ();
			}),
			new Tag("level", function (Player $player): string {
				return $player->isValid() ? $player->getLevel()->getName() : "unknown";
			}),
			new Tag("itemId", function (Player $player): string {
				return (string) $player->getInventory()->getItemInHand()->getId();
			}),
			new Tag("itemDamage", function (Player $player): string {
				return (string) $player->getInventory()->getItemInHand()->getDamage();
			}),
			new Tag("itemName", function (Player $player): string {
				return $player->getInventory()->getItemInHand()->getName();
			}),
			new Tag("usage", function (Player $player): string {
				return (string) $this->getPlugin()->getServer()->getTickUsage();
			}),
			new Tag("ping", function (Player $player): string {
				return (string) $player->getPing();
			}),
			new Tag("cps", function (Player $player): string {
				return (string) $this->getPlugin()->getCPS($player);
			}),
			new Tag("hp", function (Player $player): string {
				return (string) round($player->getHealth(), 2);
			}),
			new Tag("max_hp", function (Player $player): string {
				return (string) $player->getMaxHealth();
			})
		];
	}
}