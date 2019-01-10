<?php

namespace App\Symfony\Listener;

class ResponseListener
{
    public function onKernelResponse()
    {
        gc_collect_cycles();
    }
}