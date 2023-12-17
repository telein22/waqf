<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use HasFactory;

    protected $fillable = [
        'specialty_en', 'specialty_ar', 'icon'
    ];

    public $timestamps = false;

    public function getIconAttribute($icon)
    {
        $awsBaseUrl = config('services.aws.base_url');

        return "{$awsBaseUrl}/images/specialities/{$icon}";
    }

}
