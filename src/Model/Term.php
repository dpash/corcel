<?php

namespace Corcel\Model;

use Corcel\Concerns\AdvancedCustomFields;
use Corcel\Concerns\MetaFields;
use Corcel\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Term.
 *
 * @package Corcel\Model
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Term extends Model
{
    use MetaFields;
    use AdvancedCustomFields;

    protected $fillable = [
        'name',
        'slug',
        'term_group',
    ];

    /**
     * @var string
     */
    protected $table = 'terms';

    /**
     * @var string
     */
    protected $primaryKey = 'term_id';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return HasOne<Taxonomy>
     */
    public function taxonomy(): HasOne
    {
        return $this->hasOne(Taxonomy::class, 'term_id');
    }
}
