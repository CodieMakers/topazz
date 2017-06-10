<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Event;


use Slim\Http\Request;
use Slim\Http\Response;
use Topazz\Application;
use Topazz\Data\Collection\Map\Map;
use Topazz\Data\Collection\Stack\Stack;
use Topazz\Data\StringUtils;

class EventEmitter {

    protected $eventListeners;

    public function __construct() {
        $this->eventListeners = new Map();
    }

    public function emit(string $hook, Event $event = null) {
        $hook = $this->normalizeHookName($hook);
        $listenerStack = $this->eventListeners[$hook];
        if (!is_null($listenerStack) && $listenerStack instanceof Stack) {
            if (is_null($event)) {
                $event = new Event();
            }
            while ($listenerStack->hasNext()) {
                /** @var EventListener $top */
                $top = $listenerStack->top();
                if (method_exists($top, $hook)) {
                    /** @var Event $event */
                    $event = call_user_func([$top, $hook], $event);
                    if ($event->hasPropagationStopped()) {
                        break;
                    }
                }
            }
        }
    }

    public function subscribe(string $hook, EventListener $listener) {
        $hook = $this->normalizeHookName($hook);
        /** @var Stack $stack */
        $stack = $this->eventListeners->get($hook)->orGet(new Stack());
        $stack->put($listener);
        $this->eventListeners[$hook] = $stack;
    }

    private function normalizeHookName(string $hook): string {
        return StringUtils::camelize($hook);
    }

    private static function getInstance(): EventEmitter {
        return Application::getInstance()->getApp()->getContainer()->get('events');
    }

    public static function emitEventBeforeMiddleware(string $hook, Event $event = null) {
        $emitter = self::getInstance();
        return function (Request $request, Response $response, callable $next) use ($emitter, $hook, $event) {
            $emitter->emit($hook, $event);
            return $next($request, $response);
        };
    }

    public static function emitEventAfterMiddleware(string $hook, Event $event = null) {
        $emitter = self::getInstance();
        return function (Request $request, Response $response, callable $next) use ($emitter, $hook, $event) {
            $response = $next($request, $response);
            $emitter->emit($hook, $event);
            return $response;
        };
    }
}