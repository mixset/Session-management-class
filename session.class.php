<?php
/**
 * @author Dominik Ryńko <http://rynko.pl/>
 * @license http://creativecommons.org/licenses/by-sa/3.0/pl/
 * @Version 1.2
 */

namespace Module;

/**
 * Class Session
 * @package Module
 */
class Session {

    /**
     * @param none
     * @return string || bool
     */
    public function getSessionId()
    {
        return !empty(session_id()) ? session_id() : false;
    }
    /**
     * @param bool $type
     * @return bool
     */
    public function regenerateId($type = true)
    {
        return $type ? session_regenerate_id(true) : session_regenerate_id();
    }

    /**
     * @param $key
     * @return string
     */
    public function get($key)
    {
        return $this -> secure($_SESSION[$key]);
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this -> secure($_SESSION);
    }

    /**
     * @param array $data
     * @return string || array
     */
    public function set($data = [])
    {
        if (count($data) == 1) {
            if (version_compare(PHP_VERSION, '5.4.0') <= 0) {
                $keys = array_keys($data);
                $values = array_values($data);
                $_SESSION[$keys[0]] = $values[0];
            } else {
                $_SESSION[array_keys($data)[0]] = array_values($data)[0];
            }
        } else {
            foreach ($data as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }

        return null;
    }
    /**
     * @param string $name
     * @return bool
     */
    public function exists($name)
    {
        return empty($name) ? null : array_key_exists($name, $_SESSION);
    }

    /**
     * @param bool $type
     */
    public function remove($type = false)
    {
        if ($type === false) {
            session_destroy();
        } else {
            session_unset();
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function removeOne($key)
    {
        if (empty($key)) {
            return null;
        } else {
            if ($this -> exists($key)) {
                unset($_SESSION[$key]);
            }
        }

        return null;
    }

    /**
     * @param $data
     * @return string
     */
    private function secure($data)
    {
        if (!is_array($data)) {
            return htmlspecialchars(trim($data));
        } else {
            $array = [];
            foreach ($data as $key => $value) {
                $array[$key] = $this -> secure($value);
            }

            return $array;
        }
    }
}