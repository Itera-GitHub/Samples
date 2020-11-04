<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class CandidateTag.
 *
 * @package namespace App\Models;
 */
class CandidateTag extends Model implements Transformable
{
    use TransformableTrait;

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
    protected $table = 'cv_tag_candidat';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_tag',
        'id_candidat',
        'id_plateforme',
        'id_contact',
        'id_entreprise',
        'id_offre'
    ];

}
