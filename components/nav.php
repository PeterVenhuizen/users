<span class="logo"><a href="/users/">USERS/</a></span>
<ul id="menu">
    <?php 
        if (isset($_SESSION['username'])) {
    ?>
            <li class="item">
                <a href="profile/">
                    <i class="fas fa-user"></i> <?php echo $_SESSION['username']; ?>
                </a>
            </li>
            <li class="item button"><a href="logout/">Logout <i class="fas fa-sign-out-alt"></i></a></li>
    <?php
        } else {
    ?>
            <li class="item"><a href="register/">Register</a></li>
            <li class="item button"><a href="/users/"><i class="fas fa-sign-in-alt"></i> Login</a></li>
    <?php
        }
    ?>
    
</ul>