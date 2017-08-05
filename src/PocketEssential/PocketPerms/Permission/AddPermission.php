<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 8/5/2017
 * Time: 1:23 AM
 */

namespace PocketEssential\PocketPerms\Permission;


use PocketEssential\PocketPerms\PocketPerms;
use pocketmine\Player;
use pocketmine\Server;

class AddPermission {

	public $plugin;
	public $player;

	public function __construct(PocketPerms $plugin, Player $player){
		$this->player = $player;
		$this->plugin = $plugin;
	}

	public function int(){

		$player = $this->player;

		if($this->plugin instanceof PocketPerms){
			$permissions = $this->plugin->getPlayerGroup($this->plugin->getPlayerGroup($player))['Permissions'];

			if(count($permissions) == 0) return;

			foreach($permissions as $p){
				$player->addAttachment($this->plugin, $p, true);
			}

		}
	}

	public function getServer() : Server{

		return $this->plugin->getServer();
	}
}