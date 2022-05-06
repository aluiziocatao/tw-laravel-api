<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait ApiHandler {
    /**
     * Trata os erros personalizados
     *
     * @param Throwable $exception
     * @return Response
     */
    public function tratarErros(Throwable $exception): Response {
        if ($exception instanceof ModelNotFoundException) {
            return $this->modelNotFoundException();
        }

        if ($exception instanceof ValidationException) {
            return $this->validationException($exception);
        }

        return false;
    }

    /**
     * Retorna o erro quando não encontrado o registro
     *
     * @return Response
     */
    public function modelNotFoundException(): Response {
        return $this->respostaPadrao(
            "Registro-nao-encontrado",
            "O sistema não encontrou o registro que você está buscando",
            404
        );
    }

    /**
     * Retorna o erro quando os dados não são válidos
     *
     * @param ValidationException $e
     * @return Response
     */
    public function validationException(ValidationException $e): Response {
        return $this->respostaPadrao(
            "erro-validacao",
            "Os dados enviados são inválidos",
            400,
            $e->errors()
        );
    }

    /**
     * Retorna uma resposta padrão para os erros da API
     *
     * @param string $code
     * @param string $messagem
     * @param int $status
     * @param array|null $erros
     * @return Response
     */
    public function respostaPadrao(string $code, string $messagem, int $status, array $erros = null): Response {
        $dadosResposta = [
            'code' => $code,
            'message' => $messagem,
            'status' => $status
        ];

        if ($erros) {
            $dadosResposta = $dadosResposta + ['erros' => $erros];
        }
        return response($dadosResposta, $status);
    }
}

