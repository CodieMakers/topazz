<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Data;


use Gaufrette\Adapter\Local;
use Gaufrette\Filesystem as GaufretteFilesystem;
use Symfony\Component\Yaml\Yaml;

class Filesystem {

    protected $path;

    public function __construct(string $path = null) {
        $this->path = $path ?: getcwd();
    }

    public function setPath(string $path) {
        $this->path = $path;
    }

    public function getFilesystem(): GaufretteFilesystem {
        $adapter = new Local($this->path);
        return new GaufretteFilesystem($adapter);
    }

    public static function fromPath(string $path = null): GaufretteFilesystem {
        $filesystem = new Filesystem($path);
        return $filesystem->getFilesystem();
    }

    public static function parseYAML(string $filename) {
        $filesystem = self::fromPath();
        if ($filesystem->has($filename)) {
            return Yaml::parse($filesystem->read($filename), Yaml::PARSE_KEYS_AS_STRINGS);
        }
        return [];
    }

    public static function writeYAML(string $filename, array $content) {
        self::fromPath()->write(
            $filename,
            Yaml::dump($content, PHP_INT_MAX, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK),
            true
        );
    }
}