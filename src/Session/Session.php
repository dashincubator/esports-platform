<?php declare(strict_types=1);

namespace System\Session;

use Contracts\Session\Session as Contract;
use System\Collections\Associative as Collection;

class Session extends Collection implements Contract
{

    // Default ID Length
    private const DEFAULT_ID_LENGTH = 64;

    // Minimum Length That Is Cryptographically Secure
    private const MIN_ID_LENGTH = 16;

    // Maximum Length That PHP Allows
    private const MAX_ID_LENGTH = 128;


    // Flash Storage Keys
    private const NEW_FLASH_KEY = '__FLASH.NEW';

    private const OLD_FLASH_KEY = '__FLASH.OLD';


    // Session ID
    private $id;


    public function ageFlashKeys() : void
    {
        foreach ($this->getOldFlashKeys() as $key) {
            $this->delete($key);
        }

        $this->set(self::OLD_FLASH_KEY, $this->getNewFlashKeys());
        $this->set(self::NEW_FLASH_KEY, []);
    }


    public function flash(string $key, $value) : void
    {
        $this->push(self::NEW_FLASH_KEY, $key);
        $this->set($key, $value);

        $old = $this->getOldFlashKeys();

        if (($index = array_search($key, $old)) !== false) {
            unset($old[$index]);
        }

        $this->set(self::OLD_FLASH_KEY, $old);
    }


    protected function generateId(int $length = self::DEFAULT_ID_LENGTH) : string
    {
        // N Bytes Becomes 2N Characters In 'bin2hex', Hence The Division By 2
        $string = bin2hex(random_bytes((int) ceil($length / 2)));

        // Slice Off One Character To Make It The Appropriate Odd Length
        if ($length % 2 === 1) {
            $string = mb_substr($string, 1);
        }

        return $string;
    }


    public function getId() : string
    {
        return $this->id;
    }


    private function getNewFlashKeys() : array
    {
        return $this->get(self::NEW_FLASH_KEY, []);
    }


    private function getOldFlashKeys() : array
    {
        return $this->get(self::OLD_FLASH_KEY, []);
    }


    protected function isValidId(string $id) : bool
    {
        $regex = sprintf(
            '/^[a-z0-9]{%d,%d}$/i',
            self::MIN_ID_LENGTH,
            self::MAX_ID_LENGTH
        );

        return is_string($id) && preg_match($regex, $id);
    }


    public function reflash() : void
    {
        $this->set(self::NEW_FLASH_KEY, array_merge($this->getNewFlashKeys(), $this->getOldFlashKeys()));
        $this->set(self::OLD_FLASH_KEY, []);
    }


    public function regenerate(bool $flush = true) : void
    {
        $this->setId($this->generateId());

        if ($flush) {
            $this->clear();
        }
    }


    private function setId(string $id) : void
    {
        $this->id = $id;
    }


    public function start(string $id) : void
    {
        if ($this->isValidId($id)) {
            $this->setId($id);
        }
        else {
            $this->regenerate();
        }

        $this->clear();
    }
}
