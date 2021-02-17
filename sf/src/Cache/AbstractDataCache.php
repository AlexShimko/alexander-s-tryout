<?php

declare(strict_types=1);

namespace App\Cache;

/**
 * Class AbstractDataCache implements Singleton patter for in-memory script cache
 * In perfect case it should be implemented in proper (not static)
 * But here we using in-memory calculations without any storages
 * So it is a quick and simple way to store data in script memory
 * @package App\Cache
 */
abstract class AbstractDataCache
{
    /**
     * @var array of potential instances
     */
    private static array $instances = [];

    /**
     * @var array - data storage
     */
    private array $_cache = [];

    /**
     * Closed constructor
     */
    protected function __construct() { }

    /**
     * Closed clone method
     */
    protected function __clone() { }

    /**
     * @throws \Exception
     */
    public function __wakeup()
    {
        throw new \Exception('AbstractDataCache can not be serialized');
    }

    /**
     * No return type here because of late static binding
     *
     * @return static
     */
    public static function getInstance()
    {
        $class = static::class;
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static();
        }

        return self::$instances[$class];
    }

    /**
     * @param string $id
     * @param $data
     */
    public function setCache(string $id, $data): void
    {
        if (\is_array($data)) {
            $this->_cache[$id] = $data;
        } else {
            $this->_cache[$id][] = $data;
        }
    }

    /**
     * @param string $id
     * @return array
     */
    public function getCache(string $id): array
    {
        if ($this->hasKey($id)) {
            return $this->_cache[$id];
        }

        return [];
    }

    /**
     * @param string $id
     * @return bool
     */
    public function hasKey(string $id): bool
    {
        return isset($this->_cache[$id]);
    }
}
