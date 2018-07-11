<?php
declare(strict_types=1);

namespace ApiUpdater;

use pocketmine\command\CommandSender;
use Symfony\Component\Yaml\Yaml;

/**
 * Class FolderUpdating
 * @package FolderUpdating
 */
class FolderUpdating{

    /**
     * FolderUpdating constructor.
     * @param Base $plugin
     */
    public function __construct(Base $main){
        $this->main = $main;
    }

    /**
     * @param CommandSender $sender
     *
     * @return string|null
     */
    public function update(CommandSender $sender)
    {
        $sender->sendMessage($this->main->PREFIX . "Keep in Mind that this plugin will only change API number, and it will not apply new API changes");
        $sender->sendMessage($this->main->PREFIX . "Make sure you have backup of all your data, i'm not responsible for Damaged plugins, this will won't likely happen but take your reserves");
        sleep(10);
        $sender->sendMessage($this->main->PREFIX . "Updating....");
        $pluginsyml = $this->glob_recursive($this->main->getServer()->getPluginPath() . "plugin.yml");
        $ymlnum = count($pluginsyml);
        for ($i = 0; $i < $ymlnum; $i++) {//Updating Loop
            $plymlcontents = file_get_contents($pluginsyml[$i]);
            $yaml = Yaml::parse($plymlcontents);
            $apiy = $yaml['api'];
            $apis = $this->main->getServer()->getApiVersion();
            $plname = $yaml['name'];
                if (is_string($apiy) == true) {//Checking if api is string
                    $apiy = explode(" ", $apiy);//Converting to Array
                }
                    if (!in_array($apis, $apiy)) {//Checking if Plugin Needs Updating
                    array_push($apiy, $apis);//Adding the server api to the api array
                        $yaml['api'] = $apiy;
                    var_dump($apiy);
                        $new_yml = Yaml::dump($yaml);
                    file_put_contents($pluginsyml[$i], $new_yml);
                    $sender->sendMessage($this->main->PREFIX . "Updating " . $plname . "...");
                    if ($i == $ymlnum - 1) {//Checking if Updating is done
                    $sender->sendMessage($this->main->PREFIX . "Updating Done!");
                    $sender->sendMessage($this->main->PREFIX . "Restart the server to take effect");
                }
            }else{
                    $sender->sendMessage($this->main->PREFIX . $plname . " is using the server api.");
                    }
        }
        $sender->sendMessage($this->main->PREFIX . "All Plugins have been updated!");
        return true;
    }
    /**
     * @param string $sender
     *
     * @param int $flags
     *
     */
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
