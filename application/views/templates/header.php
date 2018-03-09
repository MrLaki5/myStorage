<!DOCTYPE html>
<html lang="en" style="height: 100%;">
  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="File explorer">
    <meta name="author" content="MrLaki5">

    <title>myStorage</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url().'assets/css/bootstrap.min.css' ?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(). 'assets/css/4-col-portfolio.css?v=' . time(); ?>" rel="stylesheet">

  </head>

  <body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="#">myStorage</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <?php
              if($this->session->has_userdata('logedIn') || $this->session->has_userdata('logedZero')){
                if($this->session->userdata('currPage')==1){
                  echo '<li class="nav-item active">
                  <a class="nav-link" href="';
                  echo site_url('DataExplorer/resetFolder');
                  echo '">Data explorer
                        <span class="sr-only">(current)</span>
                        </a>
                        </li>';
                }
                else{
                  echo '<li class="nav-item">
                  <a class="nav-link" href="';
                  echo site_url('DataExplorer/resetFolder');
                  echo '">Data explorer
                        </a>
                        </li>';
                }


                if($this->session->userdata('currPage')==2){
                  echo '<li class="nav-item active">
                  <a class="nav-link" href="';
                  echo site_url('Login/settings');
                  echo '">Settings
                        <span class="sr-only">(current)</span>
                        </a>
                        </li>';
                }
                else{
                  echo '<li class="nav-item">
                  <a class="nav-link" href="';
                  echo site_url('Login/settings');
                  echo '">Settings
                        </a>
                        </li>';
                }


                if(!$this->session->has_userdata('logedZero')){
                  echo '<li class="nav-item">';
                  echo '<a class="nav-link" href="';
                  echo site_url('Login/logout');
                  echo '">Logout</a>';
                  echo '</li>';
                }
              }
            ?>
          </ul>
        </div>
      </div>
    </nav>