<?php
/**
 * Created by PhpStorm.
 * User: KOH
 * Date: 16/2/6
 * Time: 上午12:26
 */

namespace koh\framework\core;


abstract class Application
{
    abstract public function start();

    public function stop() {
        die;
    }
}