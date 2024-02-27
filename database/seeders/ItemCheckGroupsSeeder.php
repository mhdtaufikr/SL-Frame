<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemCheckGroupsSeeder extends Seeder
{
    public function run()
    {
        // Check Group 1
        DB::table('itemcheckgroups')->insert([
            'index' => '1.1',
            'CheckGroup' => 1,
            'ItemCheck' => 'C/Mbr No.1 + Complete Bracket',
        ]);

        DB::table('itemcheckgroups')->insert([
            'index' => '1.2',
            'CheckGroup' => 1,
            'ItemCheck' => 'Hook Frt / CKD',
        ]);

        DB::table('itemcheckgroups')->insert([
            'index' => '1.3',
            'CheckGroup' => 1,
            'ItemCheck' => 'Bracket Tie Down / SLJ-77',
        ]);

        DB::table('itemcheckgroups')->insert([
            'index' => '1.4',
            'CheckGroup' => 1,
            'ItemCheck' => 'Bracket  / SGC -22',
        ]);

        DB::table('itemcheckgroups')->insert([
            'index' => '1.5',
            'CheckGroup' => 1,
            'ItemCheck' => 'Bracket Horn / SLJ-55',
        ]);

        DB::table('itemcheckgroups')->insert([
            'index' => '1.6',
            'CheckGroup' => 1,
            'ItemCheck' => 'Bracket Mtg Cabin A/SLJ-85',
        ]);



        // Check Group 2
        DB::table('itemcheckgroups')->insert([
            'index' => '2.1',
            'CheckGroup' => 2,
            'ItemCheck' => 'C/Mbr No.1,5 + Complete Bracket',
        ]);

        DB::table('itemcheckgroups')->insert([
            'index' => '2.2',
            'CheckGroup' => 2,
            'ItemCheck' => 'Bracket Strut Bar  / SLJ-38',
        ]);

        DB::table('itemcheckgroups')->insert([
            'index' => '2.3',
            'CheckGroup' => 2,
            'ItemCheck' => 'Bracket Radiator  / SLJ-82-83',
        ]);

        DB::table('itemcheckgroups')->insert([
            'index' => '2.4',
            'CheckGroup' => 2,
            'ItemCheck' => 'Reinforcement / SLJ-44 & SLJ-33',
        ]);

        DB::table('itemcheckgroups')->insert([
            'index' => '2.5',
            'CheckGroup' => 2,
            'ItemCheck' => 'Bracket Roller / SLJ-73',
        ]);

        DB::table('itemcheckgroups')->insert([
            'index' => '2.6',
            'CheckGroup' => 2,
            'ItemCheck' => 'Bracket / SLJ-103',
        ]);

        // Check Group 3
        DB::table('itemcheckgroups')->insert([
           'index'=> '3.1',
            'CheckGroup' => 3,
            'ItemCheck' => 'C/Mbr No.2 + Complete Bracket',
        ]);

        DB::table('itemcheckgroups')->insert([
           'index'=> '3.2',
            'CheckGroup' => 3,
            'ItemCheck' => 'Long Sill   / SLJ-81',
        ]);

        DB::table('itemcheckgroups')->insert([
           'index'=> '3.3',
            'CheckGroup' => 3,
            'ItemCheck' => 'Bracket Assy Cab Mtg / SLJ-119',
        ]);

        DB::table('itemcheckgroups')->insert([
           'index'=> '3.4',
            'CheckGroup' => 3,
            'ItemCheck' => 'Bracket Eng. Sup.  / SLJ-141',
        ]);

        DB::table('itemcheckgroups')->insert([
           'index'=> '3.5',
            'CheckGroup' => 3,
            'ItemCheck' => 'Bracket Cable A / SLJ-65',
        ]);

        DB::table('itemcheckgroups')->insert([
           'index'=> '3.6',
            'CheckGroup' => 3,
            'ItemCheck' => 'Bracket Hose / SLJ-35',
        ]);

        DB::table('itemcheckgroups')->insert([
           'index'=> '3.7',
            'CheckGroup' => 3,
            'ItemCheck' => 'Hanger Spring / CKD',
        ]);

        DB::table('itemcheckgroups')->insert([
           'index'=> '3.8',
            'CheckGroup' => 3,
            'ItemCheck' => 'Bracket Fuel Tank  / SLJ-97',
        ]);

        // Check Group 4
        DB::table('itemcheckgroups')->insert([
           'index' => '4.1',
            'CheckGroup' => 4,
            'ItemCheck' => 'Brkt Brake Hose',
        ]);

        DB::table('itemcheckgroups')->insert([
           'index' => '4.2',
            'CheckGroup' => 4,
            'ItemCheck' => 'C/Mbr No. 3 + Complete Brkt',
        ]);

        DB::table('itemcheckgroups')->insert([
           'index' => '4.3',
            'CheckGroup' => 4,
            'ItemCheck' => 'C/Mbr No.4 + Complete Brkt',
        ]);

        DB::table('itemcheckgroups')->insert([
           'index' => '4.4',
            'CheckGroup' => 4,
            'ItemCheck' => 'Hook Rear / CKD',
        ]);

        DB::table('itemcheckgroups')->insert([
           'index' => '4.5',
            'CheckGroup' => 4,
            'ItemCheck' => 'Brkt Shackle',
        ]);

        DB::table('itemcheckgroups')->insert([
           'index' => '4.6',
            'CheckGroup' => 4,
            'ItemCheck' => 'Brkt Stay muffler / SLJ-97',
        ]);

        DB::table('itemcheckgroups')->insert([
           'index' => '4.7',
            'CheckGroup' => 4,
            'ItemCheck' => 'Brkt Stoper Bumper / SLJ-43',
        ]);

        DB::table('itemcheckgroups')->insert([
           'index' => '4.8',
            'CheckGroup' => 4,
            'ItemCheck' => 'Brkt Mtg SLJ-18 Assy Nut',
        ]);

        // Check Group 5
        DB::table('itemcheckgroups')->insert([
            'index' => '5.1',
            'CheckGroup' => 5,
            'ItemCheck' => 'Brkt Harness , RH side x 3',
        ]);

        DB::table('itemcheckgroups')->insert([
            'index' => '5.2',
            'CheckGroup' => 5,
            'ItemCheck' => 'Bracket / SGJ-22',
        ]);

        DB::table('itemcheckgroups')->insert([
            'index' => '6.1',
            'CheckGroup' => 5,
            'ItemCheck' => 'Bracket Clip Bintang x 2',
        ]);

        DB::table('itemcheckgroups')->insert([
            'index' => '6.2',
            'CheckGroup' => 5,
            'ItemCheck' => 'Bracket New x 3',
        ]);

        // Check Group 6 (Painting)
        DB::table('itemcheckgroups')->insert([
            'index' => '7.1',
            'CheckGroup' => 6,
            'ItemCheck' => 'Cat Belang',
        ]);

        DB::table('itemcheckgroups')->insert([
            'index' => '7.2',
            'CheckGroup' => 6,
            'ItemCheck' => 'Cat Bubble',
        ]);

        DB::table('itemcheckgroups')->insert([
            'index' => '7.3',
            'CheckGroup' => 6,
            'ItemCheck' => 'Vin Number',
        ]);

        DB::table('itemcheckgroups')->insert([
            'index' => '7.4',
            'CheckGroup' => 6,
            'ItemCheck' => 'Grease Shift Lev.',
        ]);

        DB::table('itemcheckgroups')->insert([
            'index' => '7.5',
            'CheckGroup' => 6,
            'ItemCheck' => 'Grease Pin Dumper',
        ]);
    }
}

