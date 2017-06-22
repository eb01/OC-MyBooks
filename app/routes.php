<?php

// Home page
$app->get('/', function () use ($app) {
    $books = $app['dao.book']->findAll();
    return $app['twig']->render('index.html.twig', array('books' => $books));
});