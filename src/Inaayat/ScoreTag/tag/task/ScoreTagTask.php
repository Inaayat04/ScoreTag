<?php

declare(strict_types=1);

namespace Inaayat\ScoreTag\tag\task;


use Inaayat\ScoreTag\Main;
use Inaayat\ScoreTag\tag\TagFactory;
use Inaayat\ScoreTag\utils\MainPluginTrait;
use pocketmine\scheduler\Task;
use pocketmine\scheduler\TaskHandler;

class ScoreTagTask extends Task {

	use MainPluginTrait;

	/** @var TagFactory */
	private $tagFactory;

	/**
	 * ScoreTagTask constructor.
	 * @param Main $plugin
	 * @param TagFactory $tagFactory
	 */
	public function __construct(Main $plugin, TagFactory $tagFactory) {
		$this->setPlugin($plugin);
		$this->tagFactory = $tagFactory;
	}

	/**
	 * @return TagFactory
	 */
	public function getTagFactory(): TagFactory {
		return $this->tagFactory;
	}

	public function start(): void {
		if($this->getHandler() === null || ($this->getHandler() instanceof TaskHandler && !$this->getHandler()->isCancelled())) {
			$this->getPlugin()->getScheduler()->scheduleRepeatingTask($this, $this->getTagFactory()->getUpdatePeriod());
		}
	}

	public function restart(): void {
		$this->cancel();
		$this->start();
	}

	public function cancel(): void {
		$this->getHandler()->cancel();
	}

	/**
	 * @param int $currentTick
	 */
	public function onRun(int $currentTick): void {
		$this->getTagFactory()->update();
	}


}