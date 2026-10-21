<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates a catalog of rentable/requestable logistical items
     * (chairs, tables, sound system, LED wall, etc.) that admins
     * can manage and guests can select during the reservation wizard.
     */
    public function up(): void
    {
        Schema::create('logistical_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');                            // e.g. "Monoblock Chairs"
            $table->string('category')->nullable();            // e.g. "Furniture", "Audio/Visual"
            $table->text('description')->nullable();           // Optional description
            $table->decimal('price', 10, 2)->default(0);       // Per-unit price (0 = free/included)
            $table->string('unit_label')->default('unit');      // e.g. "unit", "set", "piece"
            $table->integer('max_quantity')->default(100);      // Maximum requestable quantity
            $table->boolean('is_active')->default(true);        // Admin can deactivate
            $table->integer('sort_order')->default(0);          // Display ordering
            $table->timestamps();
        });

        // Seed default logistical items
        $now = now();
        $defaults = [
            ['name' => 'Monoblock Chairs',         'category' => 'Furniture',    'price' => 0,    'unit_label' => 'piece', 'max_quantity' => 500, 'sort_order' => 1],
            ['name' => 'Long Tables',               'category' => 'Furniture',    'price' => 0,    'unit_label' => 'piece', 'max_quantity' => 50,  'sort_order' => 2],
            ['name' => 'Round Tables',              'category' => 'Furniture',    'price' => 0,    'unit_label' => 'piece', 'max_quantity' => 20,  'sort_order' => 3],
            ['name' => 'Sound System',              'category' => 'Audio/Visual', 'price' => 3000, 'unit_label' => 'set',   'max_quantity' => 1,   'sort_order' => 4],
            ['name' => 'LED Wall',                  'category' => 'Audio/Visual', 'price' => 5000, 'unit_label' => 'set',   'max_quantity' => 1,   'sort_order' => 5],
            ['name' => 'Projector + Screen',        'category' => 'Audio/Visual', 'price' => 1500, 'unit_label' => 'set',   'max_quantity' => 2,   'sort_order' => 6],
            ['name' => 'Wireless Microphone',       'category' => 'Audio/Visual', 'price' => 500,  'unit_label' => 'piece', 'max_quantity' => 4,   'sort_order' => 7],
            ['name' => 'Lectern / Podium',          'category' => 'Staging',      'price' => 0,    'unit_label' => 'piece', 'max_quantity' => 1,   'sort_order' => 8],
            ['name' => 'Stage / Platform',          'category' => 'Staging',      'price' => 2000, 'unit_label' => 'set',   'max_quantity' => 1,   'sort_order' => 9],
            ['name' => 'Generator Set',             'category' => 'Utilities',    'price' => 5000, 'unit_label' => 'set',   'max_quantity' => 1,   'sort_order' => 10],
            ['name' => 'Extension Cords / Outlets', 'category' => 'Utilities',    'price' => 0,    'unit_label' => 'piece', 'max_quantity' => 10,  'sort_order' => 11],
        ];

        foreach ($defaults as $item) {
            DB::table('logistical_items')->insert(array_merge($item, [
                'is_active'   => true,
                'description' => null,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logistical_items');
    }
};
