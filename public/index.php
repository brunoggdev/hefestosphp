<?php

/* ----------------------------------------------------------------------
 * Este arquivo faz toda a configuração necessária para a inicialização 
 * do app como buscar as constantes, carregar as funções auxiliares e 
 * registrar o autoloader adequado; Depois retorna uma instância do app.
---------------------------------------------------------------------- */
require '../system/Core/iniciar_app.php';

Hefestos\Core\App::processarRequisicao();