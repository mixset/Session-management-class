<?php
/**
 * @author Dominik Ryńko <http://rynko.pl/>
 * @license http://creativecommons.org/licenses/by-sa/3.0/pl/
*/

namespace SessionManager;

class Session
{
    /**
     * Keys from $_SESSION array, that are not filtered
     * Default: empty array
     *
     * @var array
    */
    public $rawResponse = [];

    /**
     * Check, if session has been initialized.
     *
     * Session constructor.
     * @throws SessionException
    */
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            throw new SessionException('session_start() function is not called.');
        }
    }

    /**
     * Setter for except array
     *
     * @param array $keys
    */
    public function setExceptKeys(array $keys)
    {
        $this->rawResponse = $keys;
    }

    /**
     * Clear all except keys
    */
    public function clearExceptKeys()
    {
        $this->rawResponse = [];
    }

    /**
     * @return null|string
    */
    public function getSessionId()
    {
        return empty(session_id()) === false
            ? session_id()
            : null;
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
        return $this->secure($key, $_SESSION[$key]);
    }

    /**
     * @return mixed
    */
    public function all()
    {
        return $this->secureArray($_SESSION);
    }

    /**
     * @param array $data
     * @param array $rawResponse
     *
     * @return string | array
    */
    public function set(array $data)
    {
        $data = $this->secureArray($data);

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
    private function secureArray($toFilter, $sanitize = FILTER_SANITIZE_STRING)
    {
        $notFiltered = $this->getNonFilteredElements($toFilter);
        return $notFiltered + filter_var_array($toFilter, $sanitize);
    }

    /**
     * @param $key
     * @param $toFilter
     * @param int $sanitize
     * @return mixed
    */
    private function secure($key, $toFilter, $sanitize = FILTER_SANITIZE_STRING)
    {
        if (array_key_exists($key, array_flip($this->rawResponse)) === true) {
            return $toFilter;
        }

        return filter_var($toFilter, $sanitize);
    }

    /**
     * @param $toFilter
     *
     * @return array
    */
    private function getNonFilteredElements($toFilter)
    {
        $notFiltered = [];

        if (count($this->rawResponse) > 0) {
            $notFiltered = array_diff_key(
                $toFilter,
                array_diff_key($toFilter, array_flip($this->rawResponse))
            );
        }

        return $notFiltered;
    }
}
