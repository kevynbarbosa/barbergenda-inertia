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
        'estimated_completion_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'estimated_completion_at' => 'datetime',
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

    public function checkIfOverdue(): bool
    {
        if (!$this->estimated_completion_at) {
            return false;
        }

        return now()->isAfter($this->estimated_completion_at);
    }

    public function getSlaStatus(): string
    {
        if (!$this->estimated_completion_at) {
            return 'unknown';
        }

        return $this->checkIfOverdue() ? 'overdue' : 'on_time';
    }

    public function getSlaStatusColor(): string
    {
        return match ($this->getSlaStatus()) {
            'overdue' => 'text-orange-500',
            'on_time' => 'text-green-500',
            default => 'text-gray-400',
        };
    }

    public function calculateEstimatedCompletion(): void
    {
        if ($this->stage && $this->stage->sla) {
            $this->estimated_completion_at = $this->created_at->addHours($this->stage->sla);
            $this->save();
        }
    }

    protected function isOverdue(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn () => $this->checkIfOverdue(),
        );
    }

    protected function slaStatus(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn () => $this->getSlaStatus(),
        );
    }

    protected function slaStatusColor(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn () => $this->getSlaStatusColor(),
        );
    }
}
