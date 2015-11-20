<?php

namespace Nope;

use Psr\Http\Message\ResponseInterface;


class View {

  private $list;

  function __construct($list, $options = null) {
    $this->list = Utils::mergeDirectories($list);
    #var_dump($this->list);
  }

  /**
   * Fetch rendered template
   *
   * @param  string $template Template pathname relative to templates directory
   * @param  array  $data     Associative array of template variables
   *
   * @return string
   */
  public function fetch($template, $data = [])
  {
    ob_start();
    foreach($data as $key => $value) {
      $$key = $value;
    }
    require_once $template;
    $renderedTemplate = ob_get_contents();
    ob_end_clean();
    return $renderedTemplate;
  }

  /**
   * Output rendered template
   *
   * @param ResponseInterface $response
   * @param  string $template Template pathname relative to templates directory
   * @param  array $data Associative array of template variables
   * @return ResponseInterface
   */
  public function render(ResponseInterface $response, $template, $data = [])
  {
    $response->getBody()->write($this->fetch($this->list[$template], $data));
    return $response;
  }

}
