<?php

namespace App\Models\Traits;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait SearchableLocal
{
    abstract public static function getSearchableLocal();

    private function getMonth(string $searchStr)
    {
        foreach (self::$MONTHS as $month) {
            if (str_contains($searchStr, $month)) return $month;
        }
        return false;
    }

    public static $MONTHS = [
        'January', 'Jan',
        'February',	'Feb',
        'March', 'Mar',
        'April', 'Apr',
        'May', 'May',
        'June', 'Jun',
        'July', 'Jul',
        'August', 'Aug',
        'September', 'Sep', 'Sept',
        'October', 'Oct',
        'November',	'Nov',
        'December',	'Dec',
    ];

    // public static $searchableLocal = [];
    public function searchObj(string $searchStr, array $searchable = [], array $searchableDate = [])
    {
        $query = $this->query();
        // $customer = new Customer();
        // $query = $customer->query();
        // $query = DB::table('customers');

        if (in_array('id', $searchable)) {
            $query->orWhere('id', $searchStr);
            $searchable = array_diff($searchable, ['id']);
        }

        // Check if searchStr is a date
        if ($unix = strtotime($searchStr)) {
            if (($date = \DateTime::createFromFormat('M', $searchStr)) || ($date = \DateTime::createFromFormat('F', $searchStr))) {
                // Contains only month
                $month = date('m', strtotime("$searchStr-2000"));
                foreach ($searchableDate as $dateField) {
                    $query->orWhereMonth($dateField, $month);
                }
            // Check if contains a year
            } else if (preg_match('/(19|20)\d{2}/', $searchStr)) {
                $date = date('Y-m-d', $unix);
                foreach($searchableDate as $dateField) {
                    $query->orWheredate($dateField, $date);
                }
            } else if ($month = $this->getMonth($searchStr)) {
                // Does not contain a year, but contains a month and day
                $monthNum = date('m', strtotime($month));
                foreach ($searchableDate as $dateField) {
                    $query->orWhereMonth($dateField, $monthNum);
                }
            } else {
                dd($searchStr, $unix); // edge cases not sure what to do with
            }
        }

        foreach ($searchable as $field) {
            $query->orWhere($field, 'LIKE', "%$searchStr%");
        }
        return $query;
    }

    public static function searchStatic(string $searchStr, array $searchable = [], array $searchableDate = [])
    {
        $model = new self();
        return $model->searchObj($searchStr, $searchable, $searchableDate);
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
            return call_user_func([$this, 'searchObj'], ...$parameters);
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
        if ($method === 'searchLocal') return call_user_func([static::class, 'searchStatic'], ...$parameters);
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