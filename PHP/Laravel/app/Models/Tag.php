<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Tag.
 *
 * @package namespace App\Models;
 */
class Tag extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * @var string Primary key
     */
    protected $primaryKey = 'id_tag';

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
    protected $table = 'cv_tag';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_tag',
        'nom_tag',
        'id_plateforme',
        'tag_search'
    ];

    /**
     * Hide pivot columns
     * @var array
     */
    protected $hidden = ['pivot'];



    /**
     * ManyToMany Relations for candidates tags
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function candidates()
    {
        return $this->belongsToMany(Candidate::class,'cv_tag_candidat','id-tag','id_candidat');
    }

}
