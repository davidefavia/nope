<?php

namespace Nope\Middleware;

class Install
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
      $installationPath = NOPE_ADMIN_ROUTE . '/install';
      $alreadyInstalled = \Nope::isAlredyInstalled();
      if(!$alreadyInstalled && '/'. $request->getUri()->getPath() != $installationPath) {
        return $response->withStatus(302)->withHeader('Location', $request->getUri()->getBasePath() . $installationPath);
      }
      $response = $next($request, $response);
      return $response;
    }
}
