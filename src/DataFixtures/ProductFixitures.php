<?php

namespace App\DataFixtures;

use App\Service\ProductService;
use App\Service\SeederService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixitures extends Fixture
{
    const NO_RECOMMENDATION_CONFIG = [
        '1-1', '3-1', '4-5'
    ];

    const NO_PRODUCT_AVAILABLE_CONFIG = [
        '1-2', '3-1', '4-1', '4-2', '4-3', '4-4', '5-1', '5-2', '5-3', '5-4'
    ];

    const NEXT_QUESTION_CONFIG_ARRAY_LIST = [
        '2-1', '2-2', '2-3'
    ];

    const SILDENAFIL_50_OR_TADALAFIL_10_CONFIG = [
        '2-4'
    ];

    const SILDENAFIL_50_CONFIG = [
        '2a-1'
    ];

    const TADALAFIL_20_CONFIG = [
        '2a-2', '2c-2'
    ];

    const TADALAFIL_10_CONFIG = [
        '2b-1'
    ];

    const SILDENAFIL_100_CONFIG = [
        '2b-2', '2c-1'
    ];

    const SILDENAFIL_100_OR_TADALAFIL_20_CONFIG = [
        '2c-3'
    ];

    public const MAP_NEXT_QUESTION = [
        '2-1' => '2a',
        '2-2' => '2b',
        '2-3' => '2c'
    ];

    /**
     * @var SeederService
     */
    protected $seedService;

    public function __construct(SeederService $seedService)
    {
        $this->seedService = $seedService;
    }

    /**
     * To seed multiple products
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $array = [
            ['product_identifier' => 'no_recommendation', 'behaviour_description' => 'no recommendation', 'behaviour_configuration_array' => self::NO_RECOMMENDATION_CONFIG, 'restriction_description' => 'no restrictions'],
            ['product_identifier' => 'no_products_available', 'behaviour_description' => 'No products available', 'behaviour_configuration_array' => self::NO_PRODUCT_AVAILABLE_CONFIG, 'restriction_description' => 'exclude all products'],
            ['product_identifier' => 'go_to_next_question_2a', 'behaviour_description' => 'Ask question '.self::MAP_NEXT_QUESTION['2-1'], 'behaviour_configuration_array' => ['2-1'], 'restriction_description' => 'no restrictions'],
            ['product_identifier' => 'go_to_next_question_2b', 'behaviour_description' => 'Ask question '.self::MAP_NEXT_QUESTION['2-2'], 'behaviour_configuration_array' => ['2-2'], 'restriction_description' => 'no restrictions'],
            ['product_identifier' => 'go_to_next_question_2c', 'behaviour_description' => 'Ask question '.self::MAP_NEXT_QUESTION['2-3'], 'behaviour_configuration_array' => ['2-3'], 'restriction_description' => 'no restrictions'],
            ['product_identifier' => 'sildenafil_50_or_tadalafil_10', 'behaviour_description' => 'Sildenafil 50mg or Tadalafil 10mg', 'behaviour_configuration_array' => self::SILDENAFIL_50_OR_TADALAFIL_10_CONFIG, 'restriction_description' => 'no restrictions'],
            ['product_identifier' => 'sildenafil_50', 'behaviour_description' => 'Sildenafil 50mg', 'behaviour_configuration_array' => self::SILDENAFIL_50_CONFIG, 'restriction_description' => 'exclude tadalafil'],
            ['product_identifier' => 'tadalafil_20', 'behaviour_description' => 'Tadalafil 20mg', 'behaviour_configuration_array' => self::TADALAFIL_20_CONFIG, 'restriction_description' => 'exclude sildenafil'],
            ['product_identifier' => 'tadalafil_10', 'behaviour_description' => 'Tadalafil 10mg', 'behaviour_configuration_array' => self::TADALAFIL_10_CONFIG, 'restriction_description' => 'exclude sildenafil'],
            ['product_identifier' => 'sildenafil_100', 'behaviour_description' => 'Sildenafil 100mg', 'behaviour_configuration_array' => self::SILDENAFIL_100_CONFIG, 'restriction_description' => 'exclude tadalafil'],
            ['product_identifier' => 'sildenafil_100_or_tadalafil_20', 'behaviour_description' => 'Sildenafil 100mg or Tadalafil 20mg', 'behaviour_configuration_array' => self::SILDENAFIL_100_OR_TADALAFIL_20_CONFIG, 'restriction_description' => 'no restrictions']
        ];

        $this->seedService->seedEntity('products', $array);
        $manager->flush();
    }

}
