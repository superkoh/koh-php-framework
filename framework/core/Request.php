<?php
/**
 * Created by PhpStorm.
 * User: KOH
 * Date: 16/2/6
 * Time: 上午12:39
 */

namespace koh\framework\core;


class Request
{

    private $_isAjax;

    /**
     * @return bool
     */
    public function isAjax(): bool {
        if (!isset($this->_isAjax)) {
            $this->_isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        }
        return $this->_isAjax;
    }


    /**
     * @return string
     */
    public function getClientIp(): string {
        if (getenv('HTTP_CLIENT_IP')) {
            $clientIp = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $clientIp = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('X-FORWARDED-FOR')) {
            $clientIp = getenv('X-FORWARDED-FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $clientIp = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $clientIp = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $clientIp = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $clientIp = getenv('REMOTE_ADDR');
        } else {
            $clientIp = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        }
        return $clientIp;
    }

    public static function redirect($uri) {
        header('Location: ' . $uri);
        die;
    }

    /**
     * @return int
     */
    public static function getTime() {
        if (K::app() instanceof K_Task) {
            return time();
        }
        return $_SERVER['REQUEST_TIME'];
    }


    private static function getRequestStringParam($method, $param, $defaultValue = null, $allowEmpty = true) {
        $value = empty(self::${$method}[$param]) ? $defaultValue : trim(self::${$method}[$param]);
        if (!$allowEmpty && empty($value))
            throw new K_Exception($param . ' is empty', -1);
        return !empty($value) ? $value : $defaultValue;
    }

    /**
     * @param string $param
     * @param null $defaultValue
     * @param bool $allowEmpty
     * @throws K_Exception
     * @return null|string
     */
    public static function getStringParam($param, $defaultValue = null, $allowEmpty = true) {
        return self::getRequestStringParam('get', $param, $defaultValue, $allowEmpty);
    }

    /**
     * @param string $param
     * @param null $defaultValue
     * @param bool $allowEmpty
     * @throws K_Exception
     * @return null|string
     */
    public static function getStringPost($param, $defaultValue = null, $allowEmpty = true) {
        return self::getRequestStringParam('post', $param, $defaultValue, $allowEmpty);
    }

    /**
     * @param string $param
     * @param null $defaultValue
     * @param bool $allowEmpty
     * @throws K_Exception
     * @return null|string
     */
    public static function getStringReq($param, $defaultValue = null, $allowEmpty = true) {
        return self::getRequestStringParam('request', $param, $defaultValue, $allowEmpty);
    }

    /**
     * @param string $param
     * @param null $defaultValue
     * @param bool $allowEmpty
     * @throws K_Exception
     * @return null|string
     */
    public static function getSlashedStringReq($param, $defaultValue = null, $allowEmpty = true) {
        return addslashes(self::getRequestStringParam('request', $param, $defaultValue, $allowEmpty));
    }

    private static function getRequestNumberParam($method, $type, $param, $defaultValue = null, $allowEmpty = true) {
        $value = !isset(self::${$method}[$param]) ? $defaultValue : trim(self::${$method}[$param]);
        if (!$allowEmpty && $value == '') throw new K_Exception($param . ' is empty', -1);
        if (!empty($value) && !is_numeric($value)) throw new K_Exception($param . ' is not a number', -1);
        $numFunc = $type . 'val';
        $value = $numFunc($value);
        return $value;
    }

    /**
     * @param string $param
     * @param null $defaultValue
     * @param bool $allowEmpty
     * @throws K_Exception
     * @return int|null
     */
    public static function getIntParam($param, $defaultValue = null, $allowEmpty = true) {
        return self::getRequestNumberParam('get', 'int', $param, $defaultValue, $allowEmpty);
    }


    /**
     * @param string $param
     * @param null $defaultValue
     * @param bool $allowEmpty
     * @throws K_Exception
     * @return int|null
     */
    public static function getIntPost($param, $defaultValue = null, $allowEmpty = true) {
        return self::getRequestNumberParam('post', 'int', $param, $defaultValue, $allowEmpty);
    }

    /**
     * @param string $param
     * @param null $defaultValue
     * @param bool $allowEmpty
     * @throws K_Exception
     * @return int|null
     */
    public static function getIntReq($param, $defaultValue = null, $allowEmpty = true) {
        return self::getRequestNumberParam('request', 'int', $param, $defaultValue, $allowEmpty);
    }

    /**
     * @param string $param
     * @param null $defaultValue
     * @param bool $allowEmpty
     * @throws K_Exception
     * @return float|string
     */
    public static function getFloatParam($param, $defaultValue = null, $allowEmpty = true) {
        return self::getRequestNumberParam('get', 'float', $param, $defaultValue, $allowEmpty);
    }

    /**
     * @param string $param
     * @param null $defaultValue
     * @param bool $allowEmpty
     * @throws K_Exception
     * @return float|string
     */
    public static function getFloatPost($param, $defaultValue = null, $allowEmpty = true) {
        return self::getRequestNumberParam('post', 'float', $param, $defaultValue, $allowEmpty);
    }

    /**
     * @param string $param
     * @param null $defaultValue
     * @param bool $allowEmpty
     * @throws K_Exception
     * @return float|string
     */
    public static function getFloatReq($param, $defaultValue = null, $allowEmpty = true) {
        return self::getRequestNumberParam('request', 'float', $param, $defaultValue, $allowEmpty);
    }

    private static function getRequestStringArr($method, $param, array $defaultValue = [], $allowEmpty = true) {
        $value = empty(self::${$method}[$param]) ? $defaultValue : trim(self::${$method}[$param]);
        if (!$allowEmpty && $value == '') throw new K_Exception($param . ' is empty', -1);
        if (empty($value)) return [];
        $arr = explode(',', $value);
        return $arr;
    }

    /**
     * @param string $param
     * @param array $defaultValue
     * @param bool $allowEmpty
     * @throws K_Exception
     * @return array
     */
    public static function getStringArrParam($param, array $defaultValue = [], $allowEmpty = true) {
        return self::getRequestStringArr('get', $param, $defaultValue, $allowEmpty);
    }

    /**
     * @param string $param
     * @param array $defaultValue
     * @param bool $allowEmpty
     * @throws K_Exception
     * @return array
     */
    public static function getStringArrPost($param, array $defaultValue = [], $allowEmpty = true) {
        return self::getRequestStringArr('post', $param, $defaultValue, $allowEmpty);
    }

    /**
     * @param string $param
     * @param array $defaultValue
     * @param bool $allowEmpty
     * @throws K_Exception
     * @return array
     */
    public static function getStringArrReq($param, array $defaultValue = [], $allowEmpty = true) {
        return self::getRequestStringArr('request', $param, $defaultValue, $allowEmpty);
    }

    private static function getRequestIntArr($method, $type, $param, array $defaultValue = [], $allowEmpty = true) {
        $value = empty(self::${$method}[$param]) ? $defaultValue : trim(self::${$method}[$param]);
        if (!$allowEmpty && $value == '') throw new K_Exception($param . ' is empty', -1);
        if (empty($value)) return [];
        $arr = explode(',', $value);
        $retArr = [];
        foreach ($arr as $number) {
            if (!is_numeric($number)) throw new K_Exception($param . ' is illegal', -1);
            $numFunc = $type . 'val';
            $number = $numFunc($number);
            $retArr[] = $number;
        }
        return $retArr;
    }

    /**
     * @param string $param
     * @param array $defaultValue
     * @param bool $allowEmpty
     * @throws K_Exception
     * @return array
     */
    public static function getIntArrParam($param, array $defaultValue = [], $allowEmpty = true) {
        return self::getRequestIntArr('get', 'int', $param, $defaultValue, $allowEmpty);
    }

    /**
     * @param string $param
     * @param array $defaultValue
     * @param bool $allowEmpty
     * @throws K_Exception
     * @return array
     */
    public static function getIntArrPost($param, array $defaultValue = [], $allowEmpty = true) {
        return self::getRequestIntArr('post', 'int', $param, $defaultValue, $allowEmpty);
    }

    /**
     * @param string $param
     * @param array $defaultValue
     * @param bool $allowEmpty
     * @throws K_Exception
     * @return array
     */
    public static function getIntArrReq($param, array $defaultValue = [], $allowEmpty = true) {
        return self::getRequestIntArr('request', 'int', $param, $defaultValue, $allowEmpty);
    }

    /**
     * @param string $param
     * @param array $defaultValue
     * @param bool $allowEmpty
     * @throws K_Exception
     * @return array
     */
    public static function getFloatArrParam($param, array $defaultValue = [], $allowEmpty = true) {
        return self::getRequestIntArr('get', 'float', $param, $defaultValue, $allowEmpty);
    }

    /**
     * @param string $param
     * @param array $defaultValue
     * @param bool $allowEmpty
     * @throws K_Exception
     * @return array
     */
    public static function getFloatArrPost($param, array $defaultValue = [], $allowEmpty = true) {
        return self::getRequestIntArr('post', 'float', $param, $defaultValue, $allowEmpty);
    }

    /**
     * @param string $param
     * @param array $defaultValue
     * @param bool $allowEmpty
     * @throws K_Exception
     * @return array
     */
    public static function getFloatArrReq($param, array $defaultValue = [], $allowEmpty = true) {
        return self::getRequestIntArr('request', 'float', $param, $defaultValue, $allowEmpty);
    }

    /**
     * @param array $params
     * @return string
     */
    public static function http_build_query(array $params) {
        $pArr = [];
        if (empty($params)) return '';
        foreach ($params as $key => $value) {
            if(!isset($value)) {
                continue;
            }
            if (is_bool($value)) {
                $pArr[] = $key . '=' . ($value ? 'true' : 'false');
            } else {
                $pArr[] = $key .'=' . urlencode($value);
            }
        }
        return '?' . implode('&', $pArr);
    }

    /**
     * @param array $postData
     * @return array
     */
    public static function http_post_data(array $postData) {
        $pArr = [];
        if(!isset($postData) || empty($postData)) {
            return [];
        }
        else {
            foreach($postData as $key => $value) {
                if(isset($value)) {
                    $pArr[$key] = $value;
                }
            }
        }
        return $pArr;
    }
}