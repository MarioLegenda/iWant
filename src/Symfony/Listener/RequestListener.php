<?php

namespace App\Symfony\Listener;

use App\Doctrine\Entity\IpAddress;
use App\Doctrine\Repository\IpAddressRepository;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestListener
{
    /**
     * @var IpAddressRepository $ipAddressRepository
     */
    private $ipAddressRepository;
    /**
     * RequestListener constructor.
     * @param IpAddressRepository $ipAddressRepository
     */
    public function __construct(
        IpAddressRepository $ipAddressRepository
    ) {
        $this->ipAddressRepository = $ipAddressRepository;
    }
    /**
     * @param GetResponseEvent $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($event->getRequest()->isXmlHttpRequest()) {
            return;
        }

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