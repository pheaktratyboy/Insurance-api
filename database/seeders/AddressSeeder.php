<?php

namespace Database\Seeders;

use App\Models\Municipality;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // static data
        $municipalities =
            [
                [
                    'name'      => 'Phnom Penh',
                    'districts' =>
                        [
                            ['name'     => 'Chamkar Mon',],
                            ['name'     => 'Doun Penh',],
                            ['name'     => 'Prampir Makara',],
                            ['name'     => 'Tuol Kouk',],
                            ['name'     => 'Dangkao',],
                            ['name'     => 'Mean Chey',],
                            ['name'     => 'Russey Keo',],
                            ['name'     => 'Sen Sok',],
                            ['name'     => 'Pou Senchey',],
                            ['name'     => 'Chroy Changvar',],
                            ['name'     => 'Prek Pnov',],
                            ['name'     => 'Chbar Ampov',],
                            ['name'     => 'Boeng Keng Kang',],
                            ['name'     => 'Kamboul',],
                        ],
                ],
                ['name'      => 'Banteay Meanchey',
                    'districts' =>
                        [
                            ['name'     => 'Serei Saophoan',],
                            ['name'     => 'Poipet',],
                            ['name'     => 'Mongkol Borey',],
                            ['name'     => 'Phnom Srok',],
                            ['name'     => 'Preah Netr',],
                            ['name'     => 'Ou Chrov',],
                            ['name'     => 'Thma Puok',],
                            ['name'     => 'Svay Chek',],
                            ['name'     => 'Malai',],
                        ]
                ],
                ['name'      => 'Battambang',
                    'districts' =>
                        [
                            ['name'     => 'Banan',],
                            ['name'     => 'Thma Koul',],
                            ['name'     => 'Bavel',],
                            ['name'     => 'Aek Phnom',],
                            ['name'     => 'Moung Rues',],
                            ['name'     => 'Rotanak Mondol',],
                            ['name'     => 'Phnum Proek',],
                            ['name'     => 'Kamrien',],
                            ['name'     => 'Koas Krala',],
                            ['name'     => 'Rukhak Kiri',],

                        ]
                ],
                ['name'      => 'Kampong Cham',
                    'districts' =>
                        [
                            ['name'     => 'Batheay',],
                            ['name'     => 'Chamkar Loeu',],
                            ['name'     => 'Cheung Prey',],
                            ['name'     => 'Kampong Siem',],
                            ['name'     => 'Kang Meas',],
                            ['name'     => 'Koh Sotin',],
                            ['name'     => 'Prey Chhor',],
                            ['name'     => 'Srey Santhor',],
                            ['name'     => 'Steung Trang',],

                        ]
                ],
                ['name'      => 'Kampong Chhnang',
                    'districts' =>
                        [
                            ['name'     => 'Baribo',],
                            ['name'     => 'Chol Kiri',],
                            ['name'     => 'Kampong Chhnang Municipality',],
                            ['name'     => 'Kampong Leng',],
                            ['name'     => 'Kampong Tralach',],
                            ['name'     => 'Rolea Bier',],
                            ['name'     => 'Samakki Meanchey',],
                            ['name'     => 'Teuk Phos',],
                        ]
                ],
                ['name'      => 'Kampong Speu',
                    'districts' =>
                        [
                            ['name'     => 'Basedth',],
                            ['name'     => 'Chbar Mon Municipality',],
                            ['name'     => 'Kong Pisei',],
                            ['name'     => 'Aoral',],
                            ['name'     => 'Oudong',],
                            ['name'     => 'Phnom Sruoch',],
                            ['name'     => 'Samraong Tong',],
                            ['name'     => 'Thpong',],

                        ]
                ],
                ['name'      => 'Kampong Thom',
                    'districts' =>
                        [
                            ['name'     => 'Baray',],
                            ['name'     => 'Kampong Svay',],
                            ['name'     => 'Steung Saen Municipality',],
                            ['name'     => 'Prasat Balangk',],
                            ['name'     => 'Prasat Sambour',],
                            ['name'     => 'Sandaan',],
                            ['name'     => 'Santuk',],
                            ['name'     => 'Stoung',],
                            ['name'     => 'Taing Kouk',],
                        ]
                ],

                ['name'      => 'Kampot',
                    'districts' =>
                        [
                            ['name'     => 'Angkor Chey',],
                            ['name'     => 'Banteay Meas',],
                            ['name'     => 'Chhouk',],
                            ['name'     => 'Chum Kiri',],
                            ['name'     => 'Dang Tong',],
                            ['name'     => 'Kampong Trach',],
                            ['name'     => 'Tuek Chhou',],
                            ['name'     => 'Kampot Municipality',],
                        ]
                ],
                ['name'      => 'Kandal',
                    'districts' =>
                        [
                            ['name'     => 'Kandal Stueng',],
                            ['name'     => 'Kien Svay',],
                            ['name'     => 'Khsach Kandal',],
                            ['name'     => 'Kaoh Thum',],
                            ['name'     => 'Leuk Daek',],
                            ['name'     => 'Lvea Aem',],
                            ['name'     => 'Mukh Kampul',],
                            ['name'     => 'Angk Snuol',],
                            ['name'     => 'Ponhea Lueu',],
                            ['name'     => 'Saang',],
                            ['name'     => 'Ta Khmau',],
                        ]
                ],
                ['name'      => 'Koh Kong',
                    'districts' =>
                        [
                            ['name'     => 'Botum Sakor',],
                            ['name'     => 'Kiri Sakor',],
                            ['name'     => 'Koh Kong',],
                            ['name'     => 'Khemarak Phoumin Municipality (formerly Smach Mean Chey)',],
                            ['name'     => 'Mondol Seima',],
                            ['name'     => 'Srae Ambel',],
                            ['name'     => 'Thma Bang',],
                        ]
                ],
                ['name'      => 'Kratié',
                    'districts' =>
                        [
                            ['name'     => 'Chhloung',],
                            ['name'     => 'Kratié',],
                            ['name'     => 'Preaek Prasab',],
                            ['name'     => 'Sambour',],
                            ['name'     => 'Snuol',],
                            ['name'     => 'Chetr Bore']
                        ]
                ],
                ['name'      => 'Mondulkiri',
                    'districts' =>
                        [
                            ['name'     => 'Kaev Seima',],
                            ['name'     => 'Kaoh Nheaek',],
                            ['name'     => 'Ou Reang',],
                            ['name'     => 'Pechr Chenda',],
                            ['name'     => 'Senmonorom Municipality',],
                        ]
                    ],
                ['name'      => 'Preah Vihear',
                    'districts' =>
                        [
                            ['name'     => 'Chey Saen',],
                            ['name'     => 'Chhaeb',],
                            ['name'     => 'Choam Khsant',],
                            ['name'     => 'Kuleaen',],
                            ['name'     => 'Rovieng',],
                            ['name'     => 'Sangkom Thmei',],
                            ['name'     => 'Tbaeng Meanchey',],
                            ['name'     => 'Preah Vihear Municipality',],
                        ]
                    ],

                ['name'      => 'Prey Veng',
                    'districts' =>
                        [
                            ['name'     => 'Ba Phnum',],
                            ['name'     => 'Kamchay Mear',],
                            ['name'     => 'Kampong Trabaek',],
                            ['name'     => 'Kanhchriech',],
                            ['name'     => 'Me Sang',],
                            ['name'     => 'Peam Chor',],
                            ['name'     => 'Peam Ro',],
                            ['name'     => 'Pea Reang',],
                            ['name'     => 'Preah Sdach',],
                            ['name'     => 'Prey Veng Municipality',],
                            ['name'     => 'Pur Rieng',],
                            ['name'     => 'Sithor Kandal',],
                            ['name'     => 'Svay Antor',],
                        ]
                    ],
                ['name'      => 'Pursat',
                    'districts' =>
                        [
                            ['name'     => 'Bakan   ',],
                            ['name'     => 'Kandieng    ',],
                            ['name'     => 'Krakor  ',],
                            ['name'     => 'Phnum Kravanh   ',],
                            ['name'     => 'Pursat Municipality (formerly Sampov Meas)  ',],
                            ['name'     => 'Veal Veang  ',],
                            ['name'     => 'Talou Senchey   ',],
                        ]
                    ],

                ['name'      => 'Ratanakiri',
                    'districts' =>
                        [
                            ['name'     => 'Andoung Meas',],
                            ['name'     => 'Banlung Municipality',],
                            ['name'     => 'Bar Kaev',],
                            ['name'     => 'Koun Mom',],
                            ['name'     => 'Lumphat',],
                            ['name'     => 'Ou Chum',],
                            ['name'     => 'Ou Ya Dav',],
                            ['name'     => 'Ta Veaeng',],
                            ['name'     => 'Veun Sai',],
                        ]
                    ],
                ['name'      => 'Siem Reap',
                    'districts' =>
                        [
                            ['name'     => 'Angkor Chum',],
                            ['name'     => 'Angkor Thom',],
                            ['name'     => 'Banteay Srei',],
                            ['name'     => 'Chi Kraeng',],
                            ['name'     => 'Kralanh',],
                            ['name'     => 'Puok',],
                            ['name'     => 'Prasat Bakong',],
                            ['name'     => 'Soutr Nikom',],
                            ['name'     => 'Srei Snam',],
                            ['name'     => 'Svay Leu',],
                            ['name'     => 'Varin',],
                        ]
                    ],
                ['name'      => 'Stung Treng',
                    'districts' =>
                        [
                            ['name'     => 'Sesan',],
                            ['name'     => 'Siem Bouk',],
                            ['name'     => 'Siem Pang',],
                            ['name'     => 'Stung Treng Municipality',],
                            ['name'     => 'Thala Barivat',],
                            ['name'     => 'Borei O Svay Sen Chey ',],
                        ]
                    ],

                ['name'      => 'Svay Rieng',
                    'districts' =>
                        [
                            ['name'     => 'Chanthrea',],
                            ['name'     => 'Kampong Rou',],
                            ['name'     => 'Romdoul',],
                            ['name'     => 'Romeas Haek',],
                            ['name'     => 'Svay Chrom',],
                            ['name'     => 'Svay Rieng Municipality',],
                            ['name'     => 'Svay Theab',],
                            ['name'     => 'Bavet Municipality',],
                        ]
                    ],

                ['name'      => 'Takéo',
                    'districts' =>
                        [
                            ['name'     => 'Angkor Borei',],
                            ['name'     => 'Bati',],
                            ['name'     => 'Bourei Cholsar',],
                            ['name'     => 'Kiri Vong',],
                            ['name'     => 'Koh Andaet',],
                            ['name'     => 'Prey Kabbas',],
                            ['name'     => 'Samraŏng',],
                            ['name'     => 'Doun Kaev Municipality',],
                            ['name'     => 'Tram Kak',],
                            ['name'     => 'Treang',],
                        ]
                    ],
                ['name'      => 'Oddar Meanchey',
                    'districts' =>
                        [
                            ['name'     => 'Samraong',],
                            ['name'     => 'Anlong Veng',],
                            ['name'     => 'Banteay Ampil',],
                            ['name'     => 'Chong Kal',],
                            ['name'     => 'Trapeang Prasat',],
                        ]
                    ],

                ['name'      => 'Kep',
                    'districts' =>
                        [
                            ['name'     => 'Damnak Chang aeur',],
                            ['name'     => 'Kep Municipality',],
                        ]
                    ],

                ['name'      => 'Pailin',
                    'districts' =>
                        [
                            ['name'     => 'Sala Krau',],
                        ]
                    ],

                ['name'      => 'Preah Sihanouk',
                    'districts' =>
                        [
                            ['name'     => 'Preah Sihanouk',],
                        ]
                ],

                ['name'      => 'Tboung Khmum',
                    'districts' =>
                        [
                            ['name'     => 'Dambae',],
                            ['name'     => 'Krouch Chhmar',],
                            ['name'     => 'Memot',],
                            ['name'     => 'Ou Reang Ov',],
                            ['name'     => 'Ponhea Kraek',],
                            ['name'     => 'Tboung Khmum',],
                            ['name'     => 'Suong Municipality',],
                        ]
                ]
                ];

        // seed address relations data
        foreach ($municipalities as $municipality) {
            $seededMunicipality = Municipality::factory()->create([
                'name' => $municipality['name'],
            ]);
            foreach ($municipality['districts'] as $district) {
                $seededMunicipality->district()->create([
                    'name' => $district['name'],
                ]);
            }
        }
    }
}
