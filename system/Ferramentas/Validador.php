<?php

namespace Hefestos\Ferramentas;

class Validador
{
    protected array $erros = [];
    protected array $mensagens = [];

    /**
     * Valida um conjunto de dados baseado nas regras fornecidas
     * @param array $dados Dados a serem validados
     * @param array $regras Regras de validação, no formato [campo => [regras]]
     * @param array $mensagens Mensagens de erros customizadas, no formato [campo => [regra => mensagem]]
     * @return bool Retorna true se todas as validações passarem, false caso contrário
     * @author Brunoggdev
     */
    public function validar(array $dados, array $regras, $mensagens = []): bool
    {
        $this->mensagens = $mensagens;

        foreach ($regras as $campo => $regras_campo) {

            if (is_string($regras_campo)) {
                $regras_campo = explode('|', $regras_campo);
            }

            $valor = $dados[$campo] ?? null;

            if ($valor === null) {
                if (in_array('obrigatorio', $regras_campo)) {
                    $this->erros[$campo][] = $this->mensagens[$campo]['obrigatorio'] ?? "O campo é obrigatório.";
                }

                continue;
            }

            foreach ($regras_campo as $regra) {

                [$metodo_validacao, $parametros] = array_pad(explode(':', $regra), 2, null);

                $this->tentarValidar($campo, $metodo_validacao, $valor, $parametros);
            }
        }

        return empty($this->erros);
    }



    /**
     * Retorna todos os erros de validação
     * @return array
     * @author Brunoggdev
     */
    public function erros(): array
    {
        return $this->erros;
    }


    /**
     * Tenta validar um campo, caso não tenha sucesso adiciona o erro ao campo
     * @author Brunoggdev
     */
    public function tentarValidar(string $campo, string $metodo_validacao, mixed $valor, ?string $parametros = null): void
    {
        if (!method_exists($this, $metodo_validacao)) {
            throw new \Exception("A regra de validação '{$metodo_validacao}' não existe.");
        }

        try {
            $this->$metodo_validacao($campo, $valor, $parametros);
        } catch (\Throwable) {
            $this->erros[$campo][] = "O campo parece inválido.";
        }
    }



    /**
     * Valida se o campo é obrigatório
     * @author Brunoggdev
     */
    protected function obrigatorio(string $campo, mixed $valor): bool
    {
        if (empty($valor) && $valor !== '0') {
            $this->erros[$campo][] = $this->mensagens[$campo]['obrigatorio'] ?? "O campo é obrigatório.";
            return false;
        }

        return true;
    }



    /**
     * Valida se o campo tem um valor mínimo de caracteres
     * @author Brunoggdev
     */
    protected function min(string $campo, mixed $valor, int $min): bool
    {
        if (!is_string($valor) && strlen($valor) < $min) {
            $this->erros[$campo][] = $this->mensagens[$campo]['min'] ?? "O campo deve ter pelo menos {$min} caracteres.";
            return false;
        }

        return true;
    }



    /**
     * Valida se o campo tem um valor máximo de caracteres
     * @author Brunoggdev
     */
    protected function max(string $campo, $valor, int $max): bool
    {
        if (!is_string($valor) && strlen($valor) > $max) {
            $this->erros[$campo][] = $this->mensagens[$campo]['max'] ?? "O campo não pode ter mais que {$max} caracteres.";
            return false;
        }

        return true;
    }



    /**
     * Valida se o campo é um e-mail válido
     * @author Brunoggdev
     */
    protected function email(string $campo, mixed $valor): bool
    {
        if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
            $this->erros[$campo][] = $this->mensagens[$campo]['email'] ?? "O campo deve ser um e-mail válido.";
            return false;
        }

        return true;
    }



    /**
     * Valida se o campo tem um valor numérico
     * @author Brunoggdev
     */
    protected function numerico(string $campo, mixed $valor): bool
    {
        if (!is_numeric($valor)) {
            $this->erros[$campo][] = $this->mensagens[$campo]['numerico'] ?? "O campo deve ser numérico.";
            return false;
        }

        return true;
    }



    /**
     * Verifica se o valor do campo já existe em uma tabela (se não for informada uma coluna, usa o mesmo nome do campo)
     * @author Brunoggdev
     */
    public function unico(string $campo, mixed $valor, string $tabela_coluna): bool
    {
        [$tabela, $coluna] = array_pad(explode(',', $tabela_coluna), 2, $campo);

        try {
            $campo_existe = db()->tabela($tabela)->primeiroOnde([$coluna => $valor]);
        } catch (\Throwable) {
            return true;
        }

        if ($campo_existe) {
            $this->erros[$campo][] = $this->mensagens[$campo]['unico'] ?? "O campo já existe.";
            return false;
        }

        return true;
    }


    /**
     * Valida se o campo é um JSON válido
     * @author Brunoggdev
     */
    public function json(string $campo, mixed $valor): bool
    {
        json_decode($valor);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->erros[$campo][] = $this->mensagens[$campo]['json'] ?? "O campo não é um JSON válido.";
            return false;
        }

        return true;
    }


    /**
     * Valida se o campo é um booleano
     * @author Brunoggdev
     */
    protected function bool(string $campo, mixed $valor): bool
    {
        if (!is_bool(filter_var($valor, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
            $this->erros[$campo][] = $this->mensagens[$campo]['bool'] ?? "O campo deve ser verdadeiro ou falso.";
            return false;
        }

        return true;
    }

    

    /**
     * Valida se o campo é uma URL válida
     * @author Brunoggdev
     */
    protected function url(string $campo, mixed $valor): bool
    {
        if (!filter_var($valor, FILTER_VALIDATE_URL)) {
            $this->erros[$campo][] = $this->mensagens[$campo]['url'] ?? "O campo deve ser uma URL válida.";
            return false;
        }

        return true;
    }
}
