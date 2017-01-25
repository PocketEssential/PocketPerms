<?php
#
#  _____           _        _   _____
# |  __ \         | |      | | |  __ \
# | |__) |__   ___| | _____| |_| |__) |__ _ __ _ __ ___  ___
# |  ___/ _ \ / __| |/ / _ \ __|  ___/ _ \ '__| '_ ` _ \/ __|
# | |  | (_) | (__|   <  __/ |_| |  |  __/ |  | | | | | \__ \
# |_|   \___/ \___|_|\_\___|\__|_|   \___|_|  |_| |_| |_|___/
#
# Made by PocketEssential Copyright 2016 ©
#
# This is a public software, you cannot redistribute it a and/or modify any way
# unless otherwise given permission to do so.
#
# Author: The PocketEssential Team
# Link: https://github.com/PocketEssential
#
#|------------------------------------------------- PocketPerms -------------------------------------------------|
#| - If you want to suggest/contribute something, read our contributing guidelines on our Github Repo (Link Below)|
#| - If you find an issue, please report it at https://github.com/PocketEssential/PocketPerms/issues             |
#|----------------------------------------------------------------------------------------------------------------|

namespace PocketEssential\PocketPerms;


use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;

class PocketPerms extends PluginBase implements Listener
{

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents(new Events\Events($this), $this);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->chatFormat = new Config($this->getDataFolder() . "chat.yml", Config::YAML);
        $this->groups = new Config($this->getDataFolder() . "groups.yml", Config.YAML);

       // $this->getLogger()->info($this->PocketPerms());
    }

    /*
     * API
     */

    public function getGroup($player){
      if($this->getPlayerConfig($player)->get("group") === ""){
            return false;
        } else {
           return $this->getPlayerConfig($player)->get("group");
      }
    }
    
    public function getPlayerConfig(Player $player){
        $cfg = new Config($this->getDataFolder() . "players/" . strtolower($player->getName()) . ".yml"), Config::YAML);
        return $cfg;
    }
    
    public function registerFirstJoin(Player $player){
        $cfg = new Config($this->getDataFolder() . "players/" . strtolower($player->getName()) . ".yml"));
        $cfg->set("group", $this->groups->get("default-group"));
    	$cfg->save();
    }
    
    public function isFirstJoin(Player $player){
        return file_exists($this->getDataFolder() . "players/" . strtolower($player->getName()). ".yml");
    }

    public function setGroup($player, $group){
        $this->getPlayerConfig($player)->set("group", $group);
    }

    public function getChatFormat($player, $message){

         $chats = $this->chatFormat;
         $group = $this->getGroup($player);
         $format = $group->getNested("$group"."Chat");

        $chatFormat = str_replace("{player_name}",$player->getName(),$format);
        $tw = str_replace("{message}",$message,$chatFormat);
        $tw3 = str_replace("{player_nametag}",$player->getNameTag(),$tw);
        $final = str_replace("{color}",'%',$tw3);

         return $final;

    }
}
