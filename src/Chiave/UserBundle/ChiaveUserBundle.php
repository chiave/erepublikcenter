<?php

namespace Chiave\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ChiaveUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
