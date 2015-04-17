<?php

namespace authtools;

use pocketmine\Player;

class DirectAuthSession{
	/** @var DirectAuth */
	private $directAuth;
	/** @var Player */
	private $player;
	public function __construct(DirectAuth $directAuth, Player $player){
		$this->directAuth = $directAuth;
		$this->player = $player;
	}
	/**
	 * @return DirectAuth
	 */
	public function getDirectAuth(){
		return $this->directAuth;
	}
	/**
	 * @return Player
	 */
	public function getPlayer(){
		return $this->player;
	}
}
