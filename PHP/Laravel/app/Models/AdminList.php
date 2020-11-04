<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class AdminList.
 *
 * @package namespace App\Models;
 */
class AdminList extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string Primary key
     */
    protected $primaryKey = 'id_liste';

    /**
     * @var bool disable autoincrement
     */
    public $incrementing = false;

    /**
     * The table name for model
     *
     * @var string
     */

    /**
     * @var bool disable timestamps
     */
    public $timestamps = false;

    /**
     * The table name for model
     *
     * @var string
     */
    protected $table = 'admin_liste';



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_liste',
        'type_liste',
        'lib_liste',
        'langue'
    ];

}
