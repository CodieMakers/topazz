<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz;

use PHPUnit\Framework\TestCase;
use Topazz\Config\Config;
use Topazz\Config\Configuration;
use Topazz\Data\Filesystem;

class ConfigurationTest extends TestCase {
    /** @var Configuration $configuration */
    protected $configuration;
    protected static $key = 'system.config.key';
    protected static $wrongKey = 'system2.config.key';

    protected function setUp() {
        $this->configuration = Application::getInstance()->getApp()->getContainer()->get("config");
    }

    public function testExists() {
        $this->assertTrue($this->configuration->exists(self::$key));
        $this->assertFalse($this->configuration->exists(self::$wrongKey));
    }

    public function testGet() {
        $value = $this->configuration->get(self::$key);
        $this->assertNotNull($value);
        $this->assertEquals("value", $value);
        $this->assertNull($this->configuration->get(self::$wrongKey));
    }

    public function testSet() {
        $this->configuration->set(self::$key, "another-value");
        $anotherValue = $this->configuration->get(self::$key);
        $this->assertNotNull($anotherValue);
        $this->assertEquals("another-value", $anotherValue);
    }

    public function testSetNonExistent() {
        $this->assertFalse($this->configuration->exists(self::$wrongKey));
        $this->configuration->set(self::$wrongKey, "wrong-key-value");
        $this->assertTrue($this->configuration->exists(self::$wrongKey));
        $this->assertEquals("wrong-key-value", $this->configuration->get(self::$wrongKey));
    }

    public function testRemove() {
        $this->configuration->remove(self::$key);
        $this->assertFalse($this->configuration->exists(self::$key));
    }

    public function testConfigWriteDown() {
        $this->configuration->set("another-key", "another-value");
        try {
            throw new TopazzApplicationException();
        } catch (TopazzApplicationException $exception) {
            $config = Config::fromFile("config.test.yml");
            $this->assertTrue($config->exists("another-key"));
        }
    }

    protected function tearDown() {
        if ($this->configuration->exists(self::$wrongKey)) {
            $this->configuration->remove(self::$wrongKey);
        }
        if (!$this->configuration->exists(self::$key)) {
            $this->configuration->set(self::$key, "value");
        } elseif ($this->configuration->get(self::$key) !== "value") {
            $this->configuration->set(self::$key, "value");
        }
        if ($this->configuration->exists("another-key")) {
            $this->configuration->remove("another-key");
        }
    }
}
