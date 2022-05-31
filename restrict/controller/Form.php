<?php
class Form
{
  private $message = "";
  private $error = " ";
  public function __construct()
  {
    Transaction::open();
  }
  public function controller()
  {
    $form = new Template("restrict/view/form.html");
    $form->set("id", "");
    $form->set("estilo","");
    $form->set("cor", "");
    $form->set("tamanho", "");
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
        if (empty($_POST["id"])) {
          $sapato->insert(
            "estilo, cor, tamanho",
            "$estilo, $cor, $tamanho"
          );
        } else {
          $id = $conexao->quote($_POST["id"]);
          $sapato->update(
            "estilo = $estilo, cor = $cor, tamanho = $tamanho",
            "id = $id"
          );
        }
        $this->message = $sapato->getMessage();
        $this->error = $sapato->getError();
      } catch (Exception $e) {
        $this->message = $e->getMessage();
        $this->error = true;
      }
    }else{
      $this->message = "Campos não informados!";
      $this->error = true;
    }
  }
  public function editar()
  {
    if (isset($_GET["id"])) {
      try {
        $conexao = Transaction::get();
        $id = $conexao->quote($_GET["id"]);
        $sapato = new Crud("sapato");
        $resultado = $sapato->select("*", "id = $id");
        if(!$sapato->getError()){
         $form = new Template("view/form.html");
         foreach ($resultado[0] as $cod => $valor) {
           $form->set($cod, $valor);
        }
        $this->message = $form->saida();
      } else {
        $this->message = $sapato->getMessage();
        $this->error = true;
      }
    } catch (Exception $e) {
      $this->message = $e->getMessage();
      $this->error = true;
    }
  }
}
public function getMessage()
{
  if (is_string($this->error)) {
    return $this->message;
  } else {
    $msg = new Template("shared/view/msg.html");
    if ($this->error) {
      $msg->set("cor", "danger");
    } else {
      $msg->set("cor", "success");
    }
    $msg->set("msg", $this->message);
    $msg->set("uri", "?class=Tabela");
    return $msg->saida();
  }
}
public function __destruct()
{
  Transaction::close();
}
}