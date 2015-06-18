<?php 
return array (
  'phpSettings' => 
  array (
    'display_startup_errors' => '1',
    'display_errors' => '1',
  ),
  'bootstrap' => 
  array (
    'path' => 'D:\\www\\datouxia\\app/App.php',
    'class' => 'App',
  ),
  'resources' => 
  array (
    'frontController' => 
    array (
      'moduleDirectory' => 'D:\\www\\datouxia\\app/modules',
      'moduleControllerDirectoryName' => 'controllers',
      'defaultModule' => 'front',
      'baseUrl' => '',
      'params' => 
      array (
        'displayExceptions' => '1',
        'noViewRenderer' => '0',
        'noErrorHandler' => '0',
        'prefixDefaultModule' => '',
      ),
    ),
    'layout' => 
    array (
      'layoutPath' => 'D:\\www\\datouxia\\app/templates/scripts/',
    ),
  ),
);