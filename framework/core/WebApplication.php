<?php
/**
 * Created by PhpStorm.
 * User: KOH
 * Date: 16/2/6
 * Time: 上午12:34
 */

namespace koh\framework\core;


use koh\framework\utils\ClientDetect;

class WebApplication extends Application
{
    protected $_clientDetect;

    public function start()
    {
        // TODO: Implement start() method.
    }

    /**
     * @return ClientDetect
     */
    public function clientDetect(): ClientDetect {
        if (!isset($this->_clientDetect)) {
            $this->_clientDetect = new ClientDetect();
        }
        return $this->_clientDetect;
    }

    public function startSession($domain) {
        if (session_status() == PHP_SESSION_NONE) {
            $session_name = session_name();
            if (isset($_REQUEST[$session_name])) {
                session_id($_REQUEST[$session_name]);
                $domain = '';
            }
            ini_set('session.gc_probability', 1);
            ini_set('session.gc_divisor', 100);
            ini_set('session.cookie_lifetime', 2592000);
            ini_set('session.gc_maxlifetime', 2592000);
            ini_set('session.cookie_domain', $domain);
            //$sessionHandler = new K_MemcachedSessionHandler();
            $sessionHandler = new K_RedisSessionHandler();
            /** @noinspection PhpParamsInspection */
            session_set_save_handler($sessionHandler);
            session_start();
        }
    }
}