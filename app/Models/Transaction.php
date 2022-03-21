<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['status'];

    protected $fillable = ['total', 'due_on', 'vat', 'is_vat_inclusive', 'payer_id', 'category_id', 'sub_category_id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'due_on' => 'datetime:Y-m-d',
    ];

    public function records() {
        return $this->hasMany('App\Models\PaymentRecord', 'transaction_id');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function subCategory() {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    public function payer() {
        return $this->belongsTo(User::class, 'payer_id');
    }

    /**
     * Get The status
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function status(): Attribute
    {
        return new Attribute(
            get: function () {
                $totalPaid = $this->records()->sum('amount');
                if ($totalPaid == $this->total) {
                    return 'Paid';
                }

                if ($this->due_on->isPast()) {
                    return "Overdue";
                }

                return "Outstanding";
            },
        );
    }
}
