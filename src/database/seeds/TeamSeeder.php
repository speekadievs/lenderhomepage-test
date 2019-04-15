<?php

use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teams = [
            [
                'name'    => 'Arizona Cardinals',
                'image'   => resource_path('seed_images/teams/arizona_cardinals.gif'),
                'players' => [
                    [
                        'first_name' => 'Robert',
                        'last_name'  => 'Alford',
                        'image'      => resource_path('seed_images/players/robert_alford.jpg')
                    ],
                    [
                        'first_name' => 'David',
                        'last_name'  => 'Amerson',
                        'image'      => resource_path('seed_images/players/david_amerson.jpg')
                    ]
                ]
            ],
            [
                'name'    => 'Atlanta Falcons',
                'image'   => resource_path('seed_images/teams/atlanta_falcons.gif'),
                'players' => [
                    [
                        'first_name' => 'Ricardo',
                        'last_name'  => 'Allen',
                        'image'      => resource_path('seed_images/players/ricardo_allen.jpg')
                    ],
                    [
                        'first_name' => 'Vic',
                        'last_name'  => 'Beasley Jr.',
                        'image'      => resource_path('seed_images/players/vic_beasley.jpg')
                    ]
                ]
            ],
            [
                'name'    => 'Baltimore Ravens',
                'image'   => resource_path('seed_images/teams/baltimore_ravens.gif'),
                'players' => [
                    [
                        'first_name' => 'Anthony ',
                        'last_name'  => 'Averett',
                        'image'      => resource_path('seed_images/players/anthony_averett.jpg')
                    ],
                    [
                        'first_name' => 'Mark',
                        'last_name'  => 'Andrews',
                        'image'      => resource_path('seed_images/players/mark_andrews.jpg')
                    ]
                ]
            ]
        ];

        foreach ($teams as $team) {
            $savedTeam = Team::create(array_except($team, ['image', 'players']));

            $image = new File($team['image']);
            $image = new UploadedFile($image, $image->getBasename(), $image->getMimeType(), null, null, true);

            $savedTeam->update([
                'image' => $image
            ]);

            foreach ($team['players'] as $player) {
                $savedPlayer = $savedTeam->players()->create(array_except($player, ['image']));

                $image = new File($player['image']);
                $image = new UploadedFile($image, $image->getBasename(), $image->getMimeType(), null, null, true);

                $savedPlayer->update([
                    'image' => $image
                ]);
            }
        }
    }
}
