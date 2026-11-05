<?php

namespace Database\Seeders;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
        $user = User::create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $php = Thread::create([
            'title' => 'PHP',
            'description' => 'Vragen over PHP, bijvoorbeeld over arrays, functies en het maken van een API zonder framework. Stel hier je vraag als je ergens niet uitkomt of als je iets wilt laten controleren door andere studenten.',
            'user_id' => $admin->id,
        ]);
        $laravel = Thread::create([
            'title' => 'Laravel',
            'description' => 'Vragen over Laravel, bijvoorbeeld over routes, controllers, models, migrations en Blade. Hier kun je ook tips delen die jou hebben geholpen bij het bouwen van je eigen applicatie.',
            'user_id' => $admin->id,
        ]);
        Thread::create([
            'title' => 'React',
            'description' => 'Vragen over React, bijvoorbeeld over components, props en state. Ook vragen over Redux Toolkit en React Router horen in deze thread thuis.',
            'user_id' => $admin->id,
        ]);

        $getPost = Topic::create([
            'title' => 'Wat is het verschil tussen GET en POST?',
            'body' => 'Ik snap niet goed wanneer ik GET gebruik en wanneer POST. Kan iemand dit uitleggen?',
            'thread_id' => $php->id,
            'user_id' => $user->id,
        ]);
        $migration = Topic::create([
            'title' => 'Wat doet een migration?',
            'body' => 'Waarom maak ik een tabel met een migration en niet gewoon in phpMyAdmin?',
            'thread_id' => $laravel->id,
            'user_id' => $user->id,
        ]);

        Reply::create([
            'body' => 'Met GET haal je gegevens op. Met POST stuur je gegevens om iets nieuws aan te maken.',
            'topic_id' => $getPost->id,
            'user_id' => $admin->id,
        ]);
        Reply::create([
            'body' => 'Een migration staat in je code. Zo kan iedereen in het team dezelfde tabellen maken.',
            'topic_id' => $migration->id,
            'user_id' => $admin->id,
        ]);
    }
}
