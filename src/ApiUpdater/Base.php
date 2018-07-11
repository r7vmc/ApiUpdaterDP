<?php
declare(strict_types=1);

namespace ApiUpdater;

use pocketmine\command\Command;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as C;

class Base extends PluginBase{

    public $PREFIX = C::BLUE . "[" . C::GOLD . "ApiUpdater" . C::BLUE . "]" . C::WHITE . " ";

    /** @var FolderUpdating */
    private $FolderUpdater;

    /** @var PharUpdating */
    private $PharUpdater;
    
    /** @var Command[] $commands */
    public $commands = [];

    public function onEnable(){
    $this->FolderUpdater = new FolderUpdating($this);
    $this->PharUpdater = new PharUpdating($this);
    $this->getServer()->getCommandMap()->register("ApiUpdater", $this->commands[] = new ApiUpdaterCommand($this));
    $this->getLogger()->info($this->PREFIX . "is Enabled!");
    }

    public function getFolderUpdating(){
        return $this->FolderUpdater;
    }

    public function getPharUpdating(){
        return $this->PharUpdater;
    }

}