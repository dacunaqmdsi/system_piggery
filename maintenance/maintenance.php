<?php include('../includes/init.php'); ?>
<div class="main-container">
    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th class="text-center" colspan="2">
                        <button onclick="ajax_fn('maintenance/category', 'tmp_content');" class="btn btn-outline-secondary">Setup Category</button>
                        <button onclick="ajax_fn('maintenance/symptom', 'tmp_content');" class="btn btn-outline-secondary">Setup Symptoms</button>
                    </th>
                </tr>
            </thead>
        </table>
    </div>

    <div id="tmp_content">
        <?php include('category.php'); ?>
    </div>
</div>