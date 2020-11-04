<?php


namespace App\Console\Traits;


trait PrependOutputTrait
{
    public function line($string, $style = NULL, $verbosity = NULL)
    {
        parent::line($this->prepend($string),$style,$verbosity);
    }

    protected function prepend($string)
    {
        if (method_exists($this, 'getPrependString')) {
            return $this->getPrependString($string).$string;
        }
        return $string;
    }
}
