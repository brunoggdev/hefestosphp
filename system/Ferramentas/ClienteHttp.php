<?php

namespace Hefestos\Ferramentas;

/**
 * Um wrapper para a o cURL do php com uma api mais intuitiva.
 */
class ClienteHttp
{

    private \CurlHandle|false $curl;
    private string $resposta;
    private int $status;
    private array $erros;
    private string $url_base;
    private array $headers = [];


    /**
     * Instancia um cliente para requisições HTTP.
     *
     * @param array $config Configurações opcionais para a instância do cliente HTTP.
     *                      - 'url_base' (string): Uma URL base para as requisições.
     *                      - 'headers' (array): Cabeçalhos HTTP no formato chave-valor.
     *                      - 'verificar_ssl' (bool|null): Se deve verificar SSL. 
     *                         Se for null, usa a configuração baseada no ambiente. Padrão é null.
     *
     * @throws \Exception Se o cURL não estiver habilitado no servidor.
     */
    public function __construct(array $config = ['url_base' => '', 'headers' => [], 'verificar_ssl' => null])
    {
        if (!function_exists('curl_version')) {
            throw new \Exception('Parece que seu servidor não possui cURL habilitado.');
        }

        $this->url_base = $config['url_base'];
        $this->adicionarHeaders($config['headers']);
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, $config['verificar_ssl'] ??= AMBIENTE == 'desenvolvimento');
    }


    public function get(string $url)
    {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'GET');
        $this->requisitar($url);
        return $this;
    }

    /**
     * @param string $url URL do endpoint
     * @param array $options Mesmas opções do método post():
     *    - 'body' => string
     *    - 'json' => array
     *    - 'form_params' => array
     *    - 'multipart' => array
     *    - 'headers' => array
     */
    public function post(string $url, array $dados)
    {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'POST');
        $this->requisitar($url, $dados);
        return $this;
    }


    public function put(string $url, array $dados)
    {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        $this->requisitar($url, $dados);
        return $this;
    }


    public function patch(string $url, array $dados)
    {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
        $this->requisitar($url, $dados);
        return $this;
    }


    public function delete(string $url)
    {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        $this->requisitar($url);
        return $this;
    }


    /**
     * Adiciona cabeçalhos HTTP.
     *
     * @param array $headers Cabeçalhos HTTP no formato chave-valor.
     * @author Brunoggdev
     */
    public function adicionarHeaders(array $headers)
    {
        foreach ($headers as $chave => $valor) {
            $this->headers[] = "$chave: $valor";
        }

        return $this;
    }


    public function setOpt(int $curl_opt, mixed $valor)
    {
        curl_setopt($this->curl, $curl_opt, $valor);
        return $this;
    }


    public function __toString()
    {
        return $this->resposta;
    }



    /**
     * Retorna o código de status da requisição.
     * @author Brunoggdev
     */
    public function status()
    {
        return $this->status;
    }



    /**
     * Retorna a resposta da requisicao ou possíveis erros;
     * @param string $tipo_de_retorno Se o retorno deve ser string pura (json [padrão], html, etc), array ou objeto;
     * @author Brunoggdev
     */
    public function resposta(string $tipo_de_retorno = 'json')
    {
        return match ($tipo_de_retorno) {
            'json', 'html', 'string' => $this->resposta,
            'objeto' => json_decode($this->resposta),
            'array' => json_decode($this->resposta, true),
            default => null
        };
    }


    public function erros()
    {
        return $this->erros;
    }

    private function prepararMultipart(array $parts)
    {
        $boundary = '--------------------------' . microtime(true);
        $this->adicionarHeaders([
            'Content-Type' => 'multipart/form-data; boundary=' . $boundary
        ]);

        $body = '';

        foreach ($parts as $part) {
            $body .= "--$boundary\r\n";

            if (isset($part['filename'])) {
                $contents = $part['contents'];
                if (is_resource($contents)) {
                    $contents = stream_get_contents($contents);
                }

                $body .= "Content-Disposition: form-data; name=\"{$part['name']}\"; filename=\"{$part['filename']}\"\r\n";
                $body .= "Content-Type: {" . $part['headers']['Content-Type'] ?? 'application/octet-stream' . "}\r\n\r\n";
                $body .= $contents . "\r\n";
            } else {
                $body .= "Content-Disposition: form-data; name=\"{$part['name']}\"\r\n\r\n";
                $body .= $part['contents'] . "\r\n";
            }
        }

        $body .= "--$boundary--\r\n";

        return $body;
    }

    private function prepararDados(array $dados): null|string|array
    {
        if (isset($dados['headers'])) {
            $this->adicionarHeaders($dados['headers']);
        }
        
        if (isset($dados['json'])) {
            $this->adicionarHeaders([
                'Content-Type' => 'application/json',
            ]);
            return json_encode($dados['json']);
        }

        if (isset($dados['form_params'])) {
            return $dados['form_params'];
        }

        if (isset($dados['body'])) {
            return $dados['body'];
        }

        if (isset($dados['multipart'])) {
            return $this->prepararMultipart($dados['multipart']);
        }

        return null;
    }

    /**
     * Retorna um array com informações de debug sobre a requisição feita.
     */
    public function debug()
    {
        return [
            'url' => curl_getinfo($this->curl, CURLINFO_EFFECTIVE_URL),
            'status' => $this->status,
            'headers' => $this->headers,
            'resposta' => $this->resposta,
            'erros' => $this->erros
        ];
    }


    private function requisitar(string $url, ?array $dados = null)
    {
        if (!str_starts_with($url, 'http')) {
            $url = $this->url_base . $url;
        }

        if ($dados) {
            $dados = $this->prepararDados($dados);
        }

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $dados);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);

        $resposta = curl_exec($this->curl);
        $this->resposta = $resposta ?: curl_error($this->curl);
        $this->erros = [curl_error($this->curl), curl_errno($this->curl)];
        $this->status = curl_getinfo($this->curl, CURLINFO_RESPONSE_CODE);

        curl_close($this->curl);
    }
}
