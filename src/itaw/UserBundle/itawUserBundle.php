<?php

namespace itaw\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class itawUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
