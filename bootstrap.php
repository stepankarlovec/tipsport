<?php
require_once 'composer/vendor/autoload.php';

spl_autoload_register(function ($className){
    require 'Class/' . $className . '.php';
});
