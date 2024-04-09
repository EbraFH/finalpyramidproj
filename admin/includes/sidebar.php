<div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                              <?php 
                                   if($_SESSION['CurrentUser']['userRole'] == "Admin"):?>
                            <a class="nav-link" href="view-register.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Registered Users
                            </a>
                             <?php  endif; ?>
                            <a class="nav-link" href="view-tournaments.php">
                                <div class="sb-nav-link-icon"><i class="fa fa-trophy" aria-hidden="true"></i></div>
                                Tournaments
                            </a>
                            <?php 
                                   if(!($_SESSION['CurrentUser']['userRole'] == "Player")):?>
                            <a class="nav-link" href="view-participants.php">
                                <div class="sb-nav-link-icon"><i class="fa fa-users" aria-hidden="true"></i></div>
                                participants
                            </a>
                             <a class="nav-link" href="view-ladder.php">
                                <div class="sb-nav-link-icon"><i class="fa fa-ranking-star" aria-hidden="true"></i></div>
                                Ladder
                            </a>
                            <?php  endif; ?>
                            <?php if(($_SESSION['CurrentUser']['userRole'] == "Player")):?>
                                <a class="nav-link" href="view-ladder.php">
                                <div class="sb-nav-link-icon"><i class="fa fa-ranking-star" aria-hidden="true"></i></div>
                                My-Tournaments
                            </a>
                             <?php  endif; ?>
                            <?php if(!($_SESSION['CurrentUser']['userRole'] == "Tournament Manager")):?>
                            <a class="nav-link" href="gameInvitations.php">
                                <div class="sb-nav-link-icon"><i class="fa fa-envelope"  aria-hidden="true"></i></div>
                                Game Invitations
                            </a>
                            <?php  endif; ?>
                             <a class="nav-link" href="view-games.php">
                                <div class="sb-nav-link-icon"><i class="fa fa-table-tennis" aria-hidden="true"></i></div>
                                Games
                            </a>
                            <a class="chartsPage nav-link" href="charts.php">
                                <div class="sb-nav-link-icon"><i class="fa fa-area-chart" aria-hidden="true"></i></div>
                                Charts
                            </a>
                        </div>
                    </div>
                    <?php 
                        //if(isset($_SESSION['CurrentUser'])) 
                    ?> 
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as: <?=$_SESSION['CurrentUser']['userName'];?></div>
                    </div>
                </nav>
            </div>
