<?php

namespace Nope;

class Theme {

  public $name = 'Default theme';

  function __construct() {
    // register things...
    $settings = new \Nope\Platform\Setting('theme', [
      'label' => '<i class="fa fa-leaf"></i> Theme',
      'description' => 'Theme settings',
      'role' => 'admin'
    ]);
    $settings->addField(new \Nope\Platform\Setting\Field('cover', [
      'label' => 'Cover image',
      'type' => 'model',
      'model' => '\Nope\Media',
      'attributes' => [
        'href' => '#/media?mimetype=image/',
        'preview' => 'icon',
        'label' => 'Add cover image',
        'multiple' => false
      ]
    ]));

    \Nope::registerSetting($settings);
  }

}

?>
