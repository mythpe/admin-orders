<?php

namespace App\Console;

use Symfony\Component\Console\Helper\ProgressBar;

trait ProgressBarTrait
{

    /**
     * @var
     */
    protected $bar;

    /**
     * @param int|null $max
     *
     * @return $this
     */
    protected function startBar(int $max = null): self
    {
        $this->getBar()->start($max);
        $this->line('');
        return $this;
    }

    /**
     * @return \Symfony\Component\Console\Helper\ProgressBar
     */
    protected function getBar(): ?ProgressBar
    {
        return $this->bar;
    }

    /**
     * @param int $max
     *
     * @return $this
     */
    protected function setBar(int $max = 0): self
    {
        $this->bar = $this->output->createProgressBar($max);
        return $this;
    }

    /**
     * @return $this
     */
    protected function finishBar(): self
    {
        $this->getBar()->finish();
        $this->line(' ');
        $this->alert('Finish');
        return $this;
    }

    /**
     * @param int $step
     *
     * @return \Symfony\Component\Console\Helper\ProgressBar
     */
    protected function advanceBar(int $step = 1): ProgressBar
    {
        $this->getBar()->advance($step);
        $this->line('');
        return $this->getBar();
    }
}
