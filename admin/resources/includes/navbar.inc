<?php include_once "language.php"?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><?php echo $lang['NAVBAR'];?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#"><?php echo $lang['HOME'];?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="members.php"><?php echo $lang['MEMBER'];?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="products.php"><?php echo $lang['PRODUCTS'];?></a>
        </li>
         <li class="nav-item">
         <?php if($_SESSION['GROUP_ID'] == 1):?>

          <a class="nav-link" href="categories.php">Category</a>
          <?php else:?>
          <?php endif?>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo $_SESSION['FULL_NAME']?>
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="members.php?do=edit&userid=<?= $_SESSION['USER_ID'] ?>">Profile</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php"><?php echo $lang['LOGOUT'];?></a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>