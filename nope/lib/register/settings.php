<?php

namespace Nope\Platform;

$nopeSettings = new Setting('nope', [
  'label' => 'Platform',
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
$nopeSettings->addField(new Setting\Field('cover', [
  'label' => 'Website cover',
  'type' => 'model',
  'model' => '\Nope\Media',
  'attributes' => [
    'href' => '#/media',
    'multiple' => false,
    'label' => 'Add website cover',
    'preview' => 'icon'
  ]
]));


\Nope::registerSetting($nopeSettings);
