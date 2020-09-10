<?php

namespace App\Controllers;

use App\Models\Action;

class IndexController extends BaseController
{
    /**
     * Just a simple method to check the server works correctly
     * Can also be used for monitoring
     *
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getIndex($request, $response)
    {
        echo $response->isOk();
    }

    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function postTrackaction($request, $response)
    {
        // current info
        $userId = $request->getParam('userId');
        $action = $request->getParam('action');
        $version = $request->getParam('version');

        // current timestamp
        $date = new \DateTime();
        $timestamp = date_format($date, 'Y-m-d H:i:s');

        // create and save action model
        $action = new Action([
            'userId' => $userId,
            'action' => $action,
            'version' => $version,
            'timestamp' => $timestamp,
        ]);
        $action->save();

        echo $response->isOk();
    }

    /**
     * This endpoint is for testing purpose only
     * When you want to use it, add it to the routes file
     * @see app/routes.php
     *
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getShowAll($request, $response)
    {
        $actions = Action::all();
        $data['actions'] = $actions->toArray();

        echo $response->withJson($data);
    }
}
