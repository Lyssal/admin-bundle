<?php
namespace Lyssal\AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Bundle de console administrative de Lyssal basé sur SonataAdmin.
 */
class LyssalAdminBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'SonataAdminBundle';
    }
}
