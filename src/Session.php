<?php
/**
 * Created by PhpStorm.
 * User: Oleg G.
 * Date: 27.05.2018
 * Time: 10:29
 */

namespace PhpHelper\Session;

use PhpHelper\Session\Enums\SessionEnum;

class Session
{
    const MESSAGES_CONTAINER = '_flash';

    public function __construct()
    {
        session_start();
    }

    /**
     * @param string|int $key
     * @param mixed $value
     */
    public function set($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string|int $key
     * @param mixed $defaultValue
     * @return mixed
     */
    public function get($key, $defaultValue = '')
    {
        if($this->isDefined($key))
            return $_SESSION[$key];
        else
            return $defaultValue;
    }

    /**
     * @param string|int $key
     */
    public function delete($key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * @param $key
     * @return bool
     */
    public function isDefined($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * @param string|int $key
     * @param mixed $value
     */
    public function append($key, $value): void
    {
        if (!$this->isDefined($key)) {
            $this->set($key, $value);
        } else {
            $values = $this->get($key);
            if (is_array($values)) {
                $values[] = $value;
            } else {
                $values = [$values, $value];
            }
            $this->set($key, $values);
        }
    }

    /**
     * @param string $messageType
     * @param string $message
     */
    private function setFlash(string $messageType, string $message): void
    {
        $this->append(self::MESSAGES_CONTAINER . '.' . $messageType, $message);
    }

    /**
     * @param string $messageType
     */
    private function deleteFlashMessages(string $messageType): void
    {
        $this->delete(self::MESSAGES_CONTAINER . '.' . $messageType);
    }

    public function setFlashMessage(string $message): void
    {
        $this->setFlash(SessionEnum::MESSAGE, $message);
        $this->deleteFlashMessages(SessionEnum::MESSAGE);
    }

    public function setFlashError(string $message): void
    {
        $this->setFlash(SessionEnum::ERROR, $message);
        $this->deleteFlashMessages(SessionEnum::ERROR);
    }

    /**
     * @return mixed
     */
    public function getFlash()
    {
        return $this->get(self::MESSAGES_CONTAINER);
    }

    /**
     * @return mixed
     */
    public function getFlashMessages()
    {
        return $this->get(self::MESSAGES_CONTAINER . '.' . SessionEnum::MESSAGE);
    }

    /**
     * @return mixed
     */
    public function getFlashErrors()
    {
        return $this->get(self::MESSAGES_CONTAINER . '.' . SessionEnum::ERROR);
    }
}