<?php
/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Slim/Slim.php';
require 'config/config.php';

\Slim\Slim::registerAutoloader();

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new \Slim\Slim();

/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */



// POST route

$app->post('/new',
    function () use ($app) {
        $DbClass = new DbClass();
        $vf = $app->getInstance()->request();
        $body = $vf->getBody();
        $wine = json_decode($body);
        $paramValue1 = $wine->title;
        $paramValue2 = $wine->preacher;
        $paramValue3 = $wine->date;
        $insert = array(
            "media_title" => $paramValue1,
            "media_by" => $paramValue2,
            "create_date" => $paramValue3,
            "type" => "service",

        );
        //var_dump($insert);
        $insertQry = $DbClass->db->insert('crst_media', $insert);
        if ($insertQry == TRUE) {
            $app->response->headers->set('Content-Type', 'application/json');
            $getQry['message'] = 'Message Saved';
            $getQry['response'] = $app->response->status(200);
            echo json_encode($getQry);
        }
    }
);

$app->get('/msg/:id', function ($id) use ($app) {
    $DbClass = new DbClass();
    $sth = $DbClass->db->prepare("SELECT * FROM crst_media WHERE media_id = '$id'");
    $sth->execute();
    $count = $sth->rowCount();
    $row   = $sth->fetch();
    $app->response->headers->set('Content-Type', 'application/json');
    // $app->response->getBody($getQry);
    echo json_encode($row);
});

$app->get('/media',function() use($app){
    $DbClass = new DbClass();
    $getProds = $DbClass->db->prepare("SELECT * FROM crst_media WHERE type ='Service' ORDER BY time DESC");
    $getProds->execute();
    $getQry['dumps'] = $getProds->fetchAll();
    $app->response->headers->set('Content-Type', 'application/json');
    // $app->response->getBody($getQry);
    $getQry['response'] = $app->response->status(200);
    echo json_encode($getQry);
});


$app->get('/ewg',function() use($app){
    $DbClass = new DbClass();
    $getProds = $DbClass->db->prepare("SELECT * FROM crst_media WHERE type ='EWG' ORDER BY time DESC");
    $getProds->execute();
    $getQry['dumps'] = $getProds->fetchAll();
    $app->response->headers->set('Content-Type', 'application/json');
    // $app->response->getBody($getQry);
    $getQry['response'] = $app->response->status(200);
    echo json_encode($getQry);
});


/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
