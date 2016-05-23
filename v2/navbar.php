 <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#">Expense Tracker</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>
        <li><a href="#">This Month</a></li>
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">View All <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Expenses</a></li>
            <li><a href="#">Income</a></li>
            <li><a href="#">Creditors</a></li>
            <li><a href="#">Debtors</a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <?php 
        require_once("auto_login.php");
        if($logged_in == false){
          print '<li><a href="signup.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>';
          print '<li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>';
        }
        else if($logged_in == true){
          print '<li><a href="#"><span class="glyphicon glyphicon-user"></span> '.$_SESSION['username'].'</a></li>';
          print '<li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>';
        }
        ?>
      </ul>
    </div>
  </div>
</nav>