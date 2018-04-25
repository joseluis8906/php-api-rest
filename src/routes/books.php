<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->post('/books', function (Request $request, Response $response, array $args) {
    $bookIn = $request->getParsedBody();
    
    try{
        $book =  new Book();
        $book->fromArray($bookIn);
        $book->save();
    }
    catch(Exception $e){
        return $response->withStatus(201);
    }
    
    return $response->withHeader('Content-type', 'application/json')
            ->withStatus(201)
            ->write($book->toJSON());
});

$app->get('/books', function (Request $request, Response $response, array $args) {
    $books = BookQuery::create()->find();
    return $response->withHeader('Content-type', 'application/json')
                    ->write($books->toJSON());
});

$app->get('/books/{id}', function (Request $request, Response $response, array $args) {
    $id = $args["id"];
    $book = BookQuery::create()->findPk($id);
    if(is_null($book)){
        return $response->withStatus(404);
    }
    return $response->withHeader('Content-type', 'application/json')
                    ->write($book->toJSON());
});

$app->put('/books/{id}', function (Request $request, Response $response, array $args) {
    $id = $args["id"];
    $bookIn = $request->getParsedBody();
    $book = BookQuery::create()->findPk($id);
    if(!is_null($book)){
        try{
            $bookIn["Id"] = $book->getId();
            $book->fromArray($bookIn);
            $book->save();
        }catch (Exception $e){
            return $response->withStatus(404);        
        }
        
    }
    return $response;
});

$app->delete('/books/{id}', function (Request $request, Response $response, array $args) {
    $id = $args["id"];
    $book = BookQuery::create()->findPk($id);
    if(!is_null($book)){
        $book->delete();
    }
    return $response;
});