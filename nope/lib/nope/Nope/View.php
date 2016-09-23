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
   * @param  boolean $useGlobals Whether to use global transport system by \Nope::get()
   *
   * @return string
   */
  public function fetch($template, $data = [], $useGlobals = true)
  {
    if(file_exists($template)) {
      ob_start();
      if(is_array($data)) {
        foreach($data as $key => $value) {
          $$key = $value;
        }
      }
      if($useGlobals) {
        $globalData = \Nope::get();
        if(is_array($globalData)) {
          foreach($globalData as $key => $value) {
            $$key = $value;
          }
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
    $custom = \Nope::getCustom($key);
    $template = is_null($template)?$custom->properties->template:$template;
    if(is_null($template)) {
      $template = NOPE_LIB_VIEWS_PATH . 'setting/custom.php';
    }
    if(file_exists($template)) {
      if($custom) {
        ob_start();
        $setting = $custom;
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
