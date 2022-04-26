<?php
class Form
{
  private $message = "";
  public function __construct()
  {
    Transaction::open();
  }
  public function controller()
  {
    $form = new Template("view/form.html");
    $this->message = $form->saida();
  }
  public function salvar()
  {
    if (isset($_POST["estilo"]) && isset($_POST["cor"]) && isset($_POST["tamanho"])) {
      try {
        $conexao = Transaction::get();
        $sapato = new Crud("sapato");
        $estilo = $conexao->quote($_POST["estilo"]);
        $cor = $conexao->quote($_POST["cor"]);
        $tamanho = $conexao->quote($_POST["tamanho"]);
        $resultado = $sapato->insert("estilo, cor, tamanho", "$estilo, $cor, $tamanho");
      } catch (Exception $e) {
        echo $e->getMessage();
      }
    }
  }
  public function getMessage()
  {
    return $this->message;
  }
  public function __destruct()
  {
    Transaction::close();
  }
}