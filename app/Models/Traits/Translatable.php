<?php

namespace app\Models\Traits;

use Illuminate\Support\Facades\App;

trait Translatable
{
    protected $defaultLocale = 'ru';

    public function __($fieldName) {
        $locale = App::getLocale() ?? $this->defaultLocale;
        $localeFiledName = $fieldName . '_' . $locale;

        if (!array_key_exists($localeFiledName, $this->attributes)) {
            throw new \LogicException('no such attribute for model ' . get_class($this));
        }

        $result = $this->$localeFiledName;

        return !empty($result) ? $result : $this->$fieldName;
    }
}