<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 6/26/2017
 * Time: 7:52 PM
 */

namespace PocketEssential\PocketPerms\PPListener;


use PocketEssential\PocketPerms\Permission\AddPermission;
use PocketEssential\PocketPerms\PocketPerms;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;

class JoinListener implements Listener {

	public $plugin;

	public function __construct(PocketPerms $plugin){

		$this->plugin = $plugin;
	}


	public function onJoin(PlayerPreLoginEvent $event){

		$player = $event->getPlayer();

		if($this->plugin->getPlayerGroup($player) == null){
			$this->plugin->setGroup($player, $this->plugin->group->get("default-group"));
			$this->plugin->getLogger()->info($player->getName() . " does not have a valid group set. Setting the default one.");
		}else{
			$player->setNameTag($this->plugin->getNameTagFormat($player));
		}

		if($this->plugin->getPlayerGroup($player) != null){
			$aP = new AddPermission($this->plugin, $player);
			$aP->int();
		}
	}
}