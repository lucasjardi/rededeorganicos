<?php

namespace App\Mail;

use App\Solicitacao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitacaoAprovada extends Mailable
{
    use Queueable, SerializesModels;

    private $solicitacao;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Solicitacao $solicitacao)
    {
        $this->solicitacao = $solicitacao;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.solicitacao_aprovada')
                ->subject('[Rede Orgânicos Osório] Sua Solicitação de Cadastro foi Aprovada')
                            ->with([
                                'solicitacao' => $this->solicitacao,
                            ]);
    }
}
