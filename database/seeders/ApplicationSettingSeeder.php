<?php

namespace Database\Seeders;

use App\Models\ApplicationSetting;
use Illuminate\Console\Application;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // General Settings
        ApplicationSetting::create([
            'key' => 'library_name',
            'value' => 'Laravel',
            'env_key' => 'APP_NAME',
        ]);
        ApplicationSetting::create([
            'key' => 'library_short_name',
            'value' => 'LV',
            'env_key' => 'APP_SORT_NAME',
        ]);
        ApplicationSetting::create([
            'key' => 'institution_name',
            'value' => 'laravel',
            'env_key' => 'INSTITUTION_NAME',
        ]);
        ApplicationSetting::create([
            'key' => 'timezone',
            'value' => 'Asia/Dhaka',
            'env_key' => 'TIMEZONE',
        ]);
        ApplicationSetting::create([
            'key' => 'date_format',
            'value' => 'd/m/Y',
            'env_key' => 'DATE_FORMAT',
        ]);
        ApplicationSetting::create([
            'key' => 'time_format',
            'value' => 'H:i:s',
            'env_key' => 'TIME_FORMAT',
        ]);
        ApplicationSetting::create([
            'key' => 'favicon',
            'value' => 'laravel',
            'env_key' => 'FAVICON',
        ]);
        ApplicationSetting::create([
            'key' => 'app_logo',
            'value' => '',
            'env_key' => 'APP_LOGO',
        ]);
        ApplicationSetting::create([
            'key' => 'theme_mode',
            'value' => 'light',
            'env_key' => 'THEME_MODE',
        ]);
        ApplicationSetting::create([
            'key' => 'public_registration',
            'value' => '1',
            'env_key' => 'PUBLIC_REGISTRATION',
        ]);
        ApplicationSetting::create([
            'key' => 'registration_approval',
            'value' => '1',
            'env_key' => 'REGISTRATION_APPROVAL',
        ]);
        ApplicationSetting::create([
            'key' => 'environment',
            'value' => '1',
            'env_key' => 'APP_ENV',
        ]);
        ApplicationSetting::create([
            'key' => 'app_debug',
            'value' => '1',
            'env_key' => 'APP_DEBUG',
        ]);
        ApplicationSetting::create([
            'key' => 'debugbar',
            'value' => '1',
            'env_key' => 'DEBUGBAR',
        ]);

        // Database Setting
        ApplicationSetting::create([
            'key' => 'database_driver',
            'value' => '1',
            'env_key' => 'DB_CONNECTION',
        ]);
        ApplicationSetting::create([
            'key' => 'database_host',
            'value' => '1',
            'env_key' => 'DB_HOST',
        ]);
        ApplicationSetting::create([
            'key' => 'database_port',
            'value' => '1',
            'env_key' => 'DB_PORT',
        ]);
        ApplicationSetting::create([
            'key' => 'database_name',
            'value' => '1',
            'env_key' => 'DB_DATABASE',
        ]);
        ApplicationSetting::create([
            'key' => 'database_username',
            'value' => '1',
            'env_key' => 'DB_USERNAME',
        ]);
        ApplicationSetting::create([
            'key' => 'database_password',
            'value' => '1',
            'env_key' => 'DB_PASSWORD',
        ]);

        // SMTP Setting
        ApplicationSetting::create([
            'key' => 'smtp_driver',
            'value' => 'smtp',
            'env_key' => 'MAIL_MAILER',
        ]);
        ApplicationSetting::create([
            'key' => 'smtp_host',
            'value' => '1',
            'env_key' => 'MAIL_HOST',
        ]);
        ApplicationSetting::create([
            'key' => 'smtp_port',
            'value' => '1',
            'env_key' => 'MAIL_PORT',
        ]);
        ApplicationSetting::create([
            'key' => 'smtp_encryption',
            'value' => 'tls',
            'env_key' => 'MAIL_ENCRYPTION',
        ]);
        ApplicationSetting::create([
            'key' => 'smtp_username',
            'value' => '123456',
            'env_key' => 'MAIL_USERNAME',
        ]);
        ApplicationSetting::create([
            'key' => 'smtp_password',
            'value' => '123',
            'env_key' => 'MAIL_PASSWORD',
        ]);
        ApplicationSetting::create([
            'key' => 'smtp_from_address',
            'value' => 'superadmin@gmail.com',
            'env_key' => 'MAIL_FROM_ADDRESS',
        ]);
        ApplicationSetting::create([
            'key' => 'smtp_from_name',
            'value' => 'Super Admin',
            'env_key' => 'MAIL_FROM_NAME',
        ]);
    }
}
