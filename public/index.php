<?php
/* ----------------------------------------------------------------------
 Indica este diretório. Caso altere seu nome de 'public', atualize esta
 constante também no arquivo da forja.
---------------------------------------------------------------------- */
const PASTA_PUBLIC = __DIR__;


/* ----------------------------------------------------------------------
 Configura todo o necessário para a inicialização do app como buscar as
 constantes, carregar as funções auxiliares e registrar o autoloader adequado;
---------------------------------------------------------------------- */
require __DIR__.'/../system/Core/iniciar_app.php';

Hefestos\Core\App::processarRequisicao();