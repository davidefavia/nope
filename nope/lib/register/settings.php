<?php

namespace Nope\Platform;

$nopeSettings = new Setting('nope', [
  'label' => '<i class="fa fa-gears"></i> Platform',
  'description' => 'General settings',
  'role' => 'admin'
]);
$nopeSettings->addField(new Setting\Field('headline', [
  'label' => 'Headline',
  'attributes' => [
    'placeholder' => 'Platform headline'
  ]
]));
$nopeSettings->addField(new Setting\Field('description', [
  'label' => 'Description',
  'description' => 'Few words about platform goal.',
  'type' => 'text',
  'attributes' => [
    'placeholder' => 'Platform description',
    'rows' => 5
  ]
]));
$nopeSettings->addField(new Setting\Field('homepage', [
  'label' => 'Homepage',
  'description' => 'Choose which content will be your homepage.',
  'type' => 'model',
  'model' => '\Nope\Page',
  'attributes' => [
    'href' => '#/content/page',
    'preview' => 'icon',
    'label' => 'Add homepage'
  ]
]));

\Nope::registerSetting($nopeSettings);
