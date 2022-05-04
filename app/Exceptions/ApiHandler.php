<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait ApiHandler {
    public function tratarErros(Throwable $exception) {
        if($exception instanceof ModelNotFoundException){
            return $this->respostaPadrao(
                "Registro-nao-encontrado",
                "O sistema não encontrou o registro que você está buscando",
                404
            );
        }

        if($exception instanceof ValidationException) {
            return $this->respostaPadrao(
                "erro-validacao",
                "Os dados enviados são inválidos",
                400,
                $exception->errors()
            );
        }
    }

    public function respostaPadrao(string $code, string $messagem, int $status, array $erros = null) {
        $dadosResposta = [
            'code' => $code,
            'message' => $messagem,
            'status' => $status
        ];

        if($erros){
            $dadosResposta = $dadosResposta + ['erros' => $erros];
        }
        return response($dadosResposta, $status);
    }
}
