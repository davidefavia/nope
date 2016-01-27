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

\Nope::registerSetting($nopeSettings);
