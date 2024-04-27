<?php

namespace Corcel\Model;

use Corcel\Model;
use Corcel\Model\Builder\TaxonomyBuilder;
use Corcel\Model\Meta\TermMeta;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

/**
 * Class Taxonomy
 *
 * @package Corcel\Model
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Taxonomy extends Model
{
    /**
     * @var string
     */
    protected $table = 'term_taxonomy';

    /**
     * @var string
     */
    protected $primaryKey = 'term_taxonomy_id';

    /**
     * @var array
     */
    protected $with = ['term'];

    /**
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'description',
        'taxonomy',
        'term_id',
        'parent',
        'count',
    ];

    /**
     * @return HasMany<TermMeta>
     */
    public function meta(): HasMany
    {
        return $this->hasMany(TermMeta::class, 'term_id');
    }
    
    /**
     * @return BelongsTo<Term>
     */
    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class, 'term_id');
    }

    /**
     * @return BelongsTo<Term>
     */
    public function parentTerm(): BelongsTo
    {
        return $this->belongsTo(Term::class, 'parent');
    }

    /**
     * @return BelongsToMany<Post>
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(
            Post::class,
            'term_relationships',
            'term_taxonomy_id',
            'object_id'
        );
    }

    /**
     * @param Builder $query
     * @return TaxonomyBuilder
     */
    public function newEloquentBuilder($query): TaxonomyBuilder
    {
        return new TaxonomyBuilder($query);
    }

    /**
     * @return TaxonomyBuilder
     */
    public function newQuery(): TaxonomyBuilder
    {
        return isset($this->taxonomy) && $this->taxonomy ?
            parent::newQuery()->where('taxonomy', $this->taxonomy) :
            parent::newQuery();
    }

    /**
     * Magic method to return the meta data like the post original fields.
     *
     * @param string $key
     * @return string
     */
    public function __get($key)
    {
        if (!isset($this->$key)) {
            if (isset($this->term->$key)) {
                return $this->term->$key;
            }
        }

        return parent::__get($key);
    }
}
