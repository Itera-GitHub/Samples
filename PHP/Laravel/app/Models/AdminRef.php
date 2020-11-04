<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class AdminRef.
 *
 * @package namespace App\Models;
 */
class AdminRef extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string Primary key
     */
    protected $primaryKey = 'id_ref';

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
    protected $table = 'admin_ref';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_ref',
        'id_liste',
        'lib_ref',
        'parent_id_ref',
        'pos_ref',
        'desc_ref',
        'statut_ref',
        'color',
        'statut_offre_ref',
        'xml_file_ref',
        'url_plateforme',
        'active_offre_ref'
    ];

}
