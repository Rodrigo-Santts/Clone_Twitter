<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

   public function timeline(){
      $this->validaAutenticacao();

      $tweet = Container::getModel('Tweet');
      $tweet->__set('id_usuario', $_SESSION['id']);
      $this->view->tweets = $tweet->getAll();

      $usuario = Container::getModel('Usuario');
      $usuario->__set('id', $_SESSION['id']);
   
      $this->view->info_usuario = $usuario->getInfoUsuario();
      $this->view->total_tweets = $usuario->getTotalTweets();
      $this->view->total_seguindo = $usuario->getTotalSeguindo();
      $this->view->total_seguidores = $usuario->getTotalSeguidores();
      // print_r($this->view->total_tweets);
      $this->render('timeline');

   }

   public function tweet(){
      $this->validaAutenticacao();

      $tweet = Container::getModel('Tweet');
      $tweet->__set('tweet', $_POST['tweet']);
      $tweet->__set('id_usuario', $_SESSION['id']);
      $tweet->salvar();
   }

   public function validaAutenticacao(){
      session_start();
      if (!isset($_SESSION['id']) || $_SESSION['id'] || !isset($_SESSION['nome']) || $_SESSION['nome'] == '') {

         return true;
      }else {
         header('Location: /?authentication=erro');

      }
   }

   public function quemSeguir(){
      $this->validaAutenticacao();
      $pesquisarPor = isset($_GET['pesquisarPor']) ?  $_GET['pesquisarPor']: '';
      $usuarios = array();
      $container = Container::getModel('Usuario');
      
      if ($pesquisarPor != ''){
         $container->__set('nome', $pesquisarPor);
         $container->__set('id', $_SESSION['id']);
         $usuarios = $container->getAll();

      }
      $this->view->usuarios = $usuarios;
      $container->__set('id', $_SESSION['id']);
   
      $this->view->info_usuario = $container->getInfoUsuario();
      $this->view->total_tweets = $container->getTotalTweets();
      $this->view->total_seguindo = $container->getTotalSeguindo();
      $this->view->total_seguidores = $container->getTotalSeguidores();
      $this->render('quemSeguir');
   }

   public function acao(){
      $this->validaAutenticacao();
      $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
      $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';
      $usuario = Container::getModel('Usuario');
      $usuario->__set('id', $_SESSION['id']);

      if ($acao == 'seguir'){
         $usuario->seguirUsuario($id_usuario_seguindo);  

      }else if ($acao == 'deixar_de_seguir'){
         $usuario->deixarSeguirUsuario($id_usuario_seguindo);
      }
      header('Location: quem_seguir ');
   } 

   public function removerTweet(){
      $this->validaAutenticacao();
      $remover = Container::getModel('Usuario');
      $remover->__set('id', $_GET['remover']);
      $remover->removertt();
      header('Location: timeline');
   }
}

?>