<?php

namespace SoloSearch\Core\Setup;

interface InstallerInterface
{
    /**
     * Install or update the module
     *
     * @param string $version The current version of the module in the database
     * @return void
     */
    public function install($version = '0.0.0');
}
