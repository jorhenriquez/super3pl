<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Pedido;
use App\Models\Recepcion;

class AlerceService
{
    protected $baseUrl;
    protected $token_name;
    protected $token_stock;
    protected $token_estadopedidos;
    protected $delegacion;
    protected $codigo_cliente;
    protected $token_actas_estandar;
    protected $token_actas_ubicadas;

    public function __construct()
    {
        $this->baseUrl = config('services.alerce.base_url');
        $this->token_name = config('services.alerce.token_name');
        $this->token_stock = config('services.alerce.token_stock');
        $this->token_estadopedidos = config('services.alerce.token_estadopedidos');
        $this->delegacion = config('services.alerce.delegacion');
        $this->codigo_cliente = config('services.alerce.codigo_cliente');
        $this->token_actas_estandar = config('services.alerce.token_actas_estandar');
        $this->token_actas_ubicadas = config('services.alerce.token_actas_ubicadas');
    }

    public function getStock()
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            $this->token_name => $this->token_stock,
        ];

        $body = [
            'delegacion' => $this->delegacion,
        ];

        $response = Http::withHeaders($headers)
            ->post($this->baseUrl.'ExportacionStockUbicadoEstandar/exportar/'.$this->codigo_cliente, $body);

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }

    public function getPedidos()
    {
        $url = $this->baseUrl . 'ExportacionEstadosAlbaranesEstandar/exportar/'.$this->codigo_cliente;
        $body = [
            'delegacion' => $this->delegacion,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
            $this->token_name => $this->token_estadopedidos,
        ])->post($url,$body);
     
        if ($response->successful()) {
            
            return $response->json()['datos'] ?? [];
        }

        return [];
    }

    public function getActas()
    {
        $url = $this->baseUrl . 'ExportacionEstadosActasEstandar/exportar/'.$this->codigo_cliente;
        $body = [
            'delegacion' => $this->delegacion,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
            $this->token_name => $this->token_actas_estandar,
        ])->post($url,$body);
     

        if ($response->successful()) {
            
            return $response->json()['datos'] ?? [];
        }

        return [];
    }

    public function getActaUbicada(Recepcion $recepcion)
    {
        $url = $this->baseUrl . 'ExportacionEstadosActasEstandar/exportar/'.$this->codigo_cliente;
        $body = [
            'delegacion' => $this->delegacion,
            'documento' => $recepcion->referencia,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
            $this->token_name => $this->token_actas_ubicadas,
        ])->post($url,$body);
     

        if ($response->successful()) {
            
            return $response->json()['datos'] ?? [];
        }

        return [];
    }

    public function getActasUbicadas()
    {
        $url = $this->baseUrl . 'ExportacionEstadosActasEstandar/exportar/'.$this->codigo_cliente;
        $body = [
            'delegacion' => $this->delegacion,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
            $this->token_name => $this->token_actas_ubicadas,
        ])->post($url,$body);
     

        if ($response->successful()) {
            
            return $response->json()['datos'] ?? [];
        }

        return [];
    }

    public function enviarPedido($pedidos)
    {
        // Normalizamos: si es un solo pedido, lo convertimos en array
        if ($pedidos instanceof Pedido) {
            $pedidos = [$pedidos];
        }

        // Armamos el array con todos los pedidos
        $body = collect($pedidos)->map(function ($pedido) {
            return [
                'numero_pedido' => $pedido->numero_pedido,
                'fecha_entrega' => $pedido->fecha_entrega,
                'lineas' => $pedido->lineas->map(function ($linea) {
                    return [
                        'codigo_wms' => $linea->product->wms_code,
                        'cantidad'   => $linea->cantidad,
                    ];
                })->toArray(),
            ];
        })->toArray();

        // Llamada a la API
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ])->post($this->baseUrl . '/pedidos', [
            'pedidos' => $body
        ]);

        return $response->json();
    }


}
