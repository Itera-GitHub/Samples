<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Malhal\Geographical\Geographical;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class SystemCity.
 *
 * @package namespace App\Models;
 */
class SystemCity extends Model implements Transformable
{
    use TransformableTrait;
    use Geographical;

    /**
     * Geographical columns
     */
    const LATITUDE  = 'latitude';
    const LONGITUDE = 'longitude';

    /**
     * @var string Primary key
     */
    protected $primaryKey = 'nom';

    /**
     * @var bool disable autoincrement
     */
    public $incrementing = false;

    /**
     * @var bool disable timestamps
     */
    public $timestamps = false;

    /**
     * The table name for model
     *
     * @var string
     */
    protected $table = 'sys_villes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom',
        'maj',
        'cpostal',
        'cinsee',
        'cregion',
        'latitude',
        'longitude',
        'eloignement'
    ];

    /**
     * @var bool
     * Geographical use kilometers in haversine formula
     */
    protected static $kilometers = true;


}
