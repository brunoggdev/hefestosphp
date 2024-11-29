<?php

/* ------------------------------------------------------------------------
 Configura todo o necessário para iniciar o app, como buscar as constantes, 
 carregar as funções auxiliares e registrar o autoloader adequado;
------------------------------------------------------------------------ */
require __DIR__.'/../system/iniciar_app.php';

Hefestos\Core\App::processarRequisicao();