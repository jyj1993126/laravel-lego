<?php namespace Lego\Widget\Grid\Concerns;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Lego\Widget\Grid\Batch;

trait HasBatch
{
    /**
     * @var Batch[]
     */
    protected $batches = [];
    protected $batchModeUrl;
    protected $batchModeSessionKey = 'lego.batch-mode';

    public function addBatch($name, \Closure $action = null, $primaryKey = 'id')
    {
        $batch = new Batch($name, $this->getQuery(), $primaryKey);
        $this->batches[$name] = $batch;

        if ($action) {
            $batch->action($action);
        }

        if ($this->batchModeEnabled()) {
            $this->addButton(self::BTN_LEFT_TOP, '退出批处理', function () {
                $this->disableBatchMode();
                return Redirect::back();
            });
        } else {
            $this->addButton(self::BTN_LEFT_TOP, '批处理模式', function () {
                $this->enableBatchMode();
                return Redirect::back();
            });
        }

        return $batch;
    }

    public function batches()
    {
        return $this->batches;
    }

    public function batchesAsArray()
    {
        $array = [];
        foreach ($this->batches as $batch) {
            $array[$batch->name()] = $batch->url();
        }
        return $array;
    }

    public function batch($name)
    {
        return $this->batches[$name];
    }

    public function enableBatchMode()
    {
        if (count($this->batches()) === 0) {
            return;
        }

        Session::put($this->batchModeSessionKey, true);
    }

    public function disableBatchMode()
    {
        Session::forget($this->batchModeSessionKey);
    }

    public function batchModeEnabled()
    {
        return count($this->batches()) && Session::get($this->batchModeSessionKey, false);
    }
}
