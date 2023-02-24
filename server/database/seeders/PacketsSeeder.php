<?php
/**
 *  --- Coded by Le ---
 *  at 8/12/2022 4:12 μ.μ.
 *  code full of 🐛🦗🦟
 */


namespace Database\Seeders;

use App\Models\Packet;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PacketsSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data()->reverse() as $packet)
        {
            Packet::create($packet->toArray());
        }
    }

    private function data()
    {
        return collect([
            '0' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.947548',
                'longitude' => '23.751152',
                'altitude' => 163,
                'course' => 35,
                'speed' => 41,
                'satellites' => 0,
            ]),
            '3' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.947408',
                'longitude' => '23.750993',
                'altitude' => 162,
                'course' => 25,
                'speed' => 42,
                'satellites' => 0,
            ]),
            '4' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.94738' ,
                'longitude' => '23.750965',
                'altitude' => 162,
                'course' => 10,
                'speed' => 0,
                'satellites' => 0
            ]),
            '5' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.94736' ,
                'longitude' => '23.750953',
                'altitude' => 162,
                'course' => 349,
                'speed' => 7,
                'satellites' => 0
            ]),
            '6' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.947345',
                'longitude' => '23.750953',
                'altitude' => 162,
                'course' => 119,
                'speed' => 0,
                'satellites' => 0
            ]),
            '7' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.947332',
                'longitude' => '23.750952',
                'altitude' => 162,
                'course' => 119,
                'speed' => 0,
                'satellites' => 0
            ]),
            '8' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.947332',
                'longitude' => '23.750957',
                'altitude' => 162,
                'course' => 119,
                'speed' => 0,
                'satellites' => 0
            ]),
            '9' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.94733' ,
                'longitude' => '23.750983',
                'altitude' => 162,
                'course' => 119,
                'speed' => 7,
                'satellites' => 0
            ]),
            '10' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.94733' ,
                'longitude' => '23.750983',
                'altitude' => 162,
                'course' => 119,
                'speed' => 7,
                'satellites' => 0

            ]),
            '11' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.947362',
                'longitude' => '23.750898',
                'altitude' => 162,
                'course' => 107,
                'speed' => 20,
                'satellites' => 1
            ]),
            '112' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.94744' ,
                'longitude' => '23.750652',
                'altitude' => 162,
                'course' => 112,
                'speed' => 36,
                'satellites' => 1
            ]),
            '13' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.947653',
                'longitude' => '23.749947',
                'altitude' => 162,
                'course' => 108,
                'speed' => 50,
                'satellites' => 1
            ]),
            '14' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.947987',
                'longitude' => '23.749065',
                'altitude' => 163,
                'course' => 105,
                'speed' => 40,
                'satellites' => 1
            ]),
            '15' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.94803' ,
                'longitude' => '23.748948',
                'altitude' => 163,
                'course' => 95,
                'speed' => 1,
                'satellites' => 0
            ]),
            '16' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.947778',
                'longitude' => '23.747873',
                'altitude' => 165,
                'course' => 61,
                'speed' => 5,
                'satellites' => 0
            ]),
            '17' => collect([

                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.947342',
                'longitude' => '23.746753',
                'altitude' => 165,
                'course' => 61,
                'speed' => 4,
                'satellites' => 0
            ]),
            '18' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.947222',
                'longitude' => '23.746445',
                'altitude' => 165,
                'course' => 61,
                'speed' => 4,
                'satellites' => 0
            ]),
            '19' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.947123',
                'longitude' => '23.746233',
                'altitude' => 165,
                'course' => 68,
                'speed' => 4,
                'satellites' => 0
            ]),
            '20' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.947108',
                'longitude' => '23.746183',
                'altitude' => 165,
                'course' => 78,
                'speed' => 1,
                'satellites' => 0
            ]),
            '21' => collect([

                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.947073',
                'longitude' => '23.746122',
                'altitude' => 162,
                'course' => 72,
                'speed' => 10,
                'satellites' => 6,
            ]),
            '22' => collect([

                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.947075',
                'longitude' => '23.746123',
                'altitude' => 162,
                'course' => 72,
                'speed' => 10,
                'satellites' => 5,

            ]),
            '23' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.947078',
                'longitude' => '23.746115',
                'altitude' => 161,
                'course' => 72,
                'speed' => 10,
                'satellites' => 7,
            ]),
            '24' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.947063',
                'longitude' => '23.746073',
                'altitude' => 160,
                'course' => 66,
                'speed' => 0,
                'satellites' => 0,
            ]),
            '25' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.946965',
                'longitude' => '23.745843',
                'altitude' => 157,
                'course' => 56,
                'speed' => 1,
                'satellites' => 0,
            ]),
            '26' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.94693' ,
                'longitude' => '23.745798',
                'altitude' => 156,
                'course' => 35,
                'speed' => 10,
                'satellites' => 5,

            ]),
            '27' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.946878',
                'longitude' => '23.745743',
                'altitude' => 153,
                'course' => 53,
                'speed' => 10,
                'satellites' => 4,
            ]),
            '28' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.946848',
                'longitude' => '23.74571' ,
                'altitude' => 151,
                'course' => 55,
                'speed' => 10,
                'satellites' => 5,
            ]),
            '29' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.946765',
                'longitude' => '23.74555' ,
                'altitude' => 146,
                'course' => 50,
                'speed' => 0,
                'satellites' => 0
            ]),
            '30' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.94674' ,
                'longitude' => '23.745497',
                'altitude' => 144,
                'course' => 68,
                'speed' => 1,
                'satellites' => 4,
            ]),
            '31' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.946723',
                'longitude' => '23.74543' ,
                'altitude' => 143,
                'course' => 58,
                'speed' => 6,
                'satellites' => 0,
            ]),
            '32' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.94625' ,
                'longitude' => '23.744402',
                'altitude' => 136,
                'course' => 58,
                'speed' => 4,
                'satellites' => 4,
            ]),
            '33' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.946012',
                'longitude' => '23.743962',
                'altitude' => 134,
                'course' => 52,
                'speed' => 4,
                'satellites' => 0
            ]),
            '34' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.945902',
                'longitude' => '23.743777',
                'altitude' => 133,
                'course' => 41,
                'speed' => 0,
                'satellites' => 0
            ]),
            '35' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.945662',
                'longitude' => '23.74353' ,
                'altitude' => 131,
                'course' => 31,
                'speed' => 1,
                'satellites' => 0
            ]),
            '36' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.945485',
                'longitude' => '23.743437',
                'altitude' => 131,
                'course' => 18,
                'speed' => 1,
                'satellites' => 9,
            ]),
            '37' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.944877',
                'longitude' => '23.743447',
                'altitude' => 130,
                'course' => 352,
                'speed' => 34,
                'satellites' => 9
            ]),
            '38' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.943898',
                'longitude' => '23.74374' ,
                'altitude' => 124,
                'course' => 350,
                'speed' => 31,
                'satellites' => 9
            ]),
            '39' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.943652',
                'longitude' => '23.74379' ,
                'altitude' => 122,
                'course' => 21,
                'speed' => 10,
                'satellites' => 10,

            ]),
            '40' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.943582',
                'longitude' => '23.74383' ,
                'altitude' => 122,
                'course' => 27,
                'speed' => 4,
                'satellites' => 0
            ]),
            '41' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.943463',
                'longitude' => '23.743743',
                'altitude' => 120,
                'course' => 49,
                'speed' => 3,
                'satellites' => 0

            ]),
            '42' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.94342' ,
                'longitude' => '23.743693',
                'altitude' => 119,
                'course' => 66,
                'speed' => 9,
                'satellites' => 0
            ]),
            '422' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.943032',
                'longitude' => '23.7427'  ,
                'altitude' => 115,
                'course' => 67,
                'speed' => 0,
                'satellites' => 1,
            ]),
            '43' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.942847',
                'longitude' => '23.741788',
                'altitude' => 113,
                'course' => 79,
                'speed' => 6,
                'satellites' => 4,
            ]),
            '44' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.942815',
                'longitude' => '23.741243',
                'altitude' => 112,
                'course' => 73,
                'speed' => 4,
                'satellites' => 5,
            ]),
            '45' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.942845',
                'longitude' => '23.741137',
                'altitude' => 112,
                'course' => 53,
                'speed' => 2,
                'satellites' => 4,
            ]),
            '46' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.942853',
                'longitude' => '23.740995',
                'altitude' => 112,
                'course' => 22,
                'speed' => 5,
                'satellites' => 9,
            ]),
            '47' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.942768',
                'longitude' => '23.740902',
                'altitude' => 111,
                'course' => 2,
                'speed' => 30,
                'satellites' => 4
            ]),
            '48' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.942633',
                'longitude' => '23.740892',
                'altitude' => 110,
                'course' => 352,
                'speed' => 37,
                'satellites' => 9
            ]),
            '49' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.942367',
                'longitude' => '23.740975',
                'altitude' => 110,
                'course' => 350,
                'speed' => 52,
                'satellites' => 9
            ]),
            '50' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.941545',
                'longitude' => '23.741158',
                'altitude' => 110,
                'course' => 358,
                'speed' => 62,
                'satellites' => 8
            ]),
            '51' => collect([

                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.940898',
                'longitude' => '23.740995',
                'altitude' => 111,
                'course' => 12,
                'speed' => 8,
                'satellites' => 8,
            ]),
            '52' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.940352',
                'longitude' => '23.740885',
                'altitude' => 111,
                'course' => 0,
                'speed' => 7,
                'satellites' => 1
            ]),
            '53' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.939787',
                'longitude' => '23.740922',
                'altitude' => 110,
                'course' => 349,
                'speed' => 68,
                'satellites' => 8
            ]),
            '54' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.938933',
                'longitude' => '23.741183',
                'altitude' => 108,
                'course' => 338,
                'speed' => 61,
                'satellites' => 8
            ]),
            '55' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.938587',
                'longitude' => '23.74134' ,
                'altitude' => 108,
                'course' => 334,
                'speed' => 46,
                'satellites' => 8
            ]),
            '56' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.93842' ,
                'longitude' => '23.741445',
                'altitude' => 109,
                'course' => 333,
                'speed' => 36,
                'satellites' => 8
            ]),
            '57' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.93829' ,
                'longitude' => '23.741542',
                'altitude' => 109,
                'course' => 338,
                'speed' => 26,
                'satellites' => 8
            ]),
            '10000' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.938187',
                'longitude' => '23.741627',
                'altitude' => 108,
                'course' => 358,
                'speed' => 16,
                'satellites' => 8
            ]),
            '1212123' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.938105',
                'longitude' => '23.74167' ,
                'altitude' => 107,
                'course' => 37,
                'speed' => 1,
                'satellites' => 8,
            ]),
            '12312312' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.93803' ,
                'longitude' => '23.741662',
                'altitude' => 106,
                'course' => 84,
                'speed' => 0,
                'satellites' => 7,
            ]),
            '1' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.937998',
                'longitude' => '23.741578',
                'altitude' => 105,
                'course' => 119,
                'speed' => 14,
                'satellites' => 8
            ]),
            'asfasf' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.93802' ,
                'longitude' => '23.74148' ,
                'altitude' => 104,
                'course' => 132,
                'speed' => 17,
                'satellites' => 8
            ]),
            'aa' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.938083',
                'longitude' => '23.741402',
                'altitude' => 104,
                'course' => 159,
                'speed' => 10,
                'satellites' => 8
            ]),
            'fasfa' => collect([
                'sn' => 'rdt002',
                'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
                'latitude' => '37.938112',
                'longitude' => '23.741287',
                'altitude' => 118,
                'course' => 230,
                'speed' => 3,
                'satellites' => 4
            ])

        ]);
    }

}
