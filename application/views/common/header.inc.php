<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Example</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/css/bootstrap-theme.css">
</head>
<body>
    
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="/">Example</a>
            </div>
            <ul class="nav navbar-nav">
                <li<?php echo $this->uri->segment(1) !== 'admin' ? ' class="active"' : ''; ?>><a href="/">Site</a></li>
                <li<?php echo $this->uri->segment(1) === 'admin' ? ' class="active"' : ''; ?>><a href="/admin/posts">Admin</a></li>
            </ul>
        </div><!-- /.container-fluid -->
    </nav>
    
    <div class="container">
        