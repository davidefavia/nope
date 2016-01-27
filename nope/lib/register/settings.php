<?php

namespace Nope\Platform;

$nopeSettings = new Setting('nope', [
  'label' => 'Platform',
  'description' => 'General settings',
  'role' => 'admin'
]);
/*
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
*/

/*$nopeSettings->addField(new Setting\Field('table1', [
  'label' => 'Table',
  'type' => 'table',
  'multiple' => true
]));
*/


/*$nopeSettings->addField(new Setting\Field('keyvalue1', [
  'label' => 'Key value',
  'type' => 'pair',
  'multiple' => true
]));
*/


$nopeSettings->addField(new Setting\Field('cover', [
  'label' => 'Website cover',
  'type' => 'model',
  'model' => '\Nope\Media',
  'attributes' => [
    'href' => '#/media?mimetype=image/',
    'multiple' => false,
    'label' => 'Add website cover',
    'preview' => 'icon'
  ]
]));

/*
$testGroup = new Setting\Group('testgroup', [
  'label' => 'Test group!',
  'multiple' => true
]);
$testGroup->addField(new Setting\Field('test1', [
  'label' => 'Lorem ipsum',
  'attributes' => [
    'placeholder' => 'Your test'
  ]
]));
$testGroup->addField(new Setting\Field('test2', [
  'label' => 'Website cover TEST',
  'type' => 'model',
  'model' => '\Nope\Media',
  'attributes' => [
    'href' => '#/media',
    'multiple' => false,
    'label' => 'Add website cover for testing',
    'preview' => 'icon'
  ]
]));
$nopeSettings->addGroup($testGroup);
*/


\Nope::registerSetting($nopeSettings);
