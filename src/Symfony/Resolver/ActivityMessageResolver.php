<?php

namespace App\Symfony\Resolver;

use App\Web\Model\Request\ActivityMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class ActivityMessageResolver implements ArgumentValueResolverInterface
{
    /**
     * @var ActivityMessage $model
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
        if ($argument->getType() !== ActivityMessage::class) {
            return false;
        }

        $content = $request->getContent();

        if (empty($content)) {
            return false;
        }

        $message = json_decode($content, true);

        if (!array_key_exists('activityMessage', $message)) {
            return false;
        }

        $message = $message['activityMessage'];

        $this->model = new ActivityMessage(
            (is_string($message['message'])) ? $message['message'] : jsonEncodeWithFix($message['message']),
            jsonEncodeWithFix($message['additionalData'])
        );

        return true;
    }
}