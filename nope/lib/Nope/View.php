<?php

namespace Nope;

use Psr\Http\Message\ResponseInterface;


class View {

  private $templatesPath;
  private $adminTemplatesPath;

  function __construct($templatesPath, $adminTemplatesPath, $options = null) {
    $this->templatesPath = $templatesPath;
    $this->adminTemplatesPath = $adminTemplatesPath;
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
     $response->getBody()->write($this->fetch($this->templatesPath . $template, $data));
     return $response;
  }

  /**
   * Output rendered template
   *
   * @param ResponseInterface $response
   * @param  string $template Template pathname relative to templates directory
   * @param  array $data Associative array of template variables
   * @return ResponseInterface
   */
  public function adminRender(ResponseInterface $response, $template, $data = [])
  {
     $response->getBody()->write($this->fetch($this->adminTemplatesPath . $template, $data));
     return $response;
  }

}
