<?php
    
    require 'vendor/autoload.php';
    
    
    $query = new \Protoqol\Quark\DatabaseAccessor();
    
    echo $query->getInstance();
