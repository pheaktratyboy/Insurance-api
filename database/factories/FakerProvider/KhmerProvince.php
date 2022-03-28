<?php

namespace Database\Factories\FakerProvider;

use Faker\Provider\Base;

class KhmerProvince extends Base
{
    private $provinces = ['Banteay Meanchey', 'Battambang', 'Kampong Cham', 'Kampong Chhnang', 'Kampong Speu',
        'Kampong Thom', 'Kampot', 'Kandal', 'Koh Kong', 'Kratié', 'Mondulkiri', 'Phnom Penh', 'Preah Vihear',
        'Prey Veng', 'Pursat', 'Ratanak Kiri', 'Siem Reap', 'Preah Sihanouk', 'Steung Treng', 'Svay Rieng', 'Takéo',
        'Oddar Meanchey', 'Kep', 'Pailin', 'Tboung Khmu', ];

    public function province()
    {
        return $this->generator->randomElement($this->provinces);
    }
}
