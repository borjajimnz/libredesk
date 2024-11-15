<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->text('key')->index();
            $table->jsonb('data');
            $table->timestamps();
        });


        \App\Models\Settings::query()->create([
            'key' => 'name',
            'data' => [
                'type' => 'text',
                'text_value' => 'LibreDesk',
                'value' => 'LibreDesk'
            ],
        ]);

        \App\Models\Settings::query()->create([
            'key' => 'language',
            'data' => [
                'type' => 'select',
                'text_value' => 'en',
                'value' => 'en'
            ],
        ]);

        \App\Models\Settings::query()->create([
            'key' => 'allowed_register_domains',
            'data' => [
                'type' => 'options',
                'options_value' => ['example.com', 'example.org'],
                'value' => ['example.com', 'example.org']
            ],
        ]);

        \App\Models\Settings::query()->create([
            'key' => 'logo',
            'data' => [
                'type' => 'image',
                'image_value' => '',
                'value' => ''
            ],
        ]);

        \App\Models\Settings::query()->create([
            'key' => 'theme_color',
            'data' => [
                'type' => 'color',
                'color_value' => 'blue',
                'value' => 'blue'
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
