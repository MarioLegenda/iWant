<?php

namespace App\Library\Slack;

class Metadata
{
    /**
     * @var string $title
     */
    private $title;
    /**
     * @var array $message
     */
    private $messages = [];
    /**
     * @var string $channel
     */
    private $channel;
    /**
     * Metadata constructor.
     * @param string|null $title
     * @param string $channel
     * @param array $messages
     */
    public function __construct(
        string $channel,
        array $messages,
        string $title = null
    ) {
        if (!is_string($title)) {
            $title = 'A generic title';
        }
        
        $this->title = sprintf("*%s*\n\n", $title);
        $this->channel = $channel;

        foreach ($messages as $message) {
            $this->addMessage($message);
        }
    }
    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = sprintf("*%s*\n\n", $title);
    }
    /**
     * @param string $message
     * @return Metadata
     */
    public function addMessage(string $message): Metadata
    {
        $this->messages[] = sprintf("%s\n", $message);

        return $this;
    }
    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
    /**
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }
    /**
     * @param string $channel
     */
    public function setChannel(string $channel): void
    {
        $this->channel = $channel;
    }
    /**
     * @return string
     */
    public function resolve(): string
    {
        return sprintf("%s%s", $this->getTitle(), implode($this->getMessages()));
    }
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->resolve();
    }
}