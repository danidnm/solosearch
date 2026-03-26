<?php

namespace SoloSearch\Core\Console;

interface CommandInterface
{
    /**
     * Runs the command
     * 
     * @param array $argv
     * @return void
     */
    public function run(array $argv);
}
