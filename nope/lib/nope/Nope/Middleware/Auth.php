<?php

namespace Nope\Middleware;

class Auth
{
    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
      $currentUser = \Nope\User::getAuthenticated();
      if(!$currentUser) {
        return $response->withStatus(401);
      }
      $response = $next($request, $response);
      return $response;
    }
}
