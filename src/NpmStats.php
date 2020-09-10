<?php

namespace Developmint\NpmStats;

use GuzzleHttp\Client;

class NpmStats
{
    /** @var \GuzzleHttp\Client */
    protected $client;
    /** @var string */
    protected $baseUrl;

    const LAST_DAY = 'last-day';
    const LAST_WEEK = 'last-week';
    const LAST_MONTH = 'last-month';
    const LAST_YEAR = 'last-year';
    const TOTAL = 'total';

    /**
     * @param \GuzzleHttp\Client $client
     * @param string $baseUrl
     */
    public function __construct(Client $client, $baseUrl = 'https://api.npmjs.org/downloads')
    {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param $packageName
     * @param string $pointValue
     * @return array
     */
    private function getStatsByPoint($packageName, $pointValue = 'last-day')
    {
        return $this->makeRequest("/point/{$pointValue}/{$packageName}");
    }

    /**
     * @param $packageName
     * @param string $rangeValue
     * @return array
     */
    private function getStatsByRange($packageName, $rangeValue = 'last-day')
    {
        return $this->makeRequest("/range/{$rangeValue}/{$packageName}");
    }

    /**
     * @param $packageName
     * @param string $period
     * @param bool $asRange
     * @return array
     * @throws \Exception
     */
    public function getStats($packageName, $period = 'last-day', $asRange = false)
    {
        if (empty($packageName)) {
            throw new \Exception("Package name can't be empty");
        }

        $this->replaceSpecialPeriodsIfApplicable($period);

        if ($asRange) {
            return $this->getStatsByRange($packageName, $period);
        }

        return $this->getStatsByPoint($packageName, $period);
    }

    /**
     * @param string $resource
     * @param array $query
     *
     * @return array
     */
    private function makeRequest($resource, array $query = [])
    {
        $packages = $this->client
            ->get("{$this->baseUrl}{$resource}", compact('query'))
            ->getBody()
            ->getContents();

        return json_decode($packages, true);
    }

    private function replaceSpecialPeriodsIfApplicable(&$period)
    {
        if ($period === self::TOTAL) {
            $currentDate = (new \DateTime)->format('Y-m-d');
            $period = "2015-01-01:{$currentDate}";
        } elseif ($period === self::LAST_YEAR) {
            $currentDate = (new \DateTime)->format('Y-m-d');
            $beforeDate = (new \DateTime)->modify('-365 days')->format('Y-m-d');
            $period = "{$beforeDate}:{$currentDate}";
        }
    }
}
