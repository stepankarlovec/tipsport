<?php
namespace Stepankarlovec\Tipsport;

use CurlHandle;

class Request
{
    private CurlHandle $curl;
    public string $url;
    public string $type;
    public string|array $fields;
    public array $headers;

    public bool $returnHeader;

    /**
     * Constructor for the requests, it automaticly initiates the request, but doesn't execute. For execution call $request->execute();
     *
     * @param              $url     String URL
     * @param              $type    String Type (POST, GET)
     * @param string|array $fields  array Request fields
     * @param              $headers array Request headers
     * @param bool         $returnHeader
     */
    public function __construct(string $url, string $type = "GET", string|array $fields = [], array $headers = [], bool $returnHeader = false)
    {
        $this->url = $url;
        $this->type = $type;
        $this->fields = $fields;
        $this->headers = $headers;
        $this->returnHeader = $returnHeader;

        $this->startRequest();
        $this->initType();
        $this->initHeaders();
    }


    private function startRequest(): void
    {
        $this->curl = curl_init($this->url);
    }

    private function initType(): void
    {
        if ($this->type == "POST" || $this->type == "post" || $this->type == "p") {
            curl_setopt($this->curl, CURLOPT_HEADER, $this->returnHeader?1:0);
            curl_setopt($this->curl, CURLOPT_POST, 1);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->fields);
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        } else if ($this->type == "GET" || $this->type == "get" || $this->type == "g") {
            curl_setopt($this->curl, CURLOPT_HEADER, $this->returnHeader?1:0);
            curl_setopt($this->curl, CURLOPT_POST, 0);
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        }
    }
    private function initHeaders(): void
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);

    }

    public function execute(): String
    {
        $res = curl_exec($this->curl);
        curl_close($this->curl);
        return $res;
    }

    public function executeAndParse(): mixed
    {
        $res = json_decode(curl_exec($this->curl),true);
        curl_close($this->curl);
        return $res;
    }
}