<?php

namespace App\Rules;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Rule;

class Exists implements Rule
{
    /**
     * 表的名字
     *
     * @var string
     */
    protected $table;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $table)
    {
        $connection = config('database.default');

        $prefix = config("database.connections.${connection}.prefix");

        $this->table = $prefix . $table;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $result = DB::select("select id from {$this->table} where id = ?", [$value]);

        return count($result);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '记忆中模糊记得有这条记录，但是找不到了';
    }
}
