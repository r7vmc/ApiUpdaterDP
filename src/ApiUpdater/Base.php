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

    /** @var NewAPIChanges */
    private $NewAPIChanges;

    /** @var Command[] $commands */
    public $commands = [];

    public function onEnable(){
    $this->getServer()->getCommandMap()->register("ApiUpdater", $this->commands[] = new ApiUpdaterCommand($this));
    $this->getLogger()->info($this->PREFIX . "is Enabled!");
    $this->FolderUpdater = new FolderUpdating($this);
    $this->PharUpdater = new PharUpdating($this);
    $this->NewAPIChanges = new NewAPIChanges($this);
    $this->saveDefaultConfig();
    $this->reloadConfig();
    }
	
	public function onLoad(){
		require $this->getFile() . "vendor/autoload.php";
	}

    public function getFolderUpdating(){
        return $this->FolderUpdater;
    }

    public function getPharUpdating(){
        return $this->PharUpdater;
    }

    public function NewAPIChanges(){
        return $this->NewAPIChanges;
    }
	public function onDisable(){
		$this->getLogger()->info($this->PREFIX . "is Disabled!");
	}
    function glob_recursive($pattern, $flags = 0)//Getting plugin.yml files
    {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir)
        {
            $files = array_merge($files, $this->glob_recursive($dir.'/'.basename($pattern), $flags));
        }
        return $files;
    }


}