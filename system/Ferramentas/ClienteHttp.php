<?php

namespace Hefestos\Ferramentas;

/**
 * Um wrapper para a o cURL do php com uma api mais intuitiva.
 */
class ClienteHttp
{

    private $curl;
    private $resposta;
    private $status;
    private $erros;
    private $url_base;
    private $headers;


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


    public function get(string $endpoint)
    {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'GET');
        $this->requisitar($endpoint);
        return $this;
    }


    public function post(string $endpoint, $data)
    {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'POST');
        $this->requisitar($endpoint, $data);
        return $this;
    }


    public function put(string $endpoint, $data)
    {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        $this->requisitar($endpoint, $data);
        return $this;
    }


    public function patch(string $endpoint, $data)
    {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
        $this->requisitar($endpoint, $data);
        return $this;
    }


    public function delete(string $endpoint)
    {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        $this->requisitar($endpoint);
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


    private function requisitar($endpoint, $data = null)
    {

        $url = $this->url_base . $endpoint;

        curl_setopt($this->curl, CURLOPT_URL, $url);

        if ($data) {
            if (is_string($data)) {
                $this->adicionarHeaders([
                    'Content-Type' => 'application/json',
                    'Content-Length' => strlen($data)
                ]);
                curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
            }

            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        }

        $resposta = curl_exec($this->curl);
        $this->resposta = $resposta ?: curl_error($this->curl);
        $this->erros = [curl_error($this->curl), curl_errno($this->curl)];
        $this->status = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        curl_close($this->curl);
    }
}
