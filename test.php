<?php
    
    require 'vendor/autoload.php';
    
    
    $query = new \Protoqol\Quark\Config\DatabaseAccessor();
    
    echo $query->getInstance();
