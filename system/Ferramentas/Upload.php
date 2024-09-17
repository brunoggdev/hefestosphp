<?php

namespace Hefestos\Ferramentas;

use Exception;

/**
 * Ferramenta para facilitar o upload de arquivos
 * @throws Exception
 * @author Brunoggdev
 */
class Upload
{
    protected string $diretorio;
    protected array $arquivo;
    protected array $arquivos;
    protected array $erros = [];

    /**
     * Define o diretório para upload (o diretório será criado se não existir)
     * @param string $diretorio Caminho relativo para o diretório (a partir da pasta raiz que será concatenada automaticamente)
     * @author Brunoggdev
     */
    public function paraDiretorio(string $diretorio): self
    {
        $diretorio = PASTA_RAIZ . $diretorio;

        if (!is_dir($diretorio)) {
            if (!mkdir($diretorio, 0755, true)) {
                throw new Exception('Não foi possível criar o diretório de upload.');
            }
        }

        $this->diretorio = rtrim($diretorio, '/') . '/';
        return $this;
    }



    /**
     * Define o arquivo a ser processado (via $_FILES)
     * @param string $campo_arquivo Nome do campo do arquivo no formulário
     * @author Brunoggdev
     */
    public static function arquivo(string $campo_arquivo): self
    {
        $upload = new self();
        $arquivo = $_FILES[$campo_arquivo] ?? null;

        if (!$arquivo || !isset($arquivo['name']) || !isset($arquivo['tmp_name']) || !isset($arquivo['error'])) {
            throw new Exception('Arquivo inválido.');
        }

        $upload->arquivo = $arquivo;
        return $upload;
    }

    /**
     * Similiar ao método arquivo, mas para vários arquivos simultaneamente;
     * Define os arquivsos a serem processados (via $_FILES)
     * @param string $campo_arquivo Nome do campo dos arquivos no formulário
     * @author Brunoggdev
     */
    public static function arquivos(string $campo_arquivos): self
    {
        $upload = new self();
        $arquivos = $_FILES[$campo_arquivos] ?? null;

        if (!$arquivos || !isset($arquivos['name'])) {
            throw new Exception('Arquivo(s) inválido(s).');
        }

        $upload->arquivos = $upload->organizarArquivos($arquivos);
        return $upload;
    }


    /**
     * Organiza o array de arquivos para facilitar o processamento
     * @param array $arquivos
     * @return array
     */
    protected function organizarArquivos(array $arquivos): array
    {
        $organizados = [];
        $total = count($arquivos['name']);

        for ($i = 0; $i < $total; $i++) {
            $organizados[] = [
                'name' => $arquivos['name'][$i],
                'type' => $arquivos['type'][$i],
                'tmp_name' => $arquivos['tmp_name'][$i],
                'error' => $arquivos['error'][$i],
                'size' => $arquivos['size'][$i],
            ];
        }

        return $organizados;
    }



    /**
     * Retorna o conteúdo em texto do arquivo  
     * @throws Exception
     * @author Brunoggdev
     */
    public function conteudo()
    {
        $this->verificarErros();

        if (!is_file($this->arquivo['tmp_name'])) {
            throw new Exception('Arquivo temporário não encontrado.');
        }

        // Retorna o conteúdo do arquivo como uma string
        return file_get_contents($this->arquivo['tmp_name']);
    }


    /**
     * Retorna o caminho temporário do arquivo no servidor
     * @throws Exception
     * @author Brunoggdev
     */
    public function caminhoTemporario()
    {
        $this->verificarErros();

        if (!is_file($this->arquivo['tmp_name'])) {
            throw new Exception('Arquivo temporário não encontrado.');
        }

        // Retorna o conteúdo do arquivo como uma string
        return $this->arquivo['tmp_name'];
    }
 
 
    /**
     * Retorna os caminhos temporários dos arquivos no servidor
     * @throws Exception
     * @author Brunoggdev
     */
    public function caminhosTemporarios()
    {
        $caminhos = [];
        
        foreach ($this->arquivos as $arquivo) {
            $this->arquivo = $arquivo;
            $caminhos[] = $this->caminhoTemporario();
        }

        return $caminhos;
    }



    /**
     * Similiar ao método conteudo, mas para vários arquivos simultaneamente;
     * Retorna os conteúdos de todos os arquivos como um array
     * @throws Exception
     * @author Brunoggdev
     */
    public function conteudos(): array
    {
        foreach ($this->arquivos as $arquivo) {
            $this->arquivo = $arquivo;
            $this->verificarErros();
            $conteudos[] = $this->conteudo();
        }

        return $conteudos;
    }




    /**
     * Salva o arquivo no servidor
     * @param string|null $novo_nome Nome desejado para o arquivo (opcional)
     * @return string Caminho completo do arquivo salvo
     * @throws Exception
     * @author Brunoggdev
     */
    public function salvar(?string $novo_nome = null): string
    {
        $this->verificarErros();

        if (!$this->diretorio) {
            $this->paraDiretorio(PASTA_RAIZ . '/uploads/');
        }

        // Gera um nome único se nenhum for fornecido
        $novo_nome = $novo_nome ?? $this->gerarNomeUnico();

        // Caminho completo do arquivo
        $caminho_completo = $this->diretorio . $novo_nome;

        if (!move_uploaded_file($this->arquivo['tmp_name'], $caminho_completo)) {
            throw new Exception('Falha ao salvar o arquivo no diretório de destino.');
        }

        return $caminho_completo;
    }


    /**
     * 
     */
    protected function salvarMuitos()
    {
        $caminhos = [];

        foreach ($this->arquivos as $arquivo) {
            $this->arquivo = $arquivo;
            $caminhos[] = $this->salvar();
        }

        return $caminhos;
    }



    /**
     * Verifica os erros no upload
     * @throws Exception
     * @author Brunoggdev
     */
    protected function verificarErros(): void
    {
        if ($this->arquivo['error'] !== UPLOAD_ERR_OK) {
            $this->erros[] = $this->traduzirErro($this->arquivo['error']);
            throw new Exception(implode('; ', $this->erros));
        }
    }



    /**
     * Retorna o nome original do arquivo enviado pelo cliente
     * @author Brunoggdev
     */
    public function nomeOriginal(): ?string
    {
        return $this->arquivo['name'] ?? null;
    }


    /**
     * Retorna a extensão do arquivo original
     * @author Brunoggdev
     */
    public function extensaoOriginal(): ?string
    {
        return pathinfo($this->arquivo['name'], PATHINFO_EXTENSION);
    }



    /**
     * Gera um nome único baseado no arquivo original
     * @author Brunoggdev
     */
    protected function gerarNomeUnico(): string
    {
        return uniqid(more_entropy: true) . '.' . $this->extensaoOriginal();
    }



    /**
     * Traduz os códigos de erro do upload
     * @author Brunoggdev
     */
    protected function traduzirErro(int $codigoErro): string
    {
        return match ($codigoErro) {
            UPLOAD_ERR_INI_SIZE => 'O arquivo excede o tamanho máximo permitido.',
            UPLOAD_ERR_FORM_SIZE => 'O arquivo excede o tamanho máximo definido no formulário.',
            UPLOAD_ERR_PARTIAL => 'O upload foi iniciado mas falhou porque o arquivo foi apenas parcialmente enviado.',
            UPLOAD_ERR_NO_FILE => 'Nenhum arquivo foi recebido.',
            default => 'Erro desconhecido ao realizar o upload do arquivo.',
        };
    }
}
