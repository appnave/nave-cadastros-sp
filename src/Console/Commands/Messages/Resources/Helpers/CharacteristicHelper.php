<?php

namespace BildVitta\SpProduto\Console\Commands\Messages\Resources\Helpers;

use BildVitta\SpProduto\Models\Characteristic;
use stdClass;

trait CharacteristicHelper
{
    private function characteristicUpdateOrCreate(stdClass $message): void
    {
        Characteristic::updateOrCreate([
            'uuid' => $message->uuid,
        ], [
            'uuid'           => $message->uuid,
            'name'           => $message->name,
            'description'    => $message->description,
            'icon'           => $message->icon,
            'hub_company_id' => $this->hubCompanyId($message),
        ]);
    }

    private function characteristicDelete(stdClass $message): void
    {
        Characteristic::where('uuid', $message->uuid)->delete();
    }
}
