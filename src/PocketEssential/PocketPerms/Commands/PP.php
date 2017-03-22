<?php
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


class PP extends Base
{

    private $plugin;

    public function __construct(PocketPerms $plugin) {
        $this->plugin = $plugin;
        parent::__construct($plugin, "pp", "PocketPerm", "/pp", ["p","pocketperm"]);
    }


    public function execute(CommandSender $sender, $commandLabel, array $args)
    {

        if ($sender instanceof Player) {

            if($args[0] == null){
                $sender->sendMessage("");
            }
            switch ($args[0]){

                /*
                 *  Set a player group
                 */
                case "setgroup":
                case "setgrp":
                    if($args[1] == null){
                        $sender->sendMessage("/pp setgroup [player name] [group name]");
                    } else {
                        if ($args[2] == null) {
                            $sender->sendMessage("/pp setgroup [player name] [group name]");
                        } else {
                            if ($this->getPlugin()->getServer()->getPlayer($args[1]) == null) {
                                $sender->sendMessage("[PP] Error while trying to set " . $args[1] . " group. Is he/she online?");
                            } else {
                                if ($this->getPlugin()->getGroup($args[2]) == false) {
                                    $sender->sendMessage("[PP] That group does not exist");
                                } else {
                                    $this->getPlugin()->setGroup($this->getPlugin()->getServer()->getPlayer($args[1]), $args[2]);
                                    $sender->sendMessage("[PP] " . $args[1] . " group has successfully been set to " . $args[2]);
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
                    if($args[1] == null){
                        $sender->sendMessage("/pp addperm [group name] [permission]");
                    } else {
                        if ($args[2] == null) {
                            $sender->sendMessage("/pp setgroup [group name] [permission]");
                        } else {
                            if ($this->getPlugin()->getGroup($args[1]) == false) {
                                $sender->sendMessage("[PP] That group does not exist");
                            } else {
                                $this->getPlugin()->addGroupPermission($args[1], $args[2]);
                                $sender->sendMessage("[PP] Added permission: ".$args[2]." to group " . $args[1]." successfully");
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
                if($args[1] == null){
                    $sender->sendMessage("/pp delgroup [group name]");
                } else {
                        if ($this->getPlugin()->getGroup($args[1]) == false) {
                            $sender->sendMessage("[PP] That group does not exist");
                        } else {
                            $this->getPlugin()->deleteGroup($args[1]);
                            $sender->sendMessage("[PP] Group ". $args[1] . " has successfully been removed");
                        }
                    }
                break;

            }
        } else {
            $sender->sendMessage(PocketPerms::RUN_FROM_CONSOLE);
        }
    }
    public function getPlugin(){

        return $this->plugin;
    }

    public function getServer(){

        return $this->getPlugin()->getServer();
    }
}