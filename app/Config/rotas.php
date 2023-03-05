<?php
# ----------------------------------------------------------------------
# Configure aqui suas rotas. Um objeto "$rota" está automaticamente
# disponível neste arquivo e deve ser utilizado da seguinte maneira:
# $rota->metodoRequisicao('uri', 'Controller:metodo');
# ----------------------------------------------------------------------

$rota->get('/', 'PaginasController:index');