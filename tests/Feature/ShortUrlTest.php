<?php

namespace Tests\Feature;

use App\Models\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class ShortUrlTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_generates_a_short_url_for_a_valid_long_url()
    {
        $response = $this->post(route('short-url.store'), [
            'long_url' => 'https://example.com',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('short_urls', [
            'long_url' => 'https://example.com',
        ]);
    }

    /** @test */
    public function it_requires_a_valid_url()
    {
        $response = $this->post(route('short-url.store'), [
            'long_url' => 'invalid-url',
        ]);

        $response->assertSessionHasErrors('long_url');
    }

    /** @test */
    public function short_url_is_unique()
    {
        ShortUrl::factory()->create(['short_code' => 'abc123']);

        $response = $this->post(route('short-url.store'), [
            'long_url' => 'https://anotherexample.com',
        ]);

        $newShortCode = ShortUrl::where('long_url', 'https://anotherexample.com')->first()->short_code;
        $this->assertNotEquals('abc123', $newShortCode);
    }

    /** @test */
    public function it_redirects_to_the_correct_long_url()
    {
        $shortUrl = ShortUrl::factory()->create([
            'long_url' => 'https://example.com',
            'short_code' => 'xyz123',
        ]);

        $response = $this->get('/xyz123');
        $response->assertRedirect('https://example.com');
    }
}


