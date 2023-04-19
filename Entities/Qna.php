<?php

namespace Modules\Qna\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qna extends Model
{
    use HasFactory;

    protected $guarded = ['_token'];

    public function category()
    {
        return $this->belongsTo(QnaCategory::class, 'qna_category_id')->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'mb_id', 'mb_id');
    }

    public function files()
    {
        return $this->hasMany(QnaFile::class);
    }
}
