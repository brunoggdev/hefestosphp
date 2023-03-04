<?php
if (isset($argc)) {

    match ($argv[1]) {
        'serve' => exec('php -S localhost:8080 -t public')
    };
    
}
?>