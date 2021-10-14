<?php

declare(strict_types=1);

namespace Tests;

class ClientTest extends AbstractTest
{
    /**
     * @vcr ClientTest/testCreateClientWithCredentials.json
     */
    public function testCreateClientWithCredentials()
    {
        $client = $this->getClientShop();
        static::assertEquals($client->getAccessToken(),'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MzQyMDM3NjUsImV4cCI6MTYzNDIwNzM2NSwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoic2hvcEBleGFtcGxlLmNvbSJ9.dKcUbeNa5oxQBTUcNejOoOR7eN0qs9hR7X7n9okw9m-X4bZzqiK3fcvcm3ByXwr4r5vKpa3SUxRM0XSYBxFB3nRIHFHa-Jp0HT4dZKS6iQqY_hlf4qq2xlDszgFw8iONk8C10tWHgQwT5Y0bmqBSi98JYvY6eSb2-T1dZv8QFpL1d3K_HWAiEm0IQcQFbduMs-wrzeFrQ3dcxSLMmhTdnZxHePCFPN4ZIpyShRaGlqLP93m2AU26qGjSipCwOVD6xHi-4xolpa0WfhHU6TndFedBwbQ20jzS2uobke9icje81McWc46VgR08RAAEZyy2woNGWa7HhQCRJ8gXVpL4PAFVyUaL4jm1TC9gXfAVvq67TZ1bz61AJk0kswGlqdI2yhffRSJP6fzk6XyZC-DhoR4r2lwu1CdrtnrOmcpSyClUgoOKE5ci7wWuO6hfik0uBS2rcj58mEHtUO3UKB6-cdKvkQz1NbTqqkwiDz0uAfMFkJtUiut7f8hkUpiArq715rpgh8YQ1M0ABqVPjWvnPoITlHMmHk1-G3lHl__2935T6gR3FMZobSrxgLUOBWEF7UwgDBO5ToypvHuA9XXit9zgeUboyQICIbbzLPv049L1b8jTERpxC80FuAgCyHLKWRmbV71nWVup8nq4s0NVffUUv0ZGNrRhcybb-lhfajc');
    }
}
