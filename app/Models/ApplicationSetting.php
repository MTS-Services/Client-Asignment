<?php

namespace App\Models;

use App\Models\BaseModel;

class ApplicationSetting extends BaseModel
{
    protected $fillable = ['key', 'value', 'env_key'];

    public const ALLOW_PUBLIC_REGISTRATION = 1;
    public const DENY_PUBLIC_REGISTRATION = 2;

    public static function getPublicRegistrationInfos()
    {
        return [
            self::ALLOW_PUBLIC_REGISTRATION => 'Allow',
            self::DENY_PUBLIC_REGISTRATION => 'Deny',
        ];
    }

    // public function getPublicRegistrationLabelAttribute()
    // {
    //     return $this->key == 'public_registration' ? self::getPublicRegistrationInfos()[$this->value] : 'Unknown';
    // }

    public const REGISTRATION_APPROVAL_AUTO = 1;
    public const REGISTRATION_APPROVAL_MANUAL = 2;

    public static function getRegistrationApprovalInfos()
    {
        return [
            self::REGISTRATION_APPROVAL_AUTO => 'Auto',
            self::REGISTRATION_APPROVAL_MANUAL => 'Manual',
        ];
    }

    // public function getRegistrationApprovalLabelAttribute()
    // {
    //     return $this->key == 'registration_approval' ? self::getRegistrationApprovalInfos()[$this->value] : 'Unknown';
    // }

    public const ENVIRONMENT_PRODUCTION = 'production';
    public const ENVIRONMENT_DEVELOPMENT = 'development';

    public static function getEnvironmentInfos()
    {
        return [
            self::ENVIRONMENT_PRODUCTION => 'Production',
            self::ENVIRONMENT_DEVELOPMENT => 'Local',
        ];
    }

    // public function getEnvironmentLabelAttribute()
    // {
    //     return $this->key == 'environment' ? self::getEnvironmentInfos()[$this->value] : 'Unknown';
    // }

    public const APP_DEBUG_ON = 1;
    public const APP_DEBUG_OFF = 2;

    public static function getAppDebugInfos()
    {
        return [
            self::APP_DEBUG_OFF => 'False',
            self::APP_DEBUG_ON => 'True',
        ];
    }

    // public function getAppDebugLabelAttribute()
    // {
    //     return $this->key == 'app_debug' ? self::getAppDebugInfos()[$this->value] : 'Unknown';
    // }

    public const ENABLE_DEBUGBAR = 1;
    public const DISABLE_DEBUGBAR = 2;

    public static function getDebugbarInfos()
    {
        return [
            self::DISABLE_DEBUGBAR => 'False',
            self::ENABLE_DEBUGBAR => 'True',
        ];
    }

    // public function getDebugbarLabelAttribute()
    // {
    //     return $this->key == 'debugbar' ? self::getDebugbarInfos()[$this->value] : 'Unknown';
    // }

    public const DATE_FORMAT_ONE = 'Y-m-d';
    public const DATE_FORMAT_TWO = 'd/m/Y';
    public const DATE_FORMAT_THREE = 'm/d/Y';

    public static function getDateFormatInfos()
    {
        return [
            self::DATE_FORMAT_ONE => 'YYYY-MM-DD',
            self::DATE_FORMAT_TWO => 'DD/MM/YYYY',
            self::DATE_FORMAT_THREE => 'MM/DD/YYYY',
        ];
    }

    // public function getDateFormatLabelAttribute()
    // {
    //     return $this->key == 'date_format' ? self::getDateFormatInfos()[$this->value] : 'Unknown';
    // }

    public const TIME_FORMAT_12 = 'H:i:s';
    public const TIME_FORMAT_24 = 'H:i:s A';

    public static function getTimeFormatInfos()
    {
        return [
            self::TIME_FORMAT_12 => '12-hour format (HH:mm:ss AM/PM)',
            self::TIME_FORMAT_24 => '24-hour format (HH:mm:ss)',
        ];
    }

    // public function getTimeFormatLabelAttribute()
    // {
    //     return $this->key == 'time_format' ? self::getTimeFormatInfos()[$this->value] : 'Unknown';
    // }

    public const THEME_MODE_SYSTEM = 'system';
    public const THEME_MODE_LIGHT = 'light';
    public const THEME_MODE_DARK = 'dark';

    public static function getThemeModeInfos()
    {
        return [
            self::THEME_MODE_SYSTEM => 'System',
            self::THEME_MODE_LIGHT => 'Light',
            self::THEME_MODE_DARK => 'Dark',
        ];
    }

    // public function getThemeLabelAttribute()
    // {
    //     return $this->key == 'theme' ? self::getThemeInfos()[$this->value] : 'Unknown';
    // }
}
