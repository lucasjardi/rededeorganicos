<?php

namespace App\Mail;

use App\Solicitacao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitacaoCadastrada extends Mailable
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
       return $this->view('mail.solicitacao_cadastrada')
                ->subject('[Rede Orgânicos Osório] Nova Solicitação de Cadastro')
                            ->with([
                                'solicitacao' => $this->solicitacao,
                            ]);
    }
}
