<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Module\Console;


use Symfony\Component\Console\Output\Output as AbstractOutput;
use Topazz\Data\Collection\Collection;
use Topazz\Data\Collection\Lists\ArrayList;

class Output extends AbstractOutput {

    /** @var ArrayList $messages */
    private $messages;
    private $lastMessage;

    public function __construct($verbosity = AbstractOutput::VERBOSITY_NORMAL, $decorated = false, $formatter = null) {
        AbstractOutput::__construct($verbosity, $decorated, $formatter);
        $this->messages = new ArrayList([]);
    }

    protected function doWrite($message, $newline) {
        if (is_null($this->lastMessage)) {
            $this->lastMessage = "";
        }
        $this->lastMessage .= $message;
        if ($newline) {
            $this->messages->put($this->lastMessage);
            $this->lastMessage = null;
        }
    }

    public function getMessages() {
        return $this->messages;
    }

    public function join(string $glue = PHP_EOL) {
        return $this->messages->collect(Collection::toString($glue));
    }
}