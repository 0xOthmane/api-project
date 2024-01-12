<?php

namespace App\Tests\Functional;

use App\Factory\TreasureFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Json;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\ResetDatabase;

class TreasureResourceTest extends KernelTestCase
{
    use HasBrowser;
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
            'coolFactor',
            'owner',
            'shortDescription',
            'plunderedAtAgo',
        ]);
    }
}
