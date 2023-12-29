<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait SearchableLocal
{
    abstract public static function getSearchableLocal();
    // public static $searchableLocal = [];
    public function searchObj(string $searchStr)
    {
        $query = $this->query();
        foreach (static::getSearchableLocal() as $field) {
            $query->orWhere($field, 'LIKE', "%$searchStr%");
        }
        return $query;
    }

    public static function searchStatic(string $query)
    {
        // dd('test');
        $model = new self();
        return $model->searchObj($query);
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, ['increment', 'decrement', 'incrementQuietly', 'decrementQuietly'])) {
            return $this->$method(...$parameters);
        }

        if ($resolver = $this->relationResolver(static::class, $method)) {
            return $resolver($this);
        }

        if (Str::startsWith($method, 'through') &&
            method_exists($this, $relationMethod = Str::of($method)->after('through')->lcfirst()->toString())) {
            return $this->through($relationMethod);
        }

        if ($method === 'searchLocal') {
            return call_user_func([$this, 'searchObj'], $parameters[0]);
        }

        return $this->forwardCallTo($this->newQuery(), $method, $parameters);
    }

    /**
     * Handle dynamic static method calls into the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        if ($method === 'searchLocal') return call_user_func([static::class, 'searchStatic'], $parameters[0]);
        return (new static)->$method(...$parameters);
    }

    // public static function __callStatic(string $name, mixed $args)
    // {
    //     switch($name) {
    //         case 'search':
    //             call_user_func(['Customer', 'searchStatic'], $args[0]);
    //             break;
    //     }
    // }

}