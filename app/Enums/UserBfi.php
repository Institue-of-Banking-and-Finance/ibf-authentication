<?php

namespace App\Enums;

class UserBfi
{
    public static function userBfi(): array
    {
        return [
            'frontend'=> 'Frontend',
            'backend' => 'Backend',
            'devops' => 'Devops',
            'tools' => 'Tools',
            'integrations' => 'Integrations',
        ];
    }
}
