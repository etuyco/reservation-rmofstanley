<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Property;
use App\Models\Category;

class MigratePropertyTypesToCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Get all properties and update their category_id based on type
        $properties = Property::all();
        
        foreach ($properties as $property) {
            $category = Category::where('name', $property->type)->first();
            if ($category) {
                $property->update(['category_id' => $category->id]);
            }
        }
        
        // Now remove the type column since we're using category_id
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Add back the type column
        Schema::table('properties', function (Blueprint $table) {
            $table->enum('type', ['Park', 'Conference Room', 'Equipment'])->after('category_id');
        });
        
        // Populate type based on category_id
        $properties = Property::with('category')->get();
        
        foreach ($properties as $property) {
            if ($property->category) {
                $property->update(['type' => $property->category->name]);
            }
        }
    }
}
