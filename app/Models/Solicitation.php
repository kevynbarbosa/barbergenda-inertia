<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitation extends Model
{
    /** @use HasFactory<\Database\Factories\SolicitationFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'document',
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
}
