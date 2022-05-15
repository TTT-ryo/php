<?php
// MVCのModel
// DBとの連携を行う
declare(strict_types=1);

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory; コメントアウト
use Illuminate\Database\Eloquent\Model;

/**
 *  Todo class
 */
class Todo extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['title', 'content'];

    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    // use HasFactory; コメントアウト
}
