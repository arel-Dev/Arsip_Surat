<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <title>E-Arsip Surat</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class = "container">
      <a class="navbar-brand" href="?">E-Arsip</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
     </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="?">Beranda <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="?halaman=departemen">Data Departemen</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="?halaman=pengirim_surat">Data Pengirim Surat</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="?halaman=arsip_surat">Data Arsip Surat</a>
      </li>

    </ul>
  
    <form class="form-inline my-2 my-lg-0" action="?" method="GET">
      <input class="form-control mr-sm-2" type="search" placeholder="Cari Arsip..." aria-label="Search">
      <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Cari</button>
    </form>
    <a href="logout.php" class="btn btn-danger ml-2">Logout</a>
  </div>
    </div>
</nav>

<div class="container mt-3">