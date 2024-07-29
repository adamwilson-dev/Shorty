<?php

namespace Tests\Unit;

use App\Services\UrlService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UrlService $urlService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->urlService = new UrlService();
    }

    public function test_generate_shortest_url()
    {
        $testCases = [
            '' => 'a',
            'z' => 'A',
            '9' => 'aa',
            'a9' => 'ba',
            'ba' => 'bb',
            '99' => 'aaa',
            'h4f4' => 'h4f5',
            'JSW29' => 'JSW3a',
        ];

        foreach ($testCases as $input => $expected) {
            $result = $this->urlService->generateShortestUrl($input);
            $this->assertEquals($expected, $result, 'Failed asserting that '.$result.' matches expected '.$expected);
        }
    }

    public function test_multiple_iterations()
    {
        $testCases = [
            1 => 'a',
            10 => 'j',
            100 => 'aL',
            500 => 'hd',
            1000 => 'ph',
            5000 => 'arN',
            10000 => 'bKr',
        ];

        foreach ($testCases as $iterations => $expected) {
            $chars = null;
            for ($i = 0; $i < $iterations; $i++) {
                $chars = $this->urlService->generateShortestUrl($chars);
            }

            $this->assertEquals($expected, $chars, 'Failed asserting that '.$chars.' matches expected '.$expected.' after '.$iterations.' iterations');
        }
    }
}
