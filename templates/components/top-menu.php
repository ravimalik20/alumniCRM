<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
	<div class="navbar-header">
	  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </button>
	  <a class="navbar-brand" href="/dashboard">Alumni CRM</a>
	</div>
	<div id="navbar" class="navbar-collapse collapse">
	  <ul class="nav navbar-nav navbar-right">
		<li><a href="<?=Helper::url('/dashboard')?>">Dashboard</a></li>
		<li><a href="<?=Helper::url('/auth/register')?>">Create Admin</a></li>
		<?php if (! Helper::is_login()): ?>
		<li><a href="<?=Helper::url('/auth/login')?>">Login</a></li>
		<?php else: ?>
		<li><a id="logout" href="<?=Helper::url('/api/v1/logout')?>">Logout</a></li>
		<?php endif; ?>
	  </ul>
	  
	</div>
  </div>
</nav>
