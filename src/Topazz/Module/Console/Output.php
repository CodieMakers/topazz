<?php
/**
 * @author  Lukáš
 * @version 1.0.0
 * @package Topazz
 */

namespace Topazz\Module\Console;


use Symfony\Component\Console\Output\Output as AbstractOutput;
use Topazz\Data\Collection;

class Output extends AbstractOutput {

    /** @var Collection $messages */
    private $messages;
    private $lastMessage;

    /**
     * Writes a message to the output.
     *
     * @param string $message A message to write to the output
     * @param bool   $newline Whether to add a newline or not
     */
    protected function doWrite($message, $newline) {
        if (is_null($this->messages)) {
            $this->messages = new Collection([]);
        }
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
        return $this->messages->join($glue);
    }
}