<?php
declare(strict_types=1);

namespace ApiUpdater\Commands;

use ApiUpdater\Base;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;

/**
 * Class ApiUpdaterCommand
 * @package ApiUpdaterCommand
 */
class ApiUpdaterCommand extends Command implements PluginIdentifiableCommand {

    /** @var Base $plugin */
    protected $main;

    /**
     * ApiUpdaterCommand constructor.
     * @param Base $plugin
     */
    public function __construct(Base $main) {
        $this->main = $main;
        parent::__construct("apiupdater", "apiudpater commands", \null, ["au"]);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return mixed|void
     */
    public function execute(CommandSender $sender, string $label, array $args) {
       if(!$sender->hasPermission("apiupdater.use")) {
            $sender->sendMessage($this->main->PREFIX . "§cYou don't have the permission to use the command!");
            return;
        }else{
           if(!isset($args[0])){
              $sender->sendMessage($this->main->PREFIX . "usage: §c/au help");
              
              }else{
                 switch($args[0]){
                    case 'folder':
                    $this->main->getFolderUpdating()->update($sender);
                    break;
                    case 'phar':
                    $this->main->getPharUpdating()->update($sender);
                    break;
                    case 'info':
                        $ver = $this->main->getServer()->getPluginManager()->getPlugin("ApiUpdaterDP")->getDescription()->getVersion();
                        $sender->sendMessage(
                        $this->main->PREFIX . "\n"
                    ."§aDeveloper: §fR7vmc\n".
                    "§aVersion: §f". $ver ."\n".
                    "§ainstagram: §f@awlw\n".
                        "§aCredits: §f@khavmc & @ky75"
                    );
                    break;
                    case "help":
                    $sender->sendMessage(
                        $this->main->PREFIX . "\n"
                    ."§a/au folder §f- To update DevTools plugins\n".
                    "§a/au phar §f- To update .phar plugins\n".
                    "§a/au info §f- More information about this plugin"
                    );
                    break;
                  }
              }
          }
    }

    /**
     * @return Base|Plugin $apiupdater
     */
    public function getPlugin(): Plugin {
        return $this->getPlugin();
    }

}