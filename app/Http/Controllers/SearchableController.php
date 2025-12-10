<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

abstract class SearchableController extends Controller
{
    abstract function getQuery(): Builder;

    function prepareCriteria(array $criteria): array
    {
        return [
            'term' => null,
            ...$criteria,
        ];
    }

    function applyWhereToFilterByTerm(Builder $query, string $word): void
    {
        $query
            ->orWhere('patient_name', 'LIKE', "%{$word}%");
    }

    function filterByTerm(Builder|Relation $query, ?string $term,): Builder|Relation
    {
        if (!empty($term)) {
            foreach (\preg_split('/\s+/', \trim($term)) as $word) {
                $query->where(function (Builder $innerQuery) use ($word): void {
                    $this->applyWhereToFilterByTerm($innerQuery, $word);
                });
            }
        }

        return $query;
    }

    function filter(Builder|Relation $query, array $criteria,): Builder|Relation
    {
        return $this->filterByTerm($query, $criteria['term']);
    }

    function search(array $criteria): Builder
    {
        $query = $this->getQuery();
        return $this->filter($query, $criteria);
    }

    // For easily searching by code.
    function find(string $id): Model
    {
        return $this->getQuery()
        ->orWhere('dentist_id', $id)
        ->firstOrFail();
    }
}
