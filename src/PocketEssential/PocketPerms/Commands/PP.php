<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 3/22/2017
 * Time: 12:51 AM
 */

namespace PocketEssential\PocketPerms\Commands;

use PocketEssential\PocketPerms\PocketPerms;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;


class PP extends Base {

	/** @var PocketPerms */
	private $plugin;

	/**
	 * @param PocketPerms $plugin
	 */
	public function __construct(PocketPerms $plugin){
		$this->plugin = $plugin;
		parent::__construct($plugin, "pp", "PocketPerm", "/pp", ["p", "pocketperm"]);
	}


	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{

		if($sender instanceof Player){

			if($args[0] == null){
				$help = [
					'-- PocketPerms --',
					'',
					'For a list of commands',
					'Please execute /pp help',
				];
				foreach($help as $l){
					$sender->sendMessage($l);
				}
			}
			switch($args[0]){

				/*
				 *  Set a player group
				 */
				case "setgroup":
				case "setgrp":
					if(!isset($args[1])){
						$sender->sendMessage(TextFormat::YELLOW . "Usage: /pp setgroup <Player name> <Group name>");
					}else{
						if(!isset($args[2])){
							$sender->sendMessage(TextFormat::YELLOW . "Usage: /pp setgroup <Player name> <Group name>");
						}else{
							if($this->getPlugin()->getServer()->getPlayer($args[1]) == null){
								$sender->sendMessage(TextFormat::BLUE . "[PP]" . TextFormat::RED . " Error while trying to set " . $args[1] . " group, is the player online?");
							}else{
								if($this->getPlugin()->getGroup($args[2]) == false){
									$sender->sendMessage(TextFormat::BLUE . "[PP]" . TextFormat::RED . " That group doesn't exist!");
								}else{
									$this->getPlugin()->setGroup($this->getPlugin()->getServer()->getPlayer($args[1]), $args[2]);
									$sender->sendMessage(TextFormat::BLUE . "[PP] " . $args[1] . " group has successfully been set to " . $args[2]);
								}
							}
						}
					}
					break;

				/*
				 *  Add a permission to a group
				 */
				case "addperm":
				case "addp":
				case "addpermission":
					if(!isset($args[1])){
						$sender->sendMessage(TextFormat::YELLOW . "Usage: /pp addperm <Group name> <Permission>");
					}else{
						if(!isset($args[2])){
							$sender->sendMessage(TextFormat::YELLOW . "Usage: /pp addperm <Group name> <Permission>");
						}else{
							if($this->getPlugin()->getGroup($args[1]) == false){
								$sender->sendMessage(TextFormat::BLUE . "[PP]" . TextFormat::RED . " That group doesn't exist!");
							}else{
								$this->getPlugin()->addGroupPermission($args[1], $args[2]);
								$sender->sendMessage(TextFormat::BLUE . "[PP]" . TextFormat::GREEN . " Added permission: " . $args[2] . " to group " . $args[1] . " successfully!");
							}
						}
					}
					break;

				/*
				 *  Remove group
				 */
				case "removegroup":
				case "rmgroup":
				case "delgroup":
				case "delgrp":
					if(!isset($args[1])){
						$sender->sendMessage(TextFormat::YELLOW . "Usage: /pp delgroup <Group name>");
					}else{
						if($this->getPlugin()->getGroup($args[1]) == false){
							$sender->sendMessage(TextFormat::BLUE . "[PP]" . TextFormat::RED . " That group doesn't exist!");
						}else{
							$this->getPlugin()->deleteGroup($args[1]);
							$sender->sendMessage(TextFormat::BLUE . "[PP]" . TextFormat::GREEN . " Group " . $args[1] . " has successfully been removed");
						}
					}
					break;

				/*
				 * List groups
				 */

				case "listgroup":
				case "listgroups":
				case "listgrp":
					$sender->sendMessage(TextFormat::BLUE . "[PP]" . TextFormat::GREEN . "List of groups:");

					$groups = $this->getPlugin()->group->get("Groups");
					foreach($groups as $g){
						$sender->sendMessage("- " . $g);
					}
					break;

				/*
				 *  Set format
				 */
				case "setformat":
				case "setfmt":
					if(!isset($args[1])){
						$sender->sendMessage(TextFormat::YELLOW . "Usage: /pp setformat <Group name> <Format>");
					}else{
						if(!isset($args[2])){
							$sender->sendMessage(TextFormat::YELLOW . "Usage: /pp setformat <Group name> <Format>");
						}else{
							if($this->getPlugin()->getGroup($args[1]) == false){
								$sender->sendMessage(TextFormat::BLUE . "[PP]" . TextFormat::RED . " That group doesn't exist!");
							}else{
								$this->plugin->setGroupFormat($args[1], implode(" ", $args));
								$sender->sendMessage(TextFormat::BLUE . "[PP]" . TextFormat::GREEN . " Successfully set group " . $args[1] . " format to " . $args[2]);
							}
						}
					}
					break;

				/*
				 *  Add a permission to a group
				 */
				case "delperm":
				case "delp":
				case "delpermission":
					if(!isset($args[1])){
						$sender->sendMessage(TextFormat::YELLOW . "Usage: /pp delperm <Group name> <Permission>");
					}else{
						if(!isset($args[2])){
							$sender->sendMessage(TextFormat::YELLOW . "Usage: /pp delperm <Group name> <Permission>");
						}else{
							if($this->getPlugin()->getGroup($args[1]) == false){
								$sender->sendMessage(TextFormat::BLUE . "[PP]" . TextFormat::RED . " That group doesn't exist!");
							}else{
								$this->getPlugin()->deleteGroupPermission($args[1], $args[2]);
								$sender->sendMessage(TextFormat::BLUE . "[PP]" . TextFormat::GREEN . " deleted permission: " . $args[2] . " from group " . $args[1] . " successfully!");
							}
						}
					}
					break;
			}
		}else{
			$sender->sendMessage(PocketPerms::RUN_FROM_CONSOLE);
		}

		return true;
	}
}