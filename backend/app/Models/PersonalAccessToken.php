<?php

namespace App\Models;

use App\Models\Trait\Timezone;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use Timezone;

}
