<?php

use Hefestos\Core\App;

try {
    require '../system/Core/iniciar_app.php';
} catch (\Throwable $erro) {
    //throw $th;
}

App::processarRequisicao();