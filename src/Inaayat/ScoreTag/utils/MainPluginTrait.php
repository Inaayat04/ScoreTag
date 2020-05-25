<?php

declare(strict_types=1);

namespace Inaayat\ScoreTag\utils;


use Inaayat\ScoreTag\Main;

trait MainPluginTrait {

	/** @var Main */
	private $plugin;

	/**
	 * @return Main
	 */
	public function getPlugin(): Main {
		return $this->plugin;
	}

	/**
	 * @param Main $plugin
	 */
	public function setPlugin(Main $plugin): void {
		$this->plugin = $plugin;
	}
}