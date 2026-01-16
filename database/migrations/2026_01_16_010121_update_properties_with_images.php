<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Property;

class UpdatePropertiesWithImages extends Migration
{
    public function up()
    {
        $images = [
            'Park' => [
                'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=400&h=300&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=300&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1518837695005-2083093ee35b?w=400&h=300&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=400&h=300&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1569163139394-de4e4f43e4e5?w=400&h=300&fit=crop&crop=center'
            ],
            'Conference Room' => [
                'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=300&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1497366216548-37526070297c?w=400&h=300&fit=crop&crop=center'
            ],
            'Equipment' => [
                'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=400&h=300&fit=crop&crop=center',
                'https://images.unsplash.com/photo-1571171637578-41bc2dd41cd2?w=400&h=300&fit=crop&crop=center'
            ]
        ];

        $properties = Property::all();
        foreach ($properties as $property) {
            if (empty($property->image_url)) {
                $typeImages = $images[$property->type] ?? [];
                if (!empty($typeImages)) {
                    $randomImage = $typeImages[array_rand($typeImages)];
                    $property->update(['image_url' => $randomImage]);
                }
            }
        }
    }

    public function down()
    {
        Property::query()->update(['image_url' => null]);
    }
}