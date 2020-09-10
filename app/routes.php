<?php

/**
 * Frontend Routes
 */
$app->get('/', 'IndexController:getIndex')
    ->setName('index');
$app->post('/trackaction', 'IndexController:postTrackaction')
    ->setName('trackaction');
$app->get('/statistics/generate', 'StatisticsController:getGenerate')
    ->setName('generateStats');