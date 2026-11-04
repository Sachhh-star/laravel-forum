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
        $thread = Thread::create([
            'title' => 'Test Thread',
            'description' => 'This is a test thread.',
            'user_id' => $admin->id,
        ]);
        $topic = Topic::create([
            'title' => 'Test Topic',
            'body' => 'This is a test topic.',
            'thread_id' => $thread->id,
            'user_id' => $user->id,
        ]);
        $reply = Reply::create([
            'body' => 'This is a test reply.',
            'topic_id' => $topic->id,
            'user_id' => $admin->id,
        ]);

    }
}
