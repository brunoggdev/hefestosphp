<?php

return <<<EOT
    <?php

    namespace App\Entidades{namespace};

    use Hefestos\Core\Entidade;

    class {nome} implements Entidade
    {
        public function chavePrimaria(): string
        {
            return 'id';
        }
        
        public function paraArray(): array
        {
            return [
                'id' => \$this->id,
            ];
        }
    }
    EOT;