<?php

namespace App\Observers;

use App\Models\Recepcion;
use App\Services\AlerceService;

class RecepcionObserver
{
    /**
     * Handle the Recepcion "created" event.
     */


    /**
     * Handle the Recepcion "updated" event.
     */
    public function updating(Recepcion $recepcion): void
    {
        dd();
        if ($recepcion->isDirty('estado_recepcion_id')) {
            $nuevo = $recepcion->estado->descripcion;
            $anterior = $recepcion->getOriginal('estado_recepcion_id');

            if ($nuevo === 'UBIC') {
                $acta = app(AlerceService::class)->getActaUbicada($recepcion);

            }
        }
    }

    /**
     * Handle the Recepcion "deleted" event.
     */
    public function deleted(Recepcion $recepcion): void
    {
        //
    }

    /**
     * Handle the Recepcion "restored" event.
     */
    public function restored(Recepcion $recepcion): void
    {
        //
    }

    /**
     * Handle the Recepcion "force deleted" event.
     */
    public function forceDeleted(Recepcion $recepcion): void
    {
        //
    }
}
