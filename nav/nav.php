<?php
if (session_id() == '') {
    session_start();
}
if (isset($_SESSION['accountid'])) {
    if (file_exists('../includes/dbconfig.php')) {
        include_once('../includes/dbconfig.php');
    }
} else {
    header('location: ../');
    exit(0);
}
?>
<?php
$current_page = basename($_SERVER['REQUEST_URI'], ".php");
?>
<div id="loading-overlay">
    <img src="../img/wait2.gif" alt="Loading..." />
</div>
<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="home">
            <span class="align-middle"><?php echo AccountType($_SESSION['accounttypeid']); ?></span>
        </a>


        <?php if ($_SESSION['accounttypeid'] == 2) { ?>
            <ul class="sidebar-nav">
                <li class="sidebar-header">
                    Navigation
                </li>

                <li class="sidebar-item <?php echo ($current_page == 'home') ? 'active' : ''; ?>">
                    <a class="sidebar-link nav-link" href="home" id="load_href">
                        <i class="align-middle" data-feather="home"></i> <!-- Home icon for Dashboard -->
                        <span class="align-middle">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item <?php echo ($current_page == 'entry') ? 'active' : ''; ?>">
                    <a class="sidebar-link nav-link" href="entry" id="load_href">
                        <i class="align-middle" data-feather="file-text"></i> <!-- File icon for Entry -->
                        <span class="align-middle">Entry</span>
                    </a>
                </li>

                <li class="sidebar-item <?php echo ($current_page == 'report') ? 'active' : ''; ?>">
                    <a class="sidebar-link nav-link" href="report" id="load_href">
                        <i class="align-middle" data-feather="bar-chart"></i> <!-- Bar chart icon for Generate Report -->
                        <span class="align-middle">Generate Report</span>
                    </a>
                </li>

            <?php } else { ?>


                <ul class="sidebar-nav">
                    <li class="sidebar-header">
                        Navigation
                    </li>

                    <li class="sidebar-item <?php echo ($current_page == 'home') ? 'active' : ''; ?>">
                        <a class="sidebar-link nav-link" href="home" id="load_href">
                            <i class="align-middle" data-feather="home"></i> <!-- Home icon for Dashboard -->
                            <span class="align-middle">Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?php echo ($current_page == 'entry') ? 'active' : ''; ?>">
                        <a class="sidebar-link nav-link" href="entry" id="load_href">
                            <i class="align-middle" data-feather="file-text"></i> <!-- File icon for Entry -->
                            <span class="align-middle">Entry</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?php echo ($current_page == 'archive') ? 'active' : ''; ?>">
                        <a class="sidebar-link nav-link" href="archive" id="load_href">
                            <i class="align-middle" data-feather="archive"></i> <!-- Changed to Archive icon -->
                            <span class="align-middle">Archived Records</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?php echo ($current_page == 'report') ? 'active' : ''; ?>">
                        <a class="sidebar-link nav-link" href="report" id="load_href">
                            <i class="align-middle" data-feather="bar-chart"></i> <!-- Bar chart icon for Generate Report -->
                            <span class="align-middle">Generate Report</span>
                        </a>
                    </li>

                    <li class="sidebar-header">
                        Settings
                    </li>

                    <li class="sidebar-item <?php echo ($current_page == 'rank') ? 'active' : ''; ?>">
                        <a class="sidebar-link nav-link" href="rank" id="load_href">
                            <i class="align-middle" data-feather="star"></i> <!-- Star icon for Setup Rank -->
                            <span class="align-middle">Rank Link</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?php echo ($current_page == 'region') ? 'active' : ''; ?>">
                        <a class="sidebar-link nav-link" href="region" id="load_href">
                            <i class="align-middle" data-feather="map-pin"></i> <!-- Map icon for Setup Region -->
                            <span class="align-middle">Region Link</span>
                        </a>
                    </li>

                </ul>



            <?php } ?>
    </div>
</nav>

<style>
    #loading-overlay {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
    }

    #loading-overlay img {
        width: 370px;
        height: 130px;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let links = document.querySelectorAll("#load_href");
        let loadingOverlay = document.getElementById("loading-overlay");

        links.forEach(link => {
            link.addEventListener("click", function(event) {
                loadingOverlay.style.display = "flex";
            });
        });

        // Hide loading overlay when page is shown (even from back/forward cache)
        window.addEventListener("pageshow", function(event) {
            loadingOverlay.style.display = "none";
        });
    });
</script>