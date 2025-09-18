<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $fillable = [
        'name',
        'description',
        'sla',
    ];

    protected function casts(): array
    {
        return [
            'sla' => 'integer',
        ];
    }

    public function solicitations()
    {
        return $this->hasMany(Solicitation::class);
    }
}
