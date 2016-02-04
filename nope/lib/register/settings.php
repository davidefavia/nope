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

$nopeSettings->addField(new Setting\Field('m', [
  'label' => 'Media',
  'description' => 'Choose which content will be your homepage.',
  'type' => 'model',
  'model' => '\Nope\Media',
  'attributes' => [
    'href' => '#/media',
    'label' => 'Add media',
    'template' => 'media'
  ]
]));

$nopeSettings->addField(new Setting\Field('uu', [
  'label' => 'User',
  'type' => 'model',
  'model' => '\Nope\User',
  'attributes' => [
    'href' => '#/user',
    'label' => 'Add user',
    'template' => 'user',
    'multiple' => true
  ]
]));

$nopeSettings->addField(new Setting\Field('gg', [
  'label' => 'User',
  'type' => 'model',
  'model' => '\Nope\Gallery',
  'attributes' => [
    'href' => '#/gallery',
    'label' => 'Add user',
    'template' => 'gallery',
    'multiple' => true
  ]
]));

\Nope::registerSetting($nopeSettings);
