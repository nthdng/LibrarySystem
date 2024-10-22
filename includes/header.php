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

                        <li><a href="dashboard.php" class="menu-top-active">HOME</a></li>

                        
                        <li>
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> Book Management
                                <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="bookManagement.php">Manage Books</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1"
                                        href="othersList.php">Manage Others</a></li>
                                
                            </ul>
                        </li>

                        <li>
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> Transactions <i
                                    class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="issue-book.php">Issue
                                        Books</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1"
                                        href="manage-issued-books.php">Borrowed Books</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1"
                                        href="manage-returned-books.php">Returned Books</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown"> User Management
                                <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">

                                <li><a href="../admin/studentList.php">Student List</a></li>
                                <li><a href="../admin/facultyList.php">Faculty List</a></li>
                                <li><a href="../admin/userList.php">User List</a></li>

                            </ul>
                        </li>
                        <li><a href="changePass.php">Change Password</a></li>
                </div>
            </div>

        </div>
    </div>
</section>