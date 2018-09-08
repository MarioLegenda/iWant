<?php

namespace App\Symfony\BlueDot;

use BlueDot\BlueDot;
use BlueDot\Kernel\Connection\Connection;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class BlueDotFactory
{
    /**
     * @param string $projectDir
     * @param ManagerRegistry $doctrine
     * @return BlueDot
     * @throws \BlueDot\Exception\ConfigurationException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\RepositoryException
     */
    public function createBlueDot(string $projectDir, ManagerRegistry $doctrine): BlueDot
    {
        $conn = $doctrine->getConnection()->getWrappedConnection();

        $blueDotConnection = new Connection();
        $blueDotConnection->setPdo($conn);

        $blueDot = new BlueDot();
        $blueDot->setConnection($blueDotConnection);
        $blueDotDir = realpath(sprintf('%s/config/packages/blue_dot', $projectDir));

        $blueDot->setConfiguration($blueDotDir);

        return $blueDot;
    }
}