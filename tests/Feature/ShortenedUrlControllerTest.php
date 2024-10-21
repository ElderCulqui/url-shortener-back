<?php

namespace Tests\Feature;

use AshAllenDesign\ShortURL\Models\ShortURL;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Utilities\AuthenticationUtility;

class ShortenedUrlControllerTest extends TestCase
{
    use RefreshDatabase, AuthenticationUtility;

    #[Test]
    public function test_create_without_authentication()
    {
        // given
        $data = [ 'original_url' => 'https://www.google.com'];

        // when
        $response = $this->postJson(route('shortener-url.store'), $data);

        // then
        $response->assertUnauthorized();
    }

    #[Test]
    public function test_create()
    {
        // given
        $this->userLogin();
        $data = [ 'original_url' => 'https://www.google.com'];

        // when
        $response = $this->postJson(route('shortener-url.store'), $data);

        // then
        $response->assertCreated();
        $response->assertJsonStructure(['url_key', 'destination_url', 'default_short_url']);

        $this->assertDatabaseHas('short_urls', [
            'destination_url' => 'https://www.google.com',
        ]);
    }

    #[Test]
    public function test_create_with_invalid_url()
    {
        // given
        $this->userLogin();
        $data = [ 'original_url' => 'invalid-url'];

        // when
        $response = $this->postJson(route('shortener-url.store'), $data);

        // then
        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'original_url' => [
                'The original url field must be a valid URL.'
            ]
        ]);

        $this->assertDatabaseCount('short_urls', 0);
    }

    #[Test]
    public function test_index()
    {
        // given
        $this->userLogin();
        for ($i = 0; $i < 10; $i++) {
            ShortURL::factory()->create();
        }

        // when
        $response = $this->getJson(route('shortener-url.index'));

        // then
        $response->assertOk();
        $response->assertJsonCount(10, 'data');
        $response->assertJsonStructure(['data' => [[
                'url_key',
                'destination_url',
                'default_short_url'
            ]]
        ]);
    }

    #[Test]
    public function test_index_without_authentication()
    {
        // given
        for ($i = 0; $i < 10; $i++) {
            ShortURL::factory()->create();
        }

        // when
        $response = $this->getJson(route('shortener-url.index'));

        // then
        $response->assertUnauthorized();
    }
}
