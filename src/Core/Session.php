<?php
/**
 * @author Dominik Ryńko <http://rynko.pl/>
 * @license http://creativecommons.org/licenses/by-sa/3.0/pl/
 * @Version 1.3
*/

namespace Core;

class Session
{
    /**
     * Keys from $_SESSION array, that are not filtered
     * Default: empty array
     *
     * @var array
    */
    protected $except = [];

    /**
     * Check, if session has been initialized.
     *
     * Session constructor.
     * @throws \SessionException
    */
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            throw new \SessionException('session_start() function is not called.');
        }
    }

    /**
     * Setter for except array
     *
     * @param array $keys
    */
    public function setExceptKeys(array $keys)
    {
        $this->except = $keys;
    }

    /**
     * @param none
     * @return string || bool
    */
    public function getSessionId()
    {
        return empty(session_id()) === false
            ? session_id()
            : false;
    }

    /**
     * Type:
     * 1 -> Only regenerate session ID
     * 2 -> Regenerate session ID with remove old session
     *
     * Method is used to prevent Session hijacking attack
     *
     * @param int $type
     * @return bool
    */
    public function regenerateId($type = 1)
    {
        return $type === 1
            ? session_regenerate_id()
            : session_regenerate_id(true);
    }

    /**
     * Return value of specific session key
     *
     * @param $key
     * @return string
    */
    public function get($key)
    {
        return $this->secure($_SESSION[$key]);
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->secure($_SESSION);
    }

    /**
     * @param array $data
     * @return string || array
     */
    public function set(array $data)
    {
        $data = $this->secure($data);

        if (count($data) === 1) {
            $_SESSION[array_keys($data)[0]] = array_values($data)[0];
        } else {
            foreach ($data as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }

        return true;
    }

    /**
     * Check, if given key session is present
     *
     * @param $key
     * @return bool
    */
    public function exists($key)
    {
        return array_key_exists($key, $_SESSION);
    }

    /**
     * $type int
     * 1 -> Destroys all data registered to a session
     * 2 -> Free all session variables
     *
     * @param int $type
    */
    public function delete($type = 1)
    {
        if ($type === 1) {
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
        if ($this->exists($key)) {
            unset($_SESSION[$key]);
            return true;
        }

        return false;
    }

    /**
     * Sanitize array with given filter
     * Default FILTER_SANITIZE_STRING
     *
     * Fields set in except property will be not sanitized.
     *
     * @param $toFilter
     * @param int $sanitize
     * @return mixed
    */
    private function secure($toFilter, $sanitize = FILTER_SANITIZE_STRING)
    {
        $notFiltered = [];

        if (count($this->except) > 0) {
            $notFiltered = array_diff_key(array_keys($toFilter), $this->except);
        }

        return $notFiltered + filter_var_array($toFilter, $sanitize);
    }
}
