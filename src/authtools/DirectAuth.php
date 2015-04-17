<?php

namespace authtools;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use SimpleAuth\event\PlayerAuthenticateEvent;
use SimpleAuth\event\PlayerDeauthenticateEvent;

class DirectAuth implements Listener{
	/** @var AuthTools */
	private $authTools;
	/** @var DirectAuthSession[] */
	private $sessions = [];
	public function __construct(AuthTools $authTools){
		$authTools->getServer()->getPluginManager()->registerEvents($this, $this->authTools = $authTools);
	}
	/**
	 * @param PlayerJoinEvent $event
	 * @priority MONITOR
	 * @ignoreCancelled true
	 */
	public function onJoin(PlayerJoinEvent $event){
		$this->sessions[$event->getPlayer()->getId()] = new DirectAuthSession($this, $event->getPlayer());
	}
	public function onDeauthenticate(PlayerDeauthenticateEvent $event){
		$this->sessions[$event->getPlayer()->getId()] = new DirectAuthSession($this, $event->getPlayer());
	}
	/**
	 * @param PlayerQuitEvent $event
	 */
	public function onQuit(PlayerQuitEvent $event){
		if(isset($this->sessions[$id = $event->getPlayer()->getId()])){
			unset($this->sessions[$id]);
		}
	}
	public function onAuthenticate(PlayerAuthenticateEvent $event){
		if(isset($this->sessions[$id = $event->getPlayer()->getId()])){
			unset($this->sessions[$id]);
		}
	}
	/**
	 * @param PlayerCommandPreprocessEvent $ev
	 * @priority LOW
	 * @ignoreCancelled true
	 */
	public function onPreCmd(PlayerCommandPreprocessEvent $ev){
		$ses = $this->getDirectAuthSession($ev->getPlayer());
		if($ses instanceof DirectAuthSession){
			// TODO DirectAuth!
		}else{
			// TODO check password match
		}
	}
	/**
	 * @param Player $player
	 * @return DirectAuthSession|null
	 */
	public function getDirectAuthSession(Player $player){
		return isset($this->sessions[$id = $player->getId()]) ? $this->sessions[$id] : null;
	}
}
