<?php
/**
 * Based on Slim best practice
 * @url: https://www.slimframework.com/docs/cookbook/route-patterns.html
 *
 * Class RemoveTrailingSlashMiddleware
 * @package App\Middleware
 */

namespace App\Middleware;

class RemoveTrailingSlashMiddleware
{
  /**
   * @param \Slim\Http\Request $request
   * @param \Slim\Http\Response $response
   * @param $next
   *
   * @return mixed
   */
  public function __invoke($request, $response, $next)
  {
    $uri = $request->getUri();
    $path = $uri->getPath();
    if ($path != '/' && substr($path, -1) == '/') {
      // permanently redirect paths with a trailing slash
      // to their non-trailing counterpart
      $uri = $uri->withPath(substr($path, 0, -1));

      if($request->getMethod() == 'GET') {
        return $response->withRedirect((string)$uri, 301);
      }
      else {
        return $next($request->withUri($uri), $response);
      }
    }

    return $next($request, $response);
  }

}
