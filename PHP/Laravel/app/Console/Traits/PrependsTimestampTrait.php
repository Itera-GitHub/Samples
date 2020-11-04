<?php


namespace App\Console\Traits;


trait PrependsTimestampTrait
{
    protected function getPrependString($string)
    {
        return date(property_exists($this, 'outputTimestampFormat') ?
                $this->outputTimestampFormat : '[Y-m-d H:i:s]').' ';
    }
}
