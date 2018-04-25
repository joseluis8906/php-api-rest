<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Propel\Runtime\ActiveQuery\Criteria;

$app->post('/authors', function (Request $request, Response $response, array $args) {
    $authorIn = $request->getParsedBody();
    $author =  new Author();
    $author->fromArray($authorIn);
    $author->save();
    return $response->withHeader('Content-type', 'application/json')
            ->withStatus(201)
            ->write($author->toJSON());
});

$app->get('/authors', function (Request $request, Response $response, array $args) {
    $params = $request->getQueryParams();
    $firstNameIn = $params["firstName"];
    $lastNameIn = $params["lastName"];
    if(!is_null($firstNameIn)){
        $authors = AuthorQuery::create()
            ->setIgnoreCase(true)
            ->filterByFirstName('%' . $firstNameIn . '%', Criteria::LIKE)
            ->find(); 
        return $response->withHeader('Content-type', 'application/json')
            ->write($authors->toJSON());
    }
    if(!is_null($lastNameIn)){
        $authors = AuthorQuery::create()
            ->setIgnoreCase(true)
            ->filterByLastName('%' . $lastNameIn . '%', Criteria::LIKE)
            ->find(); 
        return $response->withHeader('Content-type', 'application/json')
            ->write($authors->toJSON());
    }
    $authors = AuthorQuery::create()->find();
    //return $response->write($firstNameIn);
    return $response->withHeader('Content-type', 'application/json')
            ->write($authors->toJSON());
});

$app->get('/authors/{id}', function (Request $request, Response $response, array $args) {
    $id = $args["id"];
    $author = AuthorQuery::create()->findPk($id);
    if(is_null($author)){
        return $response->withStatus(404);
    }
    return $response->withHeader('Content-type', 'application/json')
            ->write($author->toJSON());
});

$app->put('/authors/{id}', function (Request $request, Response $response, array $args) {
    $id = $args["id"];
    $authorIn = $request->getParsedBody();
    $author = AuthorQuery::create()->findPk($id);
    if(!is_null($author)){
        $authorIn["Id"] = $author->getId();
        $author->fromArray($authorIn);
        $author->save();
    }
    return $response;
});

$app->delete('/authors/{id}', function (Request $request, Response $response, array $args) {
    $id = $args["id"];
    $author = AuthorQuery::create()->findPk($id);
    if(!is_null($author)){
        $author->delete();
    }
    return $response;
});

//books
$app->post('/authors/{id}/books', function (Request $request, Response $response, array $args) {
    $id = $args["id"];
    $author = AuthorQuery::create()->findPk($id);
    if(is_null($author)){
        return $response->withStatus(404)
                ->write("Author not found");
    }
    $bookIn = $request->getParsedBody();
    $book = BookQuery::create()->filterByIsbn($bookIn["Isbn"])->findOne();
    if(!is_null($book)){
        $book->setAuthor($author);  
        $book->save();
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(201)
                ->write($book->toJSON());
    }
    $book = new Book();
    $book->fromArray($bookIn);
    $book->setAuthor($author);
    $book->save();
    return $response->withHeader('Content-type', 'application/json')
            ->withStatus(201)
            ->write($book->toJSON());
});