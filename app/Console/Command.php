<?php

namespace App\Console;

use Illuminate\Console\Command as BaseCommand;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class Command
 *
 * @package App\Console\Commands
 */
class Command extends BaseCommand
{
    use ProgressBarTrait;
    use CommandColors;

    /**
     * @var bool
     */
    protected $truncate = true;

    /**
     * @var bool
     */
    protected $echo = true;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var array
     */
    protected $tables = [];

    /**
     * Command constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $directory
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function fetchFiles($directory)
    {
        $this->iniCollection();
        Schema::disableForeignKeyConstraints();
        $files = $this->disk()->files($directory);
        asort($files);
        foreach ($files as $file) {
            $data = json_decode($this->disk()->get($file), true);
            $table = strtolower(Str::snake(Str::plural(pathinfo($file, PATHINFO_FILENAME))));
            $this->truncate($table);
            foreach ($data as $v) {
                $this->insert($v, $table);
            }
        }
    }

    /**
     * @param array $data
     *
     * @return \Illuminate\Support\Collection
     */
    protected function iniCollection($data = []): Collection
    {
        if (!$this->collection instanceof Collection) {
            $this->collection = Collection::make($data);
        }
        return $this->collection;
    }

    /**
     * @return \Illuminate\Contracts\Filesystem\Filesystem|\Illuminate\Filesystem\FilesystemAdapter
     */
    protected function disk()
    {
        return Storage::disk('setup');
    }

    /**
     * @param $table
     */
    protected function truncate($table)
    {
        if (!$this->truncate) {
            return;
        }
        $originalTable = $table;
        if ($this->isTruncated($table)) {
            return;
        }

        if (!Schema::hasTable($table)) {
            $found = false;
            foreach (['snake', 'camel', 'kebab'] as $method) {
                $table = Str::{$method}(Str::plural($table));
                if (Schema::hasTable($table)) {
                    $this->doTruncate($table);
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                if (Schema::hasTable(($t = 'c_' . Str::snake(Str::plural($originalTable))))) {
                    $this->doTruncate($t);
                }
                else {
                    $this->error("Table: {$originalTable}. not found");
                }
            }
        }
        else {
            $this->doTruncate($table);
        }
    }

    /**
     * @param $table
     *
     * @return bool
     */
    protected function isTruncated($table): bool
    {
        return in_array($table, $this->tables);
    }

    /**
     * @param $table
     */
    protected function doTruncate($table)
    {
        if ($this->isTruncated($table)) {
            return;
        }
        $this->error("truncated : {$table}");
        $this->tables[] = $table;
        DB::table($table)->truncate();
    }

    /**
     * @param $data
     * @param $table
     * @param null $model
     */
    protected function insert($data, $table, $model = null)
    {
        $this->iniCollection();
        $hasRelations = array_key_exists('data', $data);
        $insert = $hasRelations ? $data['data'] : $data;
        unset($data['data']);

        if (is_null($model)) {
            $namespaces = ['\\App\\Models'];
            $directories = Storage::disk('app')->directories('Models');
            foreach ($directories as $directory) {
                $namespaces[] = '\\App\\' . str_ireplace('/', '\\', $directory);
            }
            $class = ucfirst(Str::camel(Str::singular($table)));
            $model = null;
            foreach ($namespaces as $namespace) {
                $c = "{$namespace}\\{$class}";
                if (class_exists($c)) {
                    $model = $c;
                    break;
                }
            }
            $model = new $model();
            $insert = Arr::only($insert, $model->getFillable());
            $model->fill($insert);
            $model->save();
            $this->pushData($model);
        }
        else {
            // d($insert,$model,$model->getFillable());
            // $insert = Arr::only($insert, $model->getFillable());
            $model = $model->{$table}()->create($insert);
            $this->pushData($model);
        }
        $this->echo(class_basename($model) . " Inserted: {$model->id}");

        if ($hasRelations && count($data) > 0) {
            foreach ($data as $relation => $row) {
                if (Str::startsWith($relation, '_')) {
                    continue;
                }
                $this->truncate($relation);
                foreach ($row as $child) {
                    $this->insert($child, $relation, $model);
                }
            }
        }
    }

    /**
     * @param $model
     *
     * @return \Illuminate\Support\Collection
     */
    protected function pushData($model): Collection
    {
        $key = is_object($model) ? get_class($model) : $model;
        $this->iniCollection();
        if (!$this->collection->has($key)) {
            $this->collection->put($key, Collection::make());
        }
        /** @var Collection $data */
        $data = $this->collection->get($key);
        $data->push($model);
        $this->collection->put($key, $data);
        return $this->collection;
    }

    /**
     * @param string $text
     * @param string|array $method
     */
    protected function echo($text = '', $method = 'line')
    {
        if (app()->runningInConsole()) {
            if (!method_exists($this, $method)) {
                $this->l($text, $method);
            }
            else {
                $this->{$method}($text);
            }
        }
        else {
            if (!$this->echo) {
                return;
            }
            echo "{$text}<BR>";
        }
    }

    /**
     * @param $class
     *
     * @return Collection|null
     */
    protected function getCollection($class): ?Collection
    {
        return $this->collection->get($class);
    }

    /**
     * Check if command has truncate option. {--t|truncate : Truncate table}
     *
     * @return bool
     */
    protected function isTruncateOption(): bool
    {
        return (bool) $this->option('truncate');
    }

    /**
     * @param $name
     *
     * @return string
     */
    protected function parseFunctionName($name): string
    {
        return ucfirst(str_ireplace(['-', ':'], ' ', strtolower(Str::kebab($name))));
    }
}
