<?php

namespace System\Ferramentas;

class Requisicao {

    private $curl;
    private $resposta;
    private $codigo;
    private $erros;
    
    public function __construct(private string $urlBase = '') {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
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


    public function setopt(int $curlOpt, mixed $valor) {
        curl_setopt($this->curl, $curlOpt, $valor);
        return $this;
    }


    public function __toString()
    {
        return $this->resposta;
    }


    public function codigo(){
        return $this->codigo;
    }


    public function resposta(){
        return $this->resposta;
    }
    
    
    public function erros(){
        return $this->erros;
    }


    private function requisitar($endpoint, $data = null) {
        
        $url = $this->urlBase . $endpoint;
        
        curl_setopt($this->curl, CURLOPT_URL, $url);
        
        if ($data) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        }

        $this->resposta = !curl_exec($this->curl) ? [curl_error($this->curl), curl_errno($this->curl)] : 'aaaaa';
        $this->erros = curl_error($this->curl);
        $this->codigo = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
    
        curl_close($this->curl);
    }
}