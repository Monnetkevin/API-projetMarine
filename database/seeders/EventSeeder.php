<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::create([
            'event_name' => 'Salon du livre lesbien 2014',
            'event_content' => 'blablabla bla bla bla bla bla blab blablablablablablablabla bla',
            'start_date'=> now(),
            'end_date' =>now(),
            // 'user_id' => 1,
        ]);
    }
}
