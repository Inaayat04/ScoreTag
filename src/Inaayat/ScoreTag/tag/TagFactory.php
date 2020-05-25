<?php

declare(strict_types=1);

namespace Inaayat\ScoreTag\tag;

use Exception;
use Inaayat\ScoreTag\Main;
use Inaayat\ScoreTag\tag\group\defaults\CombatLoggerTagGroup;
use Inaayat\ScoreTag\tag\group\defaults\DefaultTagGroup;
use Inaayat\ScoreTag\tag\group\defaults\EconomyAPITagGroup;
use Inaayat\ScoreTag\tag\group\defaults\FactionsProTagGroup;
use Inaayat\ScoreTag\tag\group\defaults\PiggyFactionsTagGroup;
use Inaayat\ScoreTag\tag\group\defaults\PurePermsTagGroup;
use Inaayat\ScoreTag\tag\group\defaults\RedSkyBlockTagGroup;
use Inaayat\ScoreTag\tag\group\defaults\SeeDeviceTagGroup;
use Inaayat\ScoreTag\tag\group\TagGroup;
use Inaayat\ScoreTag\tag\task\ScoreTagTask;
use Inaayat\ScoreTag\utils\MainPluginTrait;
use pocketmine\Player;

use pocketmine\utils\TextFormat;
use function count;
use function str_ireplace;
use function str_replace;
use function strlen;

class TagFactory {

	use MainPluginTrait;

	/** @var string */
	public const COLOR_CHARACTER = "&";

	/** @var Tag[] */
	private $tags = [];

	/** @var string */
	private $tag;

	/** @var int */
	private $updatePeriod;

	/** @var ScoreTagTask */
	private $task;

	/**
	 * TagFactory constructor.
	 * @param Main $plugin
	 */
	public function __construct(Main $plugin) {
		$this->setPlugin($plugin);
		$this->tag = $plugin->getConfig()->get("tag", "");
		$this->updatePeriod = $plugin->getConfig()->get("update-period", 10);
		$this->registerTags();
	}

	public function enable(): void {
		/*
		 * Only start task if the tag string length > 0
		 */
		if(strlen($this->getTagString()) > 0) {
			$this->task = new ScoreTagTask($this->getPlugin(), $this);
			$this->task->start();
		}
	}

	/**
	 * TODO: Implement reloading of config for live updating of the tags
	 */
	public function reload(): void {
		$this->getPlugin()->getConfig()->reload();
		$this->tag = $this->getPlugin()->getConfig()->get("tag", "");
		$this->updatePeriod = $this->getPlugin()->getConfig()->get("update-period", 10);
		$this->getTask()->restart();
	}

	/**
	 * @return string
	 */
	public function getTagString(): string {
		return $this->tag;
	}

	/**
	 * @return int
	 */
	public function getUpdatePeriod(): int {
		return $this->updatePeriod;
	}

	/**
	 * @return ScoreTagTask
	 */
	public function getTask(): ScoreTagTask {
		return $this->task;
	}


	public function registerTags(): void {
		$this->registerGroup(new CombatLoggerTagGroup($this->getPlugin()));
		$this->registerGroup(new DefaultTagGroup($this->getPlugin()));
		$this->registerGroup(new EconomyAPITagGroup($this->getPlugin()));
		$this->registerGroup(new FactionsProTagGroup($this->getPlugin()));
		$this->registerGroup(new PiggyFactionsTagGroup($this->getPlugin()));
		$this->registerGroup(new PurePermsTagGroup($this->getPlugin()));
		$this->registerGroup(new RedSkyBlockTagGroup($this->getPlugin()));
		$this->registerGroup(new SeeDeviceTagGroup($this->getPlugin()));
		$count = count($this->getTags());
		$this->getPlugin()->getLogger()->info(TextFormat::YELLOW . "Successfully loaded $count tags!");
	}

	/**
	 * @param Tag $tag
	 */
	public function register(Tag $tag): void {
		$this->tags[$tag->getName()] = $tag;
	}

	public function registerGroup(TagGroup $group): void {
		$tags = $group->load($this);
		if(count($tags) > 0) {
			foreach($tags as $tag) $this->register($tag);
		}
	}

	/**
	 * @return Tag[]
	 */
	public function getTags(): array {
		return $this->tags;
	}

	/**
	 * @param string $input
	 */
	public function replaceVisuals(string &$input): void {
		$input = str_replace(self::COLOR_CHARACTER, TextFormat::ESCAPE, $input);
		$input = str_ireplace("{line}", "\n", $input);
	}

	/**
	 * @param Player $player
	 * @return string
	 */
	public function replace(Player $player): string {
		if(strlen($this->tag) <= 0) {
			return "";
		}
		$output = $this->getTagString();
		foreach($this->getTags() as $tag) {
			try {
				$tag->replace($player, $output);
			} catch (Exception $exception) {
				//just in case a malformed tag callback happens
				$this->getPlugin()->getLogger()->logException($exception);
			}
		}
		$this->replaceVisuals($output);
		return $output;
	}

	public function update(): void {
		foreach($this->getPlugin()->getServer()->getOnlinePlayers() as $player) {
			$player->setScoreTag($this->replace($player));
		}
	}

}