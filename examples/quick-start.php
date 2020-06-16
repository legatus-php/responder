<?php

/*
 * This file is part of the Quilt project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../vendor/autoload.php';

// This automatically detects installed PSR Message Factories :)
$responder = Legatus\Http\Responder\basic();

$response = $responder->json([
    'msg' => 'This is a cool json response',
]);

$response = $responder->redirect('/take/me/here');

$response = $responder->html('<h1>Hello World!</h1>');

$response = $responder->blob('/file/location.ext');
