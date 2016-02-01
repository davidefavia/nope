<?php

namespace Nope;

use Psr\Http\Message\ResponseInterface;


class View {

  private $adminViewPathsList;
  private $themeViewPathsList;

  function __construct($adminViewPaths, $themeViewPaths, $options = null) {
    $this->adminViewPathsList = Utils::mergeDirectories($adminViewPaths);
    $this->themeViewPathsList = Utils::mergeDirectories($themeViewPaths);
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
    if(file_exists($template)) {
      ob_start();
      if(is_array($data)) {
        foreach($data as $key => $value) {
          $$key = $value;
        }
      }
      require_once $template;
      $renderedTemplate = ob_get_contents();
      ob_end_clean();
      return $renderedTemplate;
    } else {
      throw new \Exception("Error Processing Request", 1);
    }
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
    $response->getBody()->write($this->fetch($this->themeViewPathsList[$template], $data));
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
    $response->getBody()->write($this->fetch($this->adminViewPathsList[$template], $data));
    return $response;
  }

  static function renderCustomBox($key, $modelName, $template = null)
  {
    if(is_null($template)) {
      $template = NOPE_LIB_VIEWS_PATH . 'setting/box.php';
    }
    if(file_exists($template)) {
      $custom = \Nope::getCustom($key);
      if($custom) {
        ob_start();
        $fields = $custom->getFields();
        $ngModel = $modelName;
        include_once $template;
        $renderedTemplate = ob_get_contents();
        ob_end_clean();
        return $renderedTemplate;
      }
      return false;
    } else {
      throw new \Exception("Error Processing Request", 1);
    }
  }

}
