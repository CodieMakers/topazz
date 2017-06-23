<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Config;


use Topazz\Data\Filesystem;
use Topazz\Data\Utils;

class Config implements ConfigInterface, \ArrayAccess {

    const REMOVED_VALUE = "__removed";
    protected $configs = [];

    public function __construct(array $configs) {
        foreach ($configs as $key => $value) {
            $this->configs[$key] = $this->getValueOrConfig($value);
        }
    }

    public function get(string $key, $default = null) {
        $currentKey = $this->getCurrentLayerKey($key, $nextKey);
        if (!isset($this->configs[$currentKey])) {
            return $default;
        }
        if ($this->configs[$currentKey] instanceof Config) {
            if (!is_null($nextKey)) {
                return $this->configs[$currentKey]->get($nextKey);
            }
        }
        if (is_string($this->configs[$currentKey]) && $this->configs[$currentKey] === self::REMOVED_VALUE) {
            return $default;
        }
        return $this->configs[$currentKey];
    }

    public function set(string $key, $value) {
        $currentLayerKey = $this->getCurrentLayerKey($key, $nextKey);
        if (is_null($nextKey)) {
            $this->configs[$currentLayerKey] = $this->getValueOrConfig($value);
            return;
        } elseif (!isset($this->configs[$currentLayerKey])) {
            $this->configs[$currentLayerKey] = new Config([]);
        }
        if ($this->configs[$currentLayerKey] instanceof Config) {
            $this->configs[$currentLayerKey]->set($nextKey, $value);
        }
    }

    public function remove(string $key) {
        $this->set($key, self::REMOVED_VALUE);
    }

    public function exists(string $key): bool {
        $currentLayerKey = $this->getCurrentLayerKey($key, $nextKey);
        if (isset($this->configs[$currentLayerKey])) {
            if ($this->configs[$currentLayerKey] instanceof Config && !is_null($nextKey)) {
                return $this->configs[$currentLayerKey]->exists($nextKey);
            }
            return true;
        }
        return false;
    }

    public function toArray($traverse = false): array {
        $configs = [];
        foreach ($this->configs as $key => $value) {
            if (is_string($value) && $value === self::REMOVED_VALUE) {
                continue;
            }
            if ($traverse && $value instanceof Config) {
                $value = $value->toArray();
            }
            $configs[$key] = $value;
        }
        return $configs;
    }

    public static function merge(Config $defaults, Config...$items) {
        $defaults = $defaults->toArray();
        foreach ($items as $item) {
            $defaults = array_merge($defaults, $item->toArray());
        }
        return new Config($defaults);
    }

    private function getCurrentLayerKey(string $key, &$nextKey) {
        $keyParts = explode(Configuration::KEY_SEPARATOR, $key);
        if (count($keyParts) > 1) {
            $nextKey = implode(Configuration::KEY_SEPARATOR, array_slice($keyParts, 1));
        } else {
            $nextKey = null;
        }
        return $keyParts[0];
    }

    private function getValueOrConfig($value) {
        if (Utils::isAssociativeArray($value)) {
            $value = new Config($value);
        }
        return $value;
    }

    public static function fromFile(string $filename): Config {
        $fileContent = Filesystem::parseYAML($filename);
        return new Config($fileContent);
    }


    /**
     * @return \Iterator
     */
    public function getIterator() {
        return new \ArrayIterator($this->toArray());
    }

    /**
     * Whether a offset exists
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset) {
        return $this->exists($offset);
    }

    /**
     * Offset to retrieve
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset) {
        return $this->get($offset);
    }

    /**
     * Offset to set
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value) {
        $this->set($offset, $value);
    }

    /**
     * Offset to unset
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset) {
        $this->remove($offset);
    }
}