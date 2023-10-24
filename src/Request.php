<?php
/**
 * Request Class for Tipsport API communication
 * https://github.com/stepankarlovec/tipsport
 *
 * feel free to contribute <3
 */

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
     * @param string|array $fields  Array Request fields
     * @param              $headers Array Request headers
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


    /**
     * Initializes the cURL
     * @return void
     */
    private function startRequest(): void
    {
        $this->curl = curl_init($this->url);
    }

    /**
     * Sets cURL options based on the HTTP request type
     * @return void
     */
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

    /**
     * Initializes HTTP headers
     * @return void
     */
    private function initHeaders(): void
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);

    }

    /**
     * Executes the cURL
     * @return String
     */
    public function execute(): String
    {
        $res = curl_exec($this->curl);
        curl_close($this->curl);
        return $res;
    }

    /**
     * Executes and parses the JSON result
     * @return mixed
     */
    public function executeAndParse(): mixed
    {
        $res = json_decode(curl_exec($this->curl),true);
        curl_close($this->curl);
        return $res;
    }
}