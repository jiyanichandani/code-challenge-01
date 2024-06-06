<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;

class Bill extends Model
{

    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'bill_reference', 'bill_date', 'submitted_at', 'approved_at', 'on_hold_at', 'bill_stage_id',
    ];
    public function stage()
    {
        return $this->belongsTo(BillStage::class, 'bill_stage_id');
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'bill_user');
    }
    public function scopeByStatus(Builder $query, string $status)
    {
        return $query->whereHas('stage', function($query) use ($status) {
            $query->where('label', $status);
        })->with('stage');
    }
    public static function BillCountByStatus(string $status = 'Submitted')
    {
        $countWithColor = self::byStatus($status)->get();
        return [
            'count' => $countWithColor->count(),
            'color_name' => $countWithColor->isNotEmpty() ? $countWithColor->first()->stage->color_name : null
        ];
    }
}
