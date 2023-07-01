<?php

return <<<EOT
    {
        "name": "brunoggdev/hefestosphp",
        "type": "project",
        "description": "HefestosPHP - Framework MVC em português",
        "homepage": "https://github.com/brunoggdev/hefestosphp",
        "keywords": ["framework", "hefestos"],
        "license": "MIT",
        "autoload": {
            "psr-4": {
                "App\\\": "app/",
                "Hefestos\\\": "system/"
            }
        }
    }
    EOT;