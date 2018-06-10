<?php
/**
 * Created by PhpStorm.
 * User: Oleg G.
 * Date: 27.05.2018
 * Time: 10:29
 */

namespace PhpHelper\Session;

use PhpHelper\Session\Enums\FlashMessageEnum;

class Session
{
    const MESSAGES_CONTAINER = '_flash';

    public function __construct()
    {
        session_start();
    }

    public function clear(): void
    {
        session_destroy();
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
        if($this->has($key)) {
            return $_SESSION[$key];
	    } else {
            return $defaultValue;
        }
    }

    /**
     * @param string|int $key
     */
    public function delete($key): void
    {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * @param string|int $key
     * @return bool
     */
    public function has($key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function clearFlash(): void
    {
        $this->delete(self::MESSAGES_CONTAINER);
    }

    /**
     * @param string $messageType
     */
    private function clearFlashMessagesByType(string $messageType): void
    {
        $flashMessages = $this->getFlashContainer();
        if (isset($flashMessages[$messageType])) {
            unset($flashMessages[$messageType]);
        }
        if (empty($flashMessages)) {
            $this->delete(self::MESSAGES_CONTAINER);
        } else {
            $this->set(self::MESSAGES_CONTAINER, $flashMessages);
        }
    }

    public function setFlashMessage(string $message): void
    {
        $this->setFlashMessageByType(FlashMessageEnum::MESSAGE, $message);
    }

    public function setFlashError(string $message): void
    {
        $this->setFlashMessageByType(FlashMessageEnum::ERROR, $message);
    }

    public function setFlashWarning(string $message): void
    {
        $this->setFlashMessageByType(FlashMessageEnum::WARNING, $message);
    }

    /**
     * @param string $messageType
     * @param string $message
     */
    private function setFlashMessageByType(string $messageType, string $message): void
    {
        $flash = $this->get(self::MESSAGES_CONTAINER, []);
        if (!isset($flash[$messageType])) {
            $flash[$messageType] = [];
        }
        $flash[$messageType][] = $message;
        $this->set(self::MESSAGES_CONTAINER, $flash);
    }

    /**
     * @return mixed[]
     */
    public function getFlash(): array
    {
        $flashMessages = $this->get(self::MESSAGES_CONTAINER) ?: [];
        $this->clearFlash();
        return $flashMessages;
    }

    /**
     * @return mixed[]
     */
    private function getFlashContainer(): array
    {
        return $this->get(self::MESSAGES_CONTAINER) ?: [];
    }

    /**
     * @return mixed[]
     */
    public function getFlashMessages(): array
    {
        return $this->getFlashMessageByType(FlashMessageEnum::MESSAGE);
    }

    /**
     * @return mixed[]
     */
    public function getFlashErrors(): array
    {
        return $this->getFlashMessageByType(FlashMessageEnum::ERROR);
    }

    /**
     * @return mixed[]
     */
    public function getFlashWarnings(): array
    {
        return $this->getFlashMessageByType(FlashMessageEnum::WARNING);
    }

    /**
     * @param string $messageType
     * @return mixed[]
     */
    private function getFlashMessageByType(string $messageType): array
    {
        $flashMessages = $this->getFlashContainer();
        $this->clearFlashMessagesByType($messageType);
        return $flashMessages[$messageType] ?? [];
    }
}