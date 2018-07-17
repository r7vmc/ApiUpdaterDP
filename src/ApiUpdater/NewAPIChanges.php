<?php
declare(strict_types=1);

namespace ApiUpdater;

class NewAPIChanges{
    public function __construct(Base $main)
    {
        $this->main = $main;
    }
    public function apply($content){//New Changes
       $content = str_replace('PluginTask', 'Task', $content);
       $content = str_replace('parent::__construct($plugin);', ' ', $content);
       $content = str_replace('isUnderWater', 'isUnderWater', $content);
       $content = str_replace('$this->getServer()->getScheduler()', '$this->getScheduler()', $content);
       $content = str_replace("$this->getServer()->getScheduler()->scheduleAsyncTask", "$this->getServer()->getAsyncPool()->submitTask", $content);
       $content = str_replace("$this->getServer()->getScheduler()->scheduleAsyncTaskToWorker", "$this->getServer()->getAsyncPool()->submitTasktoWorker", $content);
       $content = str_replace('setDamage', 'setModifier', $content);
       $content = str_replace('getDamage', 'getModifier', $content);
       $content = str_replace('getOriginalDamage', 'getOriginalModifier', $content);
        return $content;
    }
    public function ClassPath(){
     return (new \ReflectionClass(static::class))->getFileName();
    }
}
