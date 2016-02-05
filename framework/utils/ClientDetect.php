<?php
/**
 * Created by PhpStorm.
 * User: KOH
 * Date: 16/2/6
 * Time: 上午12:55
 */

namespace koh\framework\utils;


class ClientDetect
{
    private static $_mobileDetect;

    private $_isMobile;
    private $_isTablet;
    private $_isIOS;
    private $_isAndroid;

    /**
     * @return MobileDetect
     */
    private static function mobileDetect(): MobileDetect {
        if (!isset(self::$_mobileDetect)) {
            self::$_mobileDetect = new MobileDetect();
        }
        return self::$_mobileDetect;
    }

    /**
     * @return bool
     */
    public function isMobile(): bool {
        if (!isset($this->_isMobile)) {
            $this->_isMobile = self::mobileDetect()->isMobile();
        }
        return $this->_isMobile;
    }

    /**
     * @return bool
     */
    public function isTablet(): bool {
        if (!isset($this->_isTablet)) {
            $this->_isTablet = self::mobileDetect()->isTablet();
        }
        return $this->isTablet();
    }

    /**
     * @return bool
     */
    public function isDesktop(): bool {
        return !$this->isMobile() && !$this->_isTablet();
    }

    /**
     * @return bool
     */
    public function isIOS(): bool {
        if (!isset($this->_isIOS)) {
            $this->_isIOS = self::mobileDetect()->is('iOS');
        }
        return $this->_isIOS;
    }

    /**
     * @return bool
     */
    public function isAndroid(): bool {
        if (!isset($this->_isAndroid)) {
            $this->_isAndroid = self::mobileDetect()->is('AndroidOS');
        }
        return $this->_isAndroid;
    }

}