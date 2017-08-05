<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 3/22/2017
 * Time: 12:54 AM
 */

namespace PocketEssential\PocketPerms\Commands;

use PocketEssential\PocketPerms\PocketPerms;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;

class Base extends Command implements PluginIdentifiableCommand {

	private $plugin;

	public function __construct(PocketPerms $plugin, $name, $description, $usageMessage, $aliases){
		parent::__construct($name, $description, $usageMessage, $aliases);
		$this->plugin = $plugin;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if($sender->hasPermission($this->getPermission())){
			$result = $this->onExecute($sender, $args);
			if(is_string($result)){
				$sender->sendMessage($result);
			}
			return true;
		}else{
			$sender->sendMessage(PocketPerms::PERMISSION);
		}
		return false;
	}

	public function onExecute(CommandSender $sender, array $args){

	}

	public function getPlugin() : Plugin{

		return $this->plugin;
	}
}