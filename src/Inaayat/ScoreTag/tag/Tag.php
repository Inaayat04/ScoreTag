<?php

declare(strict_types=1);

namespace Inaayat\ScoreTag\tag;

use pocketmine\Player;
use function str_ireplace;

class Tag {

	/** @var string */
	private $name;

	/** @var callable */
	protected $replaceCallback;

	/**
	 * Tag constructor.
	 * @param string $name
	 * @param $replaceCallback
	 */
	public function __construct(string $name, callable $replaceCallback) {
		$this->name = $name;
		$this->replaceCallback = $replaceCallback;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	/**
	 * @param Player $player
	 * @param string $input
	 */
	public function replace(Player $player, string &$input): void {
		$output = ($this->replaceCallback)($player);
		if($output === null) return;
		$input = str_ireplace("{". $this->getName() . "}", $output, $input);
	}

}