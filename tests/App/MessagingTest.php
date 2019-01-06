<?php

namespace App\Tests\App;

use App\App\Presentation\EntryPoint\MessagingEntryPoint;
use App\App\Presentation\Model\Request\Message;
use App\Tests\Library\BasicSetup;

class MessagingTest extends BasicSetup
{
    public function test_send_slack_message()
    {
        $messagingEntryPoint = $this->locator->get(MessagingEntryPoint::class);

        $message = new Message(
            'Anonymous',
            'Message text'
        );

        $messagingEntryPoint->sendMessageToSlack($message);
    }
}