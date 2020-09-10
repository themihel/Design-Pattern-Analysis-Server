<?php

namespace App\Controllers;


abstract class BaseController
{
    /**
     * @var \Slim\Container
     */
    protected $_container;

    /**
     * Controller constructor.
     *
     * @param \Slim\Container $container
     */
    public function __construct(\Slim\Container $container)
    {
        $this->_container = $container;
    }
}
