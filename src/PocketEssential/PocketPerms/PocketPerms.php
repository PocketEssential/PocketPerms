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



use onebone\economyapi\EconomyAPI;
use EconomyPlus\EconomyPlus;
use PocketEssential\PocketPerms\Commands\PP;
use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;

class PocketPerms extends PluginBase implements Listener
{

    public $groups;
    public $chatFormat;
    public $data;
    public $main;
    const PERMISSION = ".";
    const RUN_FROM_CONSOLE = "Please run this command from console";

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents(new PPListener\ChatListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        $this->main = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->chatFormat = new Config($this->getDataFolder() . "chat.yml", Config::YAML);
        $this->groups = new Config($this->getDataFolder() . "groups.yml", Config::YAML);
        $this->data = new Config($this->getDataFolder() . "data.yml", Config::YAML);
        $this->getServer()->getCommandMap()->registerAll('NetworkSystem', [
            new PP($this)
        ]);



    }

    public function onDisable()
    {
        $this->main->save();
        $this->chatFormat->save();
        $this->groups->save();
        $this->data->save();
    }


    /*
     *  Gets a player group
     */
    public function getPlayerGroup($player)
    {

        if ($this->data instanceof Config && $player instanceof Player) {
            $group = $this->data->get($player->getName());

            if ($group == null) {
                return null;
            } else {
                return $group;
            }
        }
    }

    /*
     * Sets a player group
     */
    public function setGroup($player, $group)
    {

        if ($this->data instanceof Config && $player instanceof Player) {
            $this->data->set($player->getName(), $group);
            $this->data->save();
        } else {
            return false;
        }
    }

    /*
     * Get a group chat format
     */
    public function getChatFormat($player, $message)
    {

        if ($this->chatFormat instanceof Config && $player instanceof Player) {

            $group = $this->getPlayerGroup($player);
            $format = $this->chatFormat->get($group);
            $format = $format['Chat'];
            $format = str_replace("{player_nametag}", $player->getNameTag(), $format);
            $format = str_replace("{player_name}", $player->getName(), $format);
            $format = str_replace("{color}", "§", $format);
            $format = str_replace("{Factions_Pro}", $this->getServer()->getPluginManager()->getPlugin("FactionsPro")->getPlayerFaction($player), $format);
            $format = str_replace("{EconomyPlus_Money}", EconomyPlus::getInstance()->getMoney($player), $format);
            $format = str_replace("{EconomyAPI_Money}", EconomyAPI::getInstance()->myMoney($player), $format);
            $format = str_replace("{Online}", count($this->getServer()->getOnlinePlayers()), $format);
            $format = str_replace("{Max}", $this->getServer()->getMaxPlayers(), $format);
            $format = str_replace("{Level}", $player->getLevel()->getName(), $format);
            $format = str_replace("{Health}", $player->getHealth(), $format);
            return $format;
        } else {
            return false;
        }
    }

    /*
     * Get a group name tag format
     */
    public function getNameTagFormat($player)
    {

        if ($this->chatFormat instanceof Config) {
            if ($player instanceof Player) {
                $group = $this->getPlayerGroup($player);
                $format = $this->chatFormat->get($group);
                $format = $format['Nametag'];
                $format = str_replace("{player_nametag}", $player->getNameTag(), $format);
                $format = str_replace("{player_name}", $player->getName(), $format);
                $format = str_replace("{color}", "§", $format);
                $format = str_replace("{Factions_Pro}", $this->getServer()->getPluginManager()->getPlugin("FactionsPro")->getPlayerFaction($player), $format);
                $format = str_replace("{EconomyPlus_Money}", EconomyPlus::getInstance()->getMoney($player), $format);
                $format = str_replace("{EconomyAPI_Money}", EconomyAPI::getInstance()->myMoney($player), $format);
                $format = str_replace("{Online}", count($this->getServer()->getOnlinePlayers()), $format);
                $format = str_replace("{Max}", $this->getServer()->getMaxPlayers(), $format);
                $format = str_replace("{Level}", $player->getLevel()->getName(), $format);
                $format = str_replace("{Health}", $player->getHealth(), $format);
                return $format;

                } else {

                    return false;
                }
            }
        }

        /*
         * Checks if a group  exist
         */
    public function getGroup($group)
    {

        $g = $this->groups->get("Groups");

        if ($g[$group] != null) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *  Register permission to player ($permissions must
     *  be instance of a list of permissions to add)
     */
    public function addPermission($player, $pp, $permissions = null)
    {

        if ($player instanceof Player) {
            $player->addAttachment($this, $pp, true);
        } else {
            return false;
        }
    }

    /*
     * Add a permission to a group
     */
    public function addGroupPermission($group, $pp)
    {

        if ($this->groups instanceof Config) {
            $permission = $this->groups->get("Groups");
            $permissions = $permission[$group]['Permissions'];
            $updated = $permissions;
            $updated[] = "$pp";
            $da = $this->groups->get("Groups");
            $da = $da[$group];

            $v = $this->groups->get("Groups");

        } else {
            return false;
        }
    }

    /*
     * Delete a group from the list of groups
     */
    public function deleteGroup($group)
    {

        if ($this->groups instanceof Config) {
            $group = $this->groups->get("Groups");

            $remove = array($group);
            $result = array_diff($group, $remove);
            $this->groups->set("Groups", $result);

        } else {

            return false;
        }
    }
}