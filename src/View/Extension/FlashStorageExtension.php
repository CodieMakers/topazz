<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\View\Extension;


use Slim\Flash\Messages;
use Topazz\Container;

class FlashStorageExtension extends \Twig_Extension {

    /** @var Messages $flash */
    protected $flash;

    public function __construct(Container $container) {
        $this->flash = $container->get('flash');
    }

    public function getFunctions() {
        return [
            new \Twig_SimpleFunction("get_flash", [$this, "getFlashMessage"])
        ];
    }

    public function getFlashMessage(string $key) {
        return $this->flash->getMessage($key);
    }
}