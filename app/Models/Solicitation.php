<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitation extends Model
{
    /** @use HasFactory<\Database\Factories\SolicitationFactory> */
    use HasFactory;

    protected $fillable = [
        'person_id',
        'status',
        'stage_id',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
