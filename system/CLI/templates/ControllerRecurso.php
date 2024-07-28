<?php

return <<<EOT
    <?php

    namespace App\Controllers{namespace};

    use Hefestos\Core\Controller;

    class {nome} extends Controller
    {
        /**
        * Retorna uma lista deste recurso.
        */
        public function index()
        {
            // return view();
            // ou
            // return json();
            // ou
            // return redirecionar();
            // ou ainda
            // abortar();
        }


        /**
        * Retorna o formulário necessário para criar mais um recurso.
        */
        public function criar()
        {
            //
        }


        /**
        * Armeza o novo recurso no banco de dados.
        */
        public function armazenar()
        {
            //
        }


        /**
        * Retorna as informações de um recurso especifico.
        */
        public function buscar(\$id)
        {
            //
        }


        /**
        * Retorna o formulário necessário para editar um recurso.
        */
        public function editar(\$id)
        {
            //
        }


        /**
        * Atualiza um recurso no banco de dados.
        */
        public function atualizar(\$id)
        {
            //
        }


        /**
        * Deleta um recurso do banco de dados.
        */
        public function deletar(\$id)
        {
            //
        }
    }
    EOT;