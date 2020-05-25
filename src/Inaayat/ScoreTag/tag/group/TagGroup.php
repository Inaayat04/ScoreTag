<?php

declare(strict_types=1);

namespace Inaayat\ScoreTag\tag\group;


use Inaayat\ScoreTag\Main;
use Inaayat\ScoreTag\tag\Tag;
use Inaayat\ScoreTag\tag\TagFactory;
use Inaayat\ScoreTag\utils\MainPluginTrait;

abstract class TagGroup {

	use MainPluginTrait;

	/**
	 * TagGroup constructor.
	 * @param Main $plugin
	 */
	public function __construct(Main $plugin) {
		$this->setPlugin($plugin);
	}

	/**
	 * @param TagFactory $factory
	 * @return Tag[]
	 */
	abstract public function load(TagFactory $factory): array;

}