<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rem\BadgeBundle;

/**
 * Description of count
 *
 * @author Beaud
 */
class count {
    
   /* private $_count  ;
    
    public function __construct($count){
        return $this->_count = $count;
    }
    
    public function getCount(){
        return $this->_count;
    }
    
    public function setCount($count){
        $this->_count = $count;
        return $this;
    }*/
    
  protected static $instance; // Contiendra l'instance de notre classe.

  

  protected function __construct() { } // Constructeur en privé.

  protected function __clone() { } // Méthode de clonage en privé aussi.

  

  public static function getInstance()

  {

    if (!isset(self::$instance)) // Si on n'a pas encore instancié notre classe.

    {

      self::$instance = new self; // On s'instancie nous-mêmes. :)

    }  

    return self::$instance;

  }
  
  private $_count  ;
    
    
    
    public function getCount(){
        return $this->_count;
    }
    
    public function setCount($count){
        $this->_count = $count;
        return $this;
    }
}
