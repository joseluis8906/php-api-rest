<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->post('/publishers', function (Request $request, Response $response, array $args) {
    $publisherIn = $request->getParsedBody();
    $publisher =  new Publisher();
    $publisher->fromArray($publisherIn);
    $publisher->save();
    return $response->withHeader('Content-type', 'application/json')
            ->withStatus(201)
            ->write($publisher->toJSON());
});

$app->get('/publishers', function (Request $request, Response $response, array $args) {
    $publishers = PublisherQuery::create()->find();
    return $response->withHeader('Content-type', 'application/json')
            ->write($publishers->toJSON());
});

$app->get('/publishers/{id}', function (Request $request, Response $response, array $args) {
    $id = $args["id"];
    $publisher = PublisherQuery::create()->findPk($id);
    if(is_null($publisher)){
        return $response->withStatus(404);
    }
    return $response->withHeader('Content-type', 'application/json')
            ->write($publisher->toJSON());
});

$app->put('/publishers/{id}', function (Request $request, Response $response, array $args) {
    $id = $args["id"];
    $publisherIn = $request->getParsedBody();
    $publisher = PublisherQuery::create()->findPk($id);
    if(!is_null($publisher)){
        $publisherIn["Id"] = $publisher->getId();
        $publisher->fromArray($publisherIn);
        $publisher->save();
    }
    return $response;
});

$app->delete('/publishers/{id}', function (Request $request, Response $response, array $args) {
    $id = $args["id"];
    $publisher = PublisherQuery::create()->findPk($id);
    if(!is_null($publisher)){
        $publisher->delete();
    }
    return $response;
});

//books
$app->post('/publishers/{id}/books', function (Request $request, Response $response, array $args) {
    $id = $args["id"];
    $publisher = PublisherQuery::create()->findPk($id);
    if(is_null($publisher)){
        return $response->withStatus(404)
                ->write("Publisher not found");
    }
    $bookIn = $request->getParsedBody();
    $book = BookQuery::create()->filterByIsbn($bookIn["Isbn"])->findOne();
    if(!is_null($book)){
        $book->setPublisher($publisher);
        $book->save();
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(201)
                ->write($book->toJSON());
    }
    $book = new Book();
    $book->fromArray($bookIn);
    $book->setPublisher($publisher);
    $book->save();
    return $response->withHeader('Content-type', 'application/json')
            ->withStatus(201)
            ->write($book->toJSON());
});