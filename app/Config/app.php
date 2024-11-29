<?php
/* ----------------------------------------------------------------------
Neste você pode definir algumas constantes do seu projeto.
---------------------------------------------------------------------- */

return [
    // Se em "desenvolvimento" ou "producao"
    'AMBIENTE' => env('AMBIENTE', 'desenvolvimento'),

    // A url base do seu projeto. Ex: exemplo.com.br
    'URL_BASE' => 'localhost:8080', 

    // Identificador da versão do seu projeto, usado para cache busting
    'VERSAO_APP' => '0.0.0',

    // O timezone do seu projeto
    'TIMEZONE' => 'America/Fortaleza',

    // Mude para true para suspender acesso ao app com uma tela de manutenção
    'MANUTENCAO' => false,
];