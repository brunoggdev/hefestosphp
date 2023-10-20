<?php

namespace Hefestos\Ferramentas;

class Requisicao {

    private $curl;
    private $resposta;
    private $codigo;
    private $erros;
    
    public function __construct(private string $url_base = '') {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        if (ENVIROMENT == 'desenvolvimento') {
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
        }
    }


    public function get(string $endpoint) {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'GET');
        $this->requisitar($endpoint);
        return $this;
    }


    public function post(string $endpoint, $data) {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'POST');
        $this->requisitar($endpoint, $data);
        return $this;
    }


    public function put(string $endpoint, $data) {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        $this->requisitar($endpoint, $data);
        return $this;
    }


    public function patch(string $endpoint, $data) {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
        $this->requisitar($endpoint, $data);
        return $this;
    }


    public function delete(string $endpoint) {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        $this->requisitar($endpoint);
        return $this;
    }


    public function setopt(int $curl_opt, mixed $valor) {
        curl_setopt($this->curl, $curl_opt, $valor);
        return $this;
    }


    public function __toString()
    {
        return $this->resposta;
    }


    public function codigo(){
        return $this->codigo;
    }

    /**
     * Retorna a resposta da requisicao ou possíveis erros;
     * @param string $tipo_de_retorno Se o retorno deve ser string pura (json [padrão], html, etc), array ou objeto;
     * @author Brunoggdev
    */
    public function resposta(string $tipo_de_retorno = 'json'){
        return match ($tipo_de_retorno) {
            'json', 'html', 'string' => $this->resposta,
            'objeto' => json_decode($this->resposta),
            'array' => json_decode($this->resposta, true),
            default => null
        };;
    }
    
    
    public function erros(){
        return $this->erros;
    }


    private function requisitar($endpoint, $data = null) {
        
        $url = $this->url_base . $endpoint;
        
        curl_setopt($this->curl, CURLOPT_URL, $url);
        
        if ($data) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        }

        $resposta = curl_exec($this->curl);
        $this->resposta = $resposta ?: [curl_error($this->curl), curl_errno($this->curl)];
        $this->erros = curl_error($this->curl);
        $this->codigo = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
    
        curl_close($this->curl);
    }
}
