<?php

namespace Mixset\SessionManager;

use Mixset\SessionManager\Exceptions\SessionException;

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
     * Fire session_unset function
     * Clear $_SESSION variable
     * Equivalent to $_SESSION = []
    */
    const SESSION_CLEAR_VARIABLE = 1;

    /**
     * Fire session_destroy function
     * Destroys data, that is stored in the session storage
     * e.g. the session file in the file system)
    */
    const SESSION_CLEAR_FILE = 2;

    /**
     * Argument for session_regenerate_id
     * Default 0 - does not removes old session
    */
    const DELETE_OLD_SESSION = false;

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
     * Method is used to prevent Session hijacking attack
     *
     * @param bool $type
     * @return bool
     */
    public function regenerateId($type = self::DELETE_OLD_SESSION)
    {
        return session_regenerate_id($type);
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
     * Return all data from $_SESSION array
     *
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
     * @return array|string
     */
    public function set(array $data, array $rawResponse = [])
    {
        if (count($rawResponse) > 0) {
            $this->rawResponse = array_merge($this->rawResponse, $rawResponse);
        }

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
     * @param int $type
     *
     * @return bool
    */
    public function delete($type = self::SESSION_CLEAR_VARIABLE)
    {
        if ($type === self::SESSION_CLEAR_VARIABLE) {
            session_unset();
        }

        if ($type === self::SESSION_CLEAR_FILE) {
            return session_destroy();
        }

        return null;
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
