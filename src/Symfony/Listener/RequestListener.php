<?php

namespace App\Symfony\Listener;

use App\Doctrine\Entity\IpAddress;
use App\Doctrine\Repository\IpAddressRepository;
use App\Library\Http\Request\Type\HttpHeader;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestListener
{
    /**
     * @var IpAddressRepository $ipAddressRepository
     */
    private $ipAddressRepository;
    /**
     * @var LoggerInterface $logger
     */
    private $logger;
    /**
     * RequestListener constructor.
     * @param IpAddressRepository $ipAddressRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        IpAddressRepository $ipAddressRepository,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->ipAddressRepository = $ipAddressRepository;
    }
    /**
     * @param GetResponseEvent $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        gc_enable();

        $request = $event->getRequest();

        if ($event->getRequest()->isXmlHttpRequest()) {
            if ($request->headers->has(HttpHeader::HTTP_API_HEADER)) {
                $httpHeader = $request->headers->get(HttpHeader::HTTP_API_HEADER);
                if ($httpHeader !== 'api') {
                    $message = sprintf(
                        'Unauthorised request with a non %s',
                        HttpHeader::HTTP_API_HEADER
                    );

                    $this->logger->alert($message);

                    throw new \RuntimeException('Unauthorised access');
                }

                $this->saveVisitorIp($request);

                return;
            }

            $message = sprintf(
                'Unauthorised request with a non %s',
                HttpHeader::HTTP_API_HEADER
            );

            $this->logger->alert($message);

            throw new \RuntimeException('Unauthorised access');
        }

        $this->saveVisitorIp($request);
    }
    /**
     * @param Request $request
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function saveVisitorIp(Request $request)
    {
        $ipAddress = (string) $request->getClientIp();

        $existingModel = $this->ipAddressRepository->findOneBy([
            'ipAddress' => $ipAddress
        ]);

        if ($existingModel instanceof IpAddress) {
            $existingModel->incrementAccessTimes();

            $this->ipAddressRepository->persistAndFlush($existingModel);

            return;
        }

        $ipAddressModel = $this->createModel($ipAddress);

        $this->ipAddressRepository->persistAndFlush($ipAddressModel);
    }
    /**
     * @param string $ipAddress
     * @return IpAddress
     */
    private function createModel(string $ipAddress): IpAddress
    {
        return new IpAddress($ipAddress);
    }
}