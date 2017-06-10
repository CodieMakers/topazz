<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz;

use Exception;
use Throwable;
use Topazz\Event\Event;
use Topazz\Event\EventEmitter;

class TopazzApplicationException extends Exception {

    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        /** @var EventEmitter $events */
        $events = Application::getInstance()->getApp()->getContainer()->get('events');
        $events->emit("onShutdown");
        $events->emit("onError", new Event(["exception" => &$this]));
        parent::__construct($message, $code, $previous);
    }
}