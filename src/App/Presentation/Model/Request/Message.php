<?php

namespace App\App\Presentation\Model\Request;

class Message
{
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var string $message
     */
    private $message;
    /**
     * Message constructor.
     * @param string|null $name
     * @param string $message
     */
    public function __construct(
        string $name,
        string $message
    ) {
        $this->name = $name;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }


}