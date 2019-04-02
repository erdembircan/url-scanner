<?php
namespace Erdem\Php\Url;

class Scanner
{
    protected $urls;

    protected $client;

    public function __construct($urls)
    {
        if (is_array($urls)) {
            $this->urls = $urls;
        } else {
            $this->urls[] = $urls;
        }

        $this->client = new \GuzzleHttp\Client();
    }

    public function getInvalidUrls()
    {
        $invUrls = [];

        foreach ($this->urls as $url) {
            try {
                $statusCode = $this->getStatusCode($url);
            } catch (\Exception $e) {
                $statusCode = 500;
            }

            if ($statusCode >= 400) {
                $invUrls[]= ['url'=> $url, 'status'=>$statusCode];
            }
        }

        return $invUrls;
    }

    public function getStatusCode($url)
    {
        $res = $this->client->request('GET', $url);

        return $res->getStatusCode();
    }
}
