<?php

namespace App\Symfony\Resolver;

use App\App\Presentation\Model\Request\Message;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class ContactMessageResolver
{
    /**
     * @var SingleItemRequestModelResolver $model
     */
    private $model;
    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return bool|\Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield $this->model;
    }
    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        if ($argument->getType() !== Message::class) {
            return false;
        }

        $messageArray = $this->extractDataFromRequest($request);

        $this->model = new Message($messageArray['name'], $messageArray['message']);

        return true;
    }
    /**
     * @param Request $request
     * @return null|array
     */
    private function extractDataFromRequest(Request $request): ?array
    {
        $content = $request->getContent();

        if (empty($content)) {
            return null;
        }

        $decodedContent = json_decode($content, true);

        if (!array_key_exists('message', $decodedContent)) {
            return null;
        }

        $message = $decodedContent['message'];

        if (!array_key_exists('message', $message)) {
            return null;
        }

        return [
            'name' => (!isset($message['name'])) ? 'Anonymous' : $message['name'],
            'message' => $message['message'],
        ];
    }
}