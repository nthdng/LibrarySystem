<div class="navbar navbar-inverse set-radius-zero">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand">
                <img src="../assets/img/PWU.png" class="left-aligned-image" alt="PWU Logo" />
                <span class="brand-text">PWU Online Library System</span>
            </a>

        </div>

        <div class="right-div">
            <a href="../logout.php" class="btn btn-danger pull-right">LOG ME OUT</a>
        </div>
    </div>
</div>
<!-- LOGO HEADER END-->
<section class="menu-section">
    <div class="container">
        <div class="row ">
            <div class="col-md-12">
                <div class="navbar-collapse collapse ">
                    <a href="dashboard.php" class="menu-top-active"></a>
                    <ul id="menu-top" class="nav navbar-nav navbar-right">

                        <li><a href="dashboardlib.php" class="menu-top-active">HOME</a></li>

                        
                        <li>
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> Book Management
                                <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="bookManagementlib.php">Manage Books</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1"
                                        href="othersListlib.php">Manage Others</a></li>
                                
                            </ul>
                        </li>

                        <li>
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> Transactions <i
                                    class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="issue-book-lib.php">Issue
                                        Books</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1"
                                        href="manage-issued-books-lib.php">Borrowed Books</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1"
                                        href="manage-returned-books-lib.php">Returned Books</a></li>
                            </ul>
                        </li>
                        <!-- <li>
                            <a href="manage-request.php">
                                Request
                                <?php 
                                // if (!isset($count_requests)) {
                                //     $count_requests = 0; // Default value if not set
                                // if ($count_requests > 0): ?>
                                //     <span class="badge badge-danger"><?php echo $count_requests; ?></span>
                                <?php //endif;} ?>
                            </a>
                        </li> -->

                        <li>
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> User Management
                                <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">

                                <li><a href="../librarian/LibstudentList.php">Student List</a></li>
                                <li><a href="../librarian/LibfacultyList.php">Faculty List</a></li>


                            </ul>
                        </li>

                        <li><a href="../librarian/changePassLib.php">Change Password</a></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>