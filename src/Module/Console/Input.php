<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Module\Console;


use Symfony\Component\Console\Input\ArrayInput;

class Input extends ArrayInput {

    public function __construct(string $command, array $parameters, $definition = null) {
        parent::__construct(array_merge(["command" => $command], $parameters), $definition);
    }
}