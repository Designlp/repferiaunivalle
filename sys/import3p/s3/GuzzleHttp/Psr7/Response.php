<?php
namespace GuzzleHttp\Psr7;

use Psr\Http\Message\ResponseInterface;

/**
 * PSR-7 response implementation.
 */
class Response implements ResponseInterface
{
    use MessageTrait;

    /** @var array Map of standard HTTP status code/reason phrases */
    private static $phrases = [
        100 => 'Continuar',
        101 => 'Protocolos de Switching',
        102 => 'Procesando',
        200 => 'OK',
        201 => 'Creado',
        202 => 'Aceptado',
        203 => 'Informacion no Autorizada',
        204 => 'Sin contenido',
        205 => 'Restablecer contenido',
        206 => 'Contenido parcial',
        207 => 'Multi-estado',
        208 => 'Ya informado',
        300 => 'Múltiples opciones',
        301 => 'Movido permanentemente',
        302 => 'Encontrado',
        303 => 'Ver otro',
        304 => 'No modificado',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'No autorizado',
        402 => 'Pago requerido',
        403 => 'Olvidado',
        404 => 'No encontrado',
        405 => 'Metodo no permitido',
        406 => 'No aceptable',
        407 => 'Requiere actualización de Proxy',
        408 => 'Solicitar tiempo de espera',
        409 => 'Conflicto',
        410 => 'Se ha ido',
        411 => 'Longitud requerida',
        412 => 'Condición previa Falló',
        413 => 'Solicitar entidad demasiado grande',
        414 => 'Request-URI demasiado grande',
        415 => 'Tipo de papel no admitido',
        416 => 'Rango solicitado no satisfactorio',
        417 => 'Expectativa fallida',
        418 => 'Yo\'soy una tetera',
        422 => 'Entidad no procesable',
        423 => 'Bloqueado',
        424 => 'Dependencia fallida',
        425 => 'Colección desordenada',
        426 => 'Se requiere actualización',
        428 => 'Requisito previo',
        429 => 'Demasiadas solicitudes',
        431 => 'Campos de encabezado de solicitud demasiado grandes',
        451 => 'No disponible por motivos legales',
        500 => 'Error de servidor interno',
        501 => 'No implementada',
        502 => 'Puerta de enlace incorrecta',
        503 => 'Servicio no disponible',
        504 => 'Tiempo de espera de puerta de enlace',
        505 => 'La variante también negocia',
        506 => 'Variant Also Negotiates',
        507 => 'Espacio insuficiente',
        508 => 'Bucle detectado',
        511 => 'Se requiere autenticación de red',
    ];

    /** @var string */
    private $reasonPhrase = '';

    /** @var int */
    private $statusCode = 200;

    /**
     * @param int                                  $status  Status code
     * @param array                                $headers Response headers
     * @param string|null|resource|StreamInterface $body    Response body
     * @param string                               $version Protocol version
     * @param string|null                          $reason  Reason phrase (when empty a default will be used based on the status code)
     */
    public function __construct(
        $status = 200,
        array $headers = [],
        $body = null,
        $version = '1.1',
        $reason = null
    ) {
        $this->statusCode = (int) $status;

        if ($body !== '' && $body !== null) {
            $this->stream = stream_for($body);
        }

        $this->setHeaders($headers);
        if ($reason == '' && isset(self::$phrases[$this->statusCode])) {
            $this->reasonPhrase = self::$phrases[$status];
        } else {
            $this->reasonPhrase = (string) $reason;
        }

        $this->protocol = $version;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getReasonPhrase()
    {
        return $this->reasonPhrase;
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        $new = clone $this;
        $new->statusCode = (int) $code;
        if ($reasonPhrase == '' && isset(self::$phrases[$new->statusCode])) {
            $reasonPhrase = self::$phrases[$new->statusCode];
        }
        $new->reasonPhrase = $reasonPhrase;
        return $new;
    }
}
