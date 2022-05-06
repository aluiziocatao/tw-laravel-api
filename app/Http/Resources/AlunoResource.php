<?php

namespace App\Http\Resources;

use App\Services\LinksGenerator;
use Illuminate\Http\Resources\Json\JsonResource;

class AlunoResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request) {
        $links = new LinksGenerator();
        $links->get(route('alunos.show', $this->id), 'aluno_detalhes');
        $links->put(route('alunos.update', $this->id), 'aluno_atualizar');
        $links->delete(route('alunos.destroy', $this->id), 'aluno_remover');

        return [
            'id' => $this->id,
            'nome_aluno' => $this->nome,
            'nascimento' => $this->nascimento,
            'genero' => $this->genero,
            'turma' => new TurmaResource($this->whenLoaded('turma')),
            'links' => $links->toArray()
        ];
    }
}
