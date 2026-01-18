<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPropertiesTableAddCategoryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            // Add category_id column
            $table->foreignId('category_id')->nullable()->after('name')->constrained()->onDelete('set null');
            
            // Add image columns if they don't exist
            if (!Schema::hasColumn('properties', 'image_url')) {
                $table->string('image_url')->nullable()->after('description');
            }
            if (!Schema::hasColumn('properties', 'image_path')) {
                $table->string('image_path')->nullable()->after('image_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
}
