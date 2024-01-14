<?php

namespace App\Tests\Functional;

use App\Entity\ApiToken;
use App\Factory\ApiTokenFactory;
use App\Factory\TreasureFactory;
use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\HttpOptions;
use Zenstruck\Browser\Json;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\ResetDatabase;

class TreasureResourceTest extends ApiTestCase
{
    use ResetDatabase;

    public function testGetCollectionOfTreasures(): void
    {
        TreasureFactory::createMany(5);
        $json = $this->browser()
            ->get('/api/treasures')
            // ->dump()
            ->assertJson()
            ->assertJsonMatches('"hydra:totalItems"', 5)
            ->assertJsonMatches('length("hydra:member")', 5)
            // ->use(function (Json $json) {
            //     $json->assertMatches('keys("hydra:member"[0])', [
            //         '@id',
            //         '@type',
            //         'name',
            //         'description',
            //         'value',
            //         'coolFactor',
            //         'owner',
            //         'shortDescription',
            //         'plunderedAtAgo'
            //     ])
            // });
            ->json();

        // $json->assertMatches('keys("hydra:member"[0])',[
        //     '@id',
        //     '@type',
        //     'name',
        //     'description',
        //     'value',
        //     'coolFactor',
        //     'owner',
        //     'shortDescription',
        //     'plunderedAtAgo',
        // ]);
        $this->assertSame(array_keys($json->decoded()['hydra:member'][0]), [
            '@id',
            '@type',
            'name',
            'description',
            'value',
            'owner',
            'plunderedAtAgo',
            'shortDescription'
        ]);
    }

    public function testPostToCreateTreasure(): void
    {
        $user = UserFactory::createOne([
            'password' => 'pass'
        ]);
        $this->browser()
            // ->post('/login', [
            //     'json'=>[
            //         'email'=>$user->getEmail(),
            //         'password'=>'pass'
            //     ]
            // ])
            // ->assertStatus(204)
            ->actingAs($user)
            ->post('/api/treasures', [
                'json' => []
            ])
            ->assertStatus(422)
            // ->post('/api/treasures', HttpOptions::json([
            //     'json' => [
            //         'name' => 'A shiny thing',
            //         'description' => 'It sparkles when I wave it in the air.',
            //         'value' => 1000,
            //         'coolFactor' => 5,
            //         'owner' => '/api/users/' . $user->getId(),
            //     ],
            // ])->withHeader('Accept', 'application/ld+json'))
            ->post('/api/treasures', HttpOptions::json([
                'name' => 'A shiny thing',
                'description' => 'It sparkles when I wave it in the air.',
                'value' => 1000,
                'coolFactor' => 5,
                'owner' => '/api/users/' . $user->getId(),
            ]))
            ->assertStatus(201)
            ->assertJsonMatches('name', 'A shiny thing');
    }

    public function testPostToCreateTreasureWithApiKey(): void
    {
        // $token = ApiTokenFactory::createOne([
        //     'scopes' => [ApiToken::SCOPE_TREASURE_CREATE]
        // ]);
        $token = ApiTokenFactory::createOne([
            'scopes' => [ApiToken::SCOPE_TREASURE_EDIT]
        ]);
        $this->browser()
            ->post('/api/treasures', [
                'json' => [],
                'headers' => [
                    'Authorization' => 'Bearer ' . $token->getToken()
                ]
            ])
            ->assertStatus(403);
    }
}
