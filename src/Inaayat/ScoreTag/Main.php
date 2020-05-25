<?php

declare(strict_types=1);

namespace Inaayat\ScoreTag;

use Inaayat\ScoreTag\tag\TagFactory;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\utils\TextFormat;
use function in_array;

class Main extends PluginBase implements Listener {

	/** @var int[] */
	private $clicks = [];

	/** @var TagFactory */
	private $tagFactory;

	public function onLoad() {
		$this->saveDefaultConfig();
		$this->tagFactory = new TagFactory($this);
	}

	public function onEnable(): void {
		$this->getTagFactory()->enable();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info(TextFormat::GREEN . "{$this->getDescription()->getFullName()} has been enabled successfully!");
	}

	/**
	 * @return TagFactory
	 */
	public function getTagFactory(): TagFactory {
		return $this->tagFactory;
	}

	/**
	 * @param Player $player
	 * @return int
	 */
	public function getCPS(Player $player): int {
		if(!isset($this->clicks[$player->getLowerCaseName()])) {
			return 0;
		}
		$time = $this->clicks[$player->getLowerCaseName()][0];
		$clicks = $this->clicks[$player->getLowerCaseName()][1];
		if($time !== time()) {
			unset($this->clicks[$player->getLowerCaseName()]);
			return 0;
		}
		return $clicks;
	}

	/**
	 * @param Player $player
	 */
	public function addCPS(Player $player): void {
		if(!isset($this->clicks[$player->getLowerCaseName()])) {
			$this->clicks[$player->getLowerCaseName()] = [time(), 0];
		}
		$time = $this->clicks[$player->getLowerCaseName()][0];
		$clicks = $this->clicks[$player->getLowerCaseName()][1];
		if($time !== time()){
			$time = time();
			$clicks = 0;
		}
		$clicks++;
		$this->clicks[$player->getLowerCaseName()] = [$time, $clicks];
	}

	/**
	 * @param DataPacketReceiveEvent $event
	 */
	public function onDataPacketReceive(DataPacketReceiveEvent $event): void {
		$player = $event->getPlayer();
		$packet = $event->getPacket();
		if($packet instanceof InventoryTransactionPacket) {
			if(in_array($packet->transactionType, [InventoryTransactionPacket::TYPE_USE_ITEM, InventoryTransactionPacket::TYPE_USE_ITEM_ON_ENTITY])) {
				$this->addCPS($player);
			}
		}
	}
}
