<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 8/5/2017
 * Time: 1:40 AM
 */

namespace PocketEssential\PocketPerms\Permission;


use PocketEssential\PocketPerms\PocketPerms;
use pocketmine\Server;

class RemovePermission {

	/** @var PocketPerms */
	public $plugin;
	public $group;
	public $permission;

	/**
	 * @param PocketPerms $plugin
	 * @param             $group
	 * @param             $permission
	 */
	public function __construct(PocketPerms $plugin, $group, $permission){
		$this->plugin = $plugin;
		$this->group = $group;
		$this->permission = $permission;
	}

	public function int(){
		$group = $this->plugin->group->get("Groups");
		$permissions = $group[$this->group]['Permissions'];

		foreach($permissions as $key => $value){
			if($value == $this->permission){
				unset($permissions[$key]);
			}
		}
		$this->plugin->group->set($group['Permissions'], $permissions);
	}

	/**
	 * @return Server
	 */
	public function getServer() : Server{
		return $this->plugin->getServer();
	}
}