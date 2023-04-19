<?php

namespace Modules\Qna\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QnaCategory extends Model
{
    use HasFactory;

    protected $table = 'qna_categories';

    protected $guarded = ['_token'];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function qnas()
    {
        return $this->hasMany(Qna::class, 'qna_category_id', 'id');
    }
}
