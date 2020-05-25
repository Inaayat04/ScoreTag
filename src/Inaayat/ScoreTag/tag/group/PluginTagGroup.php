<?php

declare(strict_types=1);

namespace Inaayat\ScoreTag\tag\group;


use Inaayat\ScoreTag\Main;
use Inaayat\ScoreTag\tag\ExternalPluginTag;
use Inaayat\ScoreTag\tag\Tag;
use Inaayat\ScoreTag\tag\TagFactory;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

abstract class PluginTagGroup extends TagGroup {

	/** @var string */
	private $pluginName;

	/** @var Plugin|null */
	private $externalPlugin;

	/**
	 * PluginTagGroup constructor.
	 * @param Main $plugin
	 * @param string $pluginName
	 */
	public function __construct(Main $plugin, string $pluginName) {
		parent::__construct($plugin);
		$this->pluginName = $pluginName;
		$this->externalPlugin = $plugin->getServer()->getPluginManager()->getPlugin($pluginName);
	}

	/**
	 * @return string
	 */
	public function getPluginName(): string {
		return $this->pluginName;
	}

	/**
	 * @return Plugin|null
	 */
	public function getExternalPlugin(): ?Plugin {
		return $this->externalPlugin;
	}

	/**
	 * @return bool
	 */
	public function check(): bool {
		return $this->getExternalPlugin() instanceof Plugin;
	}

	/**
	 * @param TagFactory $factory
	 * @return Tag[]
	 */
	public function load(TagFactory $factory): array {
		if($this->check()) {
			$this->getPlugin()->getLogger()->info(TextFormat::GREEN . "{$this->getPluginName()} support has been enabled!");
			return $this->register($factory);
		}
		return [];
	}

	/**
	 * @param TagFactory $factory
	 * @return ExternalPluginTag[]
	 */
	abstract public function register(TagFactory $factory): array;
}