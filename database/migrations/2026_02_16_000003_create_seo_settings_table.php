<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
        
        // Insert default settings
        DB::table('seo_settings')->insert([
            ['key' => 'site_name', 'value' => config('app.name'), 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'title_template', 'value' => '{title} | {site}', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'default_description', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'default_og_image', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'twitter_handle', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'facebook_app_id', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'sitemap_enabled', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'robots_content', 'value' => "User-agent: *\nDisallow:", 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};
