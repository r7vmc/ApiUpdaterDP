<?php
declare(strict_types=1);

namespace ApiUpdater;

use pocketmine\command\CommandSender;
use Symfony\Component\Yaml\Yaml;

/**
 * Class PharUpdating
 * @package PharUpdating
 */
class PharUpdating{

    /**
     * PharUpdating constructor.
     * @param Base $plugin
     */
    public function __construct(Base $main)
    {
        $this->main = $main;
    }

    /**
     * @param CommandSender $sender
     *
     * @return string|null
     */
    public function update(CommandSender $sender)
    {
        if ($this->main->getConfig()->get("show_warning") == true) {
            $sender->sendMessage($this->main->PREFIX . "Make sure you have backup of all your data, i'm not responsible for Damaged plugins, this will won't likely happen but take your reserves");
            $sender->sendMessage($this->main->PREFIX . "You can disable this message by setting 'show_warning' to false in the config");
            sleep(10);
        }
        $sender->sendMessage($this->main->PREFIX . "Updating....");
        $pluginsfolder = $this->main->getServer()->getPluginPath();
        $plugins = glob("$pluginsfolder*.phar");
        $plnum = count($plugins);
        for ($i = 0; $i < $plnum; $i++) {//Updating
            //Opening PHAR
            $phar = new \Phar($plugins[$i]);
            $phar->startBuffering();
            $plymlpath = 'phar://' . $phar->getPath() . '/plugin.yml';
            $plymlcontents = file_get_contents($plymlpath);
            $yaml = Yaml::parse($plymlcontents);
            //applying API Changes: Start
            if($yaml['name'] !== "ApiUpdaterDP"){
            foreach (new \RecursiveIteratorIterator($phar) as $files) {
                $files = $files->getPathname();
                if (preg_match("/\.(php)*$/i", $files, $matches)) {
                    $content = file_get_contents($files);
                    $newcontent = $this->main->NewAPIChanges()->apply($content);
                    file_put_contents($files, $newcontent);
                }
            }
            }
            //applying API Changes: End
            $apiy = $yaml['api'];
            $apis = $this->main->getServer()->getApiVersion();
            $plname = $yaml['name'];
            if (is_string($apiy) == true) {//Checking if api is string
                $apiy = explode(" ", $apiy);//Converting to Array
            }
            if (!in_array($apis, $apiy)) {//Checking if Plugin Needs Updating
                array_push($apiy, $apis);//Adding the server api to the api array
                $yaml['api'] = $apiy;
                $new_yml = Yaml::dump($yaml);
                $phar->addFromString("plugin.yml", $new_yml);
                $phar->stopBuffering();
                $sender->sendMessage($this->main->PREFIX . "Updating " . $plname . "...");

                if ($i = $plnum - 1) {//Checking if Updating is done
                    $sender->sendMessage($this->main->PREFIX . "Updating Done!");
                    $sender->sendMessage($this->main->PREFIX . "Restart the server to take effect");
                }
            }
        }
        $sender->sendMessage($this->main->PREFIX . "All plugins API have been updated!");
        return true;
    }
}
