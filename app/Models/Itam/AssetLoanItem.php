<?php

declare(strict_types=1);

namespace App\Models\Itam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetLoanItem extends Model
{
    use HasFactory;

    protected $table = 'itam_asset_loan_items';

    protected $fillable = [
        'loan_id',
        'asset_id',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(AssetLoan::class, 'loan_id');
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}
