<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class NewProductAdded extends Mailable
{
    use Queueable, SerializesModels;

    public string $product;
    public string $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($product)
    {
        $this->product = $product;
        $this->name = Auth::user()->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): NewProductAdded
    {
        $this->subject('Novo produto adicionado à sua lista de desejos');
        $this->to(Auth::user()->email, Auth::user()->name);
        return $this->view('products');
    }
}
