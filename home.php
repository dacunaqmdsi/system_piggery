<?php
if (session_id() == '') {
    session_start();
}
if (isset($_SESSION['accountid'])) {
    if (file_exists('includes/dbconfig.php')) {
        include_once('includes/dbconfig.php');
    }
} else {
    header('location: ./');
    exit(0);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dimayacyac's Piggery Farm Management System</title>
    <!-- Scripts -->
    <link rel="icon" type="image/png" href="docs/logo.png-removebg-preview.png" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        #app-container {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            background: linear-gradient(180deg, #e546ad 0%, #e546ad 100%);
            color: #e0e7ff;
            width: 250px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            padding: 1rem;
            overflow-y: auto;
        }

        .sidebar a {
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            color: #e0e7ff;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: #e546ad;
            color: white;
        }

        .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: 600;
            border-left-color: white;
        }

        .sidebar-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .user-info {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: auto;
        }

        .logout-btn {
            background-color: rgba(255, 255, 255, 0.1);
            transition: background-color 0.2s ease;
        }

        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        main {
            flex-grow: 1;
            overflow-y: auto;
            padding: 1rem;
            background-color: #f8f9fa;
        }

        .auth-container {
            background: linear-gradient(135deg, #fcf6fc 0%, #e9f0f7 100%);
        }

        .btn-primary {
            background: linear-gradient(135deg, #e546b5 0%, #e546b5 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #e546b5 0%, #e546b5 100%);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #e54698;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        .input-field,
        .select-field {
            border: 2px solid #e5e7eb;
        }

        .input-field:focus,
        .select-field:focus {
            border-color: #e546b5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #ec4899 !important;
            box-shadow: 0 0 0 0.2rem rgba(236, 72, 153, 0.25);
        }
    </style>

    <script>
        function param(w, h) {
            var width = w;
            var height = h;
            var left = (screen.width - width) / 2;
            var top = (screen.height - height) / 2;
            var params = 'width=' + width + ', height=' + height;
            params += ', top=' + top + ', left=' + left;
            params += ', directories=no, location=no, resizable=no, status=no, toolbar=no';
            return params;
        }

        function openWin(url) {
            window.open(url, 'mywin', param(800, 500)).focus();
        }

        function openCustom(url, w, h) {
            window.open(url, 'mywin', param(w, h)).focus();
        }

        function openCustom2(url, w, h) {
            let newWindow = window.open(url, '_blank', param(w, h));
            if (!newWindow) {
                alert("Popup blocked! Please allow popups for this site.");
            } else {
                newWindow.focus();
            }
        }

        function ajax_fn(url, elementId) {
            const xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    document.getElementById(elementId).innerHTML = xmlhttp.responseText;
                }
            };
            xmlhttp.open("GET", url, true);
            xmlhttp.send();
        }

        function add_user() {
            var username = document.getElementById('username').value;
            var account_password = document.getElementById('account_password').value;
            var account_type = document.getElementById('account_type').value;

            if (!username) {
                alert('Username is required');
                return;
            }
            if (!account_password) {
                alert('Account Password is required');
                return;
            }
            if (!account_type) {
                alert('Account type is required');
                return;
            }


            if (confirm("Are you sure you want to create this user?")) {
                var formData = new FormData();
                formData.append('username', username);
                formData.append('account_password', account_password);
                formData.append('account_type', account_type);
                formData.append('add_user', 1);
                $.ajax({
                    url: 'pages/user_management.php',
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $("#main_content").html(data);
                        $("#main_content").css('opacity', '1');
                        // document.getElementById("user_id").value = "";
                        $('.modal-backdrop').remove();
                    },
                    error: function() {
                        alert("Error occurred while creating the user.");
                    }
                });
            } else {
                alert("User creation canceled.");
            }
        }

        function edit_user() {
            var accountid = document.getElementById('accountid_edit').value;
            var username = document.getElementById('username_edit').value.trim();
            var account_password = document.getElementById('account_password_edit').value.trim();
            var account_type = document.getElementById('account_type_edit').value;
            var is_blocked_edit = document.getElementById('is_blocked_edit').value;

            if (!username) {
                alert('Username is required');
                return;
            }

            if (!account_type) {
                alert('Account type is required');
                return;
            }
            if (!is_blocked_edit) {
                alert('Account status is required');
                return;
            }

            if (confirm("Are you sure you want to update this user?")) {
                var formData = new FormData();
                formData.append('accountid', accountid);
                formData.append('username', username);
                formData.append('account_type', account_type);
                formData.append('is_blocked_edit', is_blocked_edit);
                formData.append('update_user', 1);

                if (account_password !== "") {
                    formData.append('account_password', account_password);
                }

                $.ajax({
                    url: 'pages/user_management.php',
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $("#main_content").html(data);
                        $("#main_content").css('opacity', '1');
                        $('.modal').modal('hide');
                        $('.modal-backdrop').remove();
                    },
                    error: function() {
                        alert("Error occurred while updating the user.");
                    }
                });
            } else {
                alert("User update canceled.");
            }
        }



        function select_user(accountid) {
            $.ajax({
                url: 'pages/user_management_select.php',
                type: "GET",
                data: {
                    accountid: accountid
                },
                dataType: 'json',
                success: function(data) {
                    $('#username_edit').val(data.username);
                    $('#is_blocked_edit').val(data.is_blocked);
                    $('#account_type_edit').val(data.account_type);
                    $('#accountid_edit').val(data.accountid);
                    $('#account_password_edit').val(data.account_password);
                },
                error: function() {
                    alert("Error");
                }
            });
        }




        function add_customer() {
            var name = document.getElementById('name').value.trim();
            var phone = document.getElementById('phone').value.trim();
            var email = document.getElementById('email').value.trim();
            var address = document.getElementById('address').value.trim();
            var notes = document.getElementById('notes').value.trim();

            // Basic validation
            if (!name) {
                alert('Full name is required');
                return;
            }

            if (!phone) {
                alert('Phone number is required');
                return;
            }

            if (!email) {
                alert('Email address is required');
                return;
            }

            if (!address) {
                alert('Address is required');
                return;
            }

            if (confirm("Are you sure you want to add this customer?")) {
                var formData = new FormData();
                formData.append('name', name);
                formData.append('phone', phone);
                formData.append('email', email);
                formData.append('address', address);
                formData.append('notes', notes);
                formData.append('add_customer', 1); // Flag for backend to identify this request

                $.ajax({
                    url: 'pages/customers.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $("#main_content").html(response); // use response instead of data
                        $("#main_content").css('opacity', '1');
                        $('.modal-backdrop').remove();

                    },
                    error: function() {
                        alert("Error occurred while adding the customer.");
                    }
                });
            }
        }

        function select_customer(customerid) {
            $.ajax({
                url: 'pages/customers_select.php',
                type: "GET",
                data: {
                    customerid: customerid
                },
                dataType: 'json',
                success: function(data) {
                    $('#customerid_edit').val(data.customerid);
                    $('#name_edit').val(data.name);
                    $('#phone_edit').val(data.phone);
                    $('#email_edit').val(data.email);
                    $('#address_edit').val(data.address);
                    $('#notes_edit').val(data.notes);
                },
                error: function() {
                    alert("Error");
                }
            });
        }

        function edit_customer() {
            var name_edit = document.getElementById('name_edit').value.trim();
            var phone_edit = document.getElementById('phone_edit').value.trim();
            var email_edit = document.getElementById('email_edit').value.trim();
            var address_edit = document.getElementById('address_edit').value.trim();
            var notes_edit = document.getElementById('notes_edit').value.trim();

            // Basic validation
            if (!name_edit) {
                alert('Full name is required');
                return;
            }

            if (!phone_edit) {
                alert('Phone number is required');
                return;
            }

            if (!email_edit) {
                alert('Email address is required');
                return;
            }

            if (!address_edit) {
                alert('Address is required');
                return;
            }

            if (confirm("Are you sure you want to update this customer?")) {
                var formData = new FormData();
                formData.append('customerid_edit', document.getElementById('customerid_edit').value);
                formData.append('name_edit', name_edit);
                formData.append('phone_edit', phone_edit);
                formData.append('email_edit', email_edit);
                formData.append('address_edit', address_edit);
                formData.append('notes_edit', notes_edit);
                formData.append('edit_customer', 1); // Flag for backend to identify this request

                $.ajax({
                    url: 'pages/customers.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $("#main_content").html(response); // use response instead of data
                        $("#main_content").css('opacity', '1');
                        $('.modal-backdrop').remove();

                    },
                    error: function() {
                        alert("Error occurred while adding the customer.");
                    }
                });
            }
        }



        function add_expense() {
            var expense_date = document.getElementById('expense_date').value.trim();
            var categoryid = document.getElementById('categoryid').value;
            var description = document.getElementById('description').value.trim();
            var amount = document.getElementById('amount').value.trim();
            var refnum = document.getElementById('refnum').value.trim();

            // Basic validation
            if (!expense_date) {
                alert('Date is required');
                return;
            }

            if (!categoryid) {
                alert('Category is required');
                return;
            }

            if (!description) {
                alert('Description is required');
                return;
            }

            if (!amount || isNaN(amount) || parseFloat(amount) <= 0) {
                alert('Valid amount is required');
                return;
            }

            if (confirm("Are you sure you want to add this expense?")) {
                var formData = new FormData();
                formData.append('expense_date', expense_date);
                formData.append('categoryid', categoryid);
                formData.append('description', description);
                formData.append('amount', amount);
                formData.append('refnum', refnum);
                formData.append('add_expense', 1); // Flag for backend

                $.ajax({
                    url: 'pages/expenses.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $("#main_content").html(response);
                        $("#main_content").css('opacity', '1');
                        $('#expenseModal').modal('hide');
                        $('.modal-backdrop').remove();
                    },
                    error: function() {
                        alert("Error occurred while adding the expense.");
                    }
                });
            }
        }


        function select_expense(expenseid) {
            $.ajax({
                url: 'pages/expenses_select.php',
                type: "GET",
                data: {
                    expenseid: expenseid
                },
                dataType: 'json',
                success: function(data) {
                    $('#expenseid_edit').val(data.expenseid);
                    $('#expense_date_edit').val(data.expense_date);
                    $('#categoryid_edit').val(data.categoryid);
                    $('#description_edit').val(data.description);
                    $('#amount_edit').val(data.amount);
                    $('#refnum_edit').val(data.refnum);
                },
                error: function() {
                    alert("Error");
                }
            });
        }


        function edit_expense() {
            var expenseid_edit = document.getElementById('expenseid_edit').value.trim();
            var expense_date_edit = document.getElementById('expense_date_edit').value.trim();
            var categoryid_edit = document.getElementById('categoryid_edit').value;
            var description_edit = document.getElementById('description_edit').value.trim();
            var amount_edit = document.getElementById('amount_edit').value.trim();
            var refnum_edit = document.getElementById('refnum_edit').value.trim();

            // Basic validation
            if (!expense_date_edit) {
                alert('Date is required');
                return;
            }

            if (!categoryid_edit) {
                alert('Category is required');
                return;
            }

            if (!description_edit) {
                alert('Description is required');
                return;
            }

            if (!amount_edit || isNaN(amount_edit) || parseFloat(amount_edit) <= 0) {
                alert('Valid amount is required');
                return;
            }

            if (confirm("Are you sure you want to edit this expense?")) {
                var formData = new FormData();
                formData.append('expenseid_edit', expenseid_edit);
                formData.append('expense_date_edit', expense_date_edit);
                formData.append('categoryid_edit', categoryid_edit);
                formData.append('description_edit', description_edit);
                formData.append('amount_edit', amount_edit);
                formData.append('refnum_edit', refnum_edit);
                formData.append('edit_expense', 1); // Flag for backend

                $.ajax({
                    url: 'pages/expenses.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $("#main_content").html(response);
                        $("#main_content").css('opacity', '1');
                        $('#expenseModal').modal('hide');
                        $('.modal-backdrop').remove();
                    },
                    error: function() {
                        alert("Error occurred while editing the expense.");
                    }
                });
            }
        }


        function add_product() {
            var product_name = document.getElementById('product_name').value.trim();
            var categoryid = document.getElementById('categoryid').value;
            var current_qty = document.getElementById('current_qty').value.trim();
            var unit = document.getElementById('unit').value.trim();
            var critical_level = document.getElementById('critical_level').value.trim();

            // Basic validation
            if (!product_name) {
                alert('Product name is required');
                return;
            }

            if (!categoryid) {
                alert('Category is required');
                return;
            }

            if (!current_qty || isNaN(current_qty) || parseFloat(current_qty) < 0) {
                alert('Valid current quantity is required');
                return;
            }

            if (!unit) {
                alert('Unit is required');
                return;
            }

            if (!critical_level || isNaN(critical_level) || parseFloat(critical_level) < 0) {
                alert('Valid critical level is required');
                return;
            }

            if (confirm("Are you sure you want to add this product?")) {
                var formData = new FormData();
                formData.append('product_name', product_name);
                formData.append('categoryid', categoryid);
                formData.append('current_qty', current_qty);
                formData.append('unit', unit);
                formData.append('critical_level', critical_level);
                formData.append('add_product', 1); // Backend flag

                $.ajax({
                    url: 'pages/products.php', // adjust if needed
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $("#main_content").html(response);
                        $("#main_content").css('opacity', '1');
                        $('#productModal').modal('hide');
                        $('.modal-backdrop').remove();
                    },
                    error: function() {
                        alert("Error occurred while adding the product.");
                    }
                });
            }
        }


        function select_product(product_id) {
            $.ajax({
                url: 'pages/products_select.php',
                type: "GET",
                data: {
                    product_id: product_id
                },
                dataType: 'json',
                success: function(data) {
                    $('#product_id_edit').val(data.product_id);
                    $('#product_name_edit').val(data.product_name);
                    $('#categoryid_edit').val(data.categoryid);
                    $('#current_qty_edit').val(data.current_qty);
                    $('#unit_edit').val(data.unit);
                    $('#critical_level_edit').val(data.critical_level);
                    $('#productEditModal').modal('show');
                },
                error: function() {
                    alert("Failed to fetch product details.");
                }
            });
        }

        function edit_product() {
            var product_id = $('#product_id_edit').val().trim();
            var product_name = $('#product_name_edit').val().trim();
            var categoryid = $('#categoryid_edit').val();
            var current_qty = $('#current_qty_edit').val().trim();
            var unit = $('#unit_edit').val().trim();
            var critical_level = $('#critical_level_edit').val().trim();

            // Basic validation
            if (!product_name || !categoryid || !current_qty || !unit || !critical_level) {
                alert("All fields are required.");
                return;
            }

            if (isNaN(current_qty) || isNaN(critical_level) || current_qty < 0 || critical_level < 0) {
                alert("Please enter valid numbers for quantity and critical level.");
                return;
            }

            if (confirm("Are you sure you want to update this product?")) {
                var formData = new FormData();
                formData.append('product_id_edit', product_id);
                formData.append('product_name_edit', product_name);
                formData.append('categoryid_edit', categoryid);
                formData.append('current_qty_edit', current_qty);
                formData.append('unit_edit', unit);
                formData.append('critical_level_edit', critical_level);
                formData.append('edit_product', 1); // Backend flag

                $.ajax({
                    url: 'pages/products.php', // Make sure this path matches your backend
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#main_content').html(response); // Update main content
                        $('#productEditModal').modal('hide');
                        $('.modal-backdrop').remove();
                    },
                    error: function() {
                        alert("An error occurred while updating the product.");
                    }
                });
            }
        }

        function monitor_it() {
            var monitor_date = document.getElementById('monitor_date').value;
            var pen_number = document.getElementById('pen_number').value;
            var symptom_id = document.getElementById('symptom_id').value;
            var description = document.getElementById('description').value;
            if (!monitor_date || !pen_number || !symptom_id) {
                alert('Please input monitor date, pen number and select symptom.');
                return;
            }
            if (confirm("Are you sure you want to add this log?")) {
                var formData = new FormData();
                formData.append('monitor_date', monitor_date);
                formData.append('pen_number', pen_number);
                formData.append('symptom_id', symptom_id);
                formData.append('description', description);
                formData.append('add_log', 1); // Backend flag

                $.ajax({
                    url: 'pages/monitoring.php', // Make sure this path matches your backend
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#main_content').html(response); // Update main content
                        // $('.modal-backdrop').remove();
                    },
                    error: function() {
                        alert("An error occurred while updating the product.");
                    }
                });
            }
        }


        function togglePigletOptions() {
            const penType = document.getElementById('pen_type').value;
            const pigletOptions = document.getElementById('piglet-options');

            if (penType === 'Piglet') {
                pigletOptions.style.display = 'block';
            } else {
                pigletOptions.style.display = 'none';
                document.getElementById('mothers_pen').selectedIndex = 0;
            }
        }

        function pen_inventory() {
            const pen_number = document.getElementById('pen_number').value.trim();
            const pen_type = document.getElementById('pen_type').value;
            const mothers_pen = document.getElementById('mothers_pen')?.value || '';
            const count_ = document.getElementById('count_').value;

            if (!pen_number) {
                alert("Please input pen number.");
                return;
            }

            if (!pen_type) {
                alert("Please select a pen type.");
                return;
            }

            // If Piglet is selected, mother_sow_pen must be selected too
            if (pen_type === 'Piglet' && !mothers_pen) {
                alert("Please select a mother sow pen for the piglet.");
                return;
            }

            if (!count_ || parseInt(count_) < 1) {
                alert("Please enter a valid animal count.");
                return;
            }

            if (confirm("Are you sure you want to add this inventory?")) {
                const formData = new FormData();
                formData.append('pen_number', pen_number);
                formData.append('pen_type', pen_type);
                formData.append('mothers_pen', mothers_pen);
                formData.append('count_', count_);
                formData.append('add_inventory', 1); // Backend flag

                $.ajax({
                    url: 'pages/inventory.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#main_content').html(response);
                        $('#addPenModal').modal('hide'); // Hide modal on success
                        $('.modal-backdrop').remove();
                    },
                    error: function() {
                        alert("An error occurred while updating the inventory.");
                    }
                });
            }
        }

        function add_birth() {
            const mothers_pen_id = document.getElementById('mothers_pen_id').value;
            const dob = document.getElementById('dob').value;
            const total_piglets = document.getElementById('total_piglets').value;
            const deaths = document.getElementById('deaths').value;
            const alive = document.getElementById('alive').value;

            if (!mothers_pen_id || !dob || !total_piglets) {
                alert("Please fill out all required fields.");
                return;
            }

            if (confirm("Are you sure you want to record this?")) {
                const formData = new FormData();
                formData.append('mothers_pen_id', mothers_pen_id);
                formData.append('dob', dob);
                formData.append('total_piglets', total_piglets);
                formData.append('deaths', deaths);
                formData.append('alive', alive);
                formData.append('add_birth', 1); // Backend flag

                $.ajax({
                    url: 'pages/inventory.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#main_content').html(response);
                        $('#addPenModal').modal('hide'); // Hide modal on success
                        $('.modal-backdrop').remove();
                    },
                    error: function() {
                        alert("An error occurred while updating the inventory.");
                    }
                });
            }
        }

        function calculateAlive() {
            const total = parseInt(document.getElementById('total_piglets').value) || 0;
            const deaths = parseInt(document.getElementById('deaths').value) || 0;
            const alive = Math.max(total - deaths, 0); // Avoid negative values
            document.getElementById('alive').value = alive;
        }

        function deleteBirth(id) {
            if (confirm("Are you sure you want to delete this birthing record?")) {
                const formData = new FormData();
                formData.append('delete_birth', 1); // flag for backend
                formData.append('id', id);

                $.ajax({
                    url: 'pages/inventory.php', // adjust if needed
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#main_content').html(response); // reload main content
                    },
                    error: function() {
                        alert("An error occurred while deleting the birthing record.");
                    }
                });
            }
        }

        function filterSalesHistory() {
            const input = document.getElementById('sales-search-input');
            const filter = input.value.toUpperCase();
            const tbody = document.getElementById('sales-history-body');
            const rows = tbody.getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const cols = rows[i].getElementsByTagName('td');
                let match = false;

                for (let j = 0; j < cols.length - 1; j++) { // exclude action column
                    const cell = cols[j];
                    if (cell) {
                        const text = cell.textContent || cell.innerText;
                        if (text.toUpperCase().indexOf(filter) > -1) {
                            match = true;
                            break;
                        }
                    }
                }

                rows[i].style.display = match ? '' : 'none';
            }
        }

        function process_sale() {
            const sale_date = document.getElementById('sale_date').value;
            const customerid = document.getElementById('customerid').value;

            if (!sale_date) {
                alert('Please select date.');
                return;
            }
            if (!customerid) {
                alert('Please select customer.');
                return;
            }

            const items = document.querySelectorAll('.sale-item-row');
            if (items.length === 0) {
                alert("Please add at least one sale item.");
                return;
            }

            const formData = new FormData();
            formData.append('sale_date', sale_date);
            formData.append('customerid', customerid);
            formData.append('add_sales', 1); // backend flag

            let hasError = false;

            items.forEach((row, index) => {
                const inventory_id = row.querySelector('.item-pen-select').value;
                const qty = row.querySelector('.item-quantity').value;
                const price = row.querySelector('.item-price').value;

                if (!inventory_id || qty <= 0 || price < 0) {
                    alert(`Invalid entry in row ${index + 1}.`);
                    hasError = true;
                    return;
                }

                formData.append('inventory_id[]', inventory_id);
                formData.append('qty[]', qty);
                formData.append('price[]', price);
            });

            if (hasError) return;

            if (confirm("Are you sure you want to process this?")) {
                $.ajax({
                    url: 'pages/sales.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#main_content').html(response);
                    },
                    error: function() {
                        alert("An error occurred while updating the inventory.");
                    }
                });
            }
        }

        function addSaleItem() {
            const container = document.getElementById('sale-items-container');
            const firstRow = container.querySelector('.sale-item-row');
            const clone = firstRow.cloneNode(true);

            clone.querySelectorAll('input').forEach(input => {
                input.value = input.type === 'number' ? (input.classList.contains('item-quantity') ? 1 : 0.00) : '';
            });
            clone.querySelector('select').selectedIndex = 0;

            container.appendChild(clone);
            updateGrandTotal();
        }

        function removeSaleItem(button) {
            const row = button.closest('.sale-item-row');
            const container = document.getElementById('sale-items-container');
            if (container.querySelectorAll('.sale-item-row').length > 1) {
                row.remove();
                updateGrandTotal();
            } else {
                alert("At least one item is required.");
            }
        }

        function updateGrandTotal() {
            let total = 0;
            document.querySelectorAll('.sale-item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('.item-quantity').value) || 0;
                const price = parseFloat(row.querySelector('.item-price').value) || 0;
                total += qty * price;
            });
            document.getElementById('grand-total').innerText = '₱' + total.toFixed(2);
        }

        // Auto-update grand total when quantity or price changes
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('item-quantity') || e.target.classList.contains('item-price')) {
                updateGrandTotal();
            }
        });

        function add_edit_category(categoryid = null) {
            var category = document.getElementById('category').value.trim();
            if (!category) {
                alert("Please input category");
                return;
            }

            if (confirm(categoryid ? "Update this category?" : "Create this category?")) {
                var formData = new FormData();
                formData.append('category', category);
                if (categoryid) {
                    formData.append('edit_category', 1);
                    formData.append('categoryid', categoryid);
                } else {
                    formData.append('add_category', 1);
                }

                $.ajax({
                    url: 'maintenance/category.php',
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $("#tmp_content").html(data);
                        $("#tmp_content").css('opacity', '1');
                        document.getElementById("category").value = "";
                    },
                    error: function() {
                        alert("Error occurred while saving the category.");
                    }
                });
            }
        }

        function add_edit_symptom(symptom_id = null) {
            const symptom = document.getElementById('symptom').value.trim();
            if (!symptom) {
                alert("Please input a symptom.");
                return;
            }

            const confirmMsg = symptom_id ? "Update this symptom?" : "Create this symptom?";
            if (confirm(confirmMsg)) {
                const formData = new FormData();
                formData.append('symptom', symptom);
                if (symptom_id) {
                    formData.append('edit_symptom', 1);
                    formData.append('symptom_id', symptom_id);
                } else {
                    formData.append('add_symptom', 1);
                }

                $.ajax({
                    url: 'maintenance/symptom.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#tmp_content').html(data).css('opacity', '1');
                        document.getElementById('symptom').value = '';
                    },
                    error: function() {
                        alert("An error occurred while saving the symptom.");
                    }
                });
            }
        }
    </script>
</head>

<body class="auth-container">
    <div id="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header pb-3 mb-3 text-center">
                <h1 class="h5 text-white fw-bold">Dimayacyac's Piggery Farm MS</h1>
            </div>
            <div class="text-center mb-3">
                <img src="docs/logo.png-removebg-preview.png" alt="Website Logo" class="img-fluid" style="width: 80px;" />
            </div>

            <nav>
                <?php if ($_SESSION['account_type'] == 'Farm Owner') { ?>
                    <a href="javascript:void(0);" onclick="location.reload();"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
                    <a href="javascript:void(0);" onclick="ajax_fn('pages/inventory','main_content')"><i class="fas fa-boxes me-2"></i> Pig Inventory</a>
                    <a href="javascript:void(0);" onclick="ajax_fn('pages/products','main_content')"><i class="fas fa-shopping-basket me-2"></i> Products</a>
                    <a href="javascript:void(0);" onclick="ajax_fn('pages/sales','main_content')"><i class="fas fa-dollar-sign me-2"></i> Sales</a>
                    <a href="javascript:void(0);" onclick="ajax_fn('pages/expenses','main_content')"><i class="fas fa-receipt me-2"></i> Expenses</a>
                    <a href="javascript:void(0);" onclick="ajax_fn('pages/monitoring','main_content')"><i class="fas fa-clipboard-list me-2"></i> Daily Monitoring</a>
                    <a href="javascript:void(0);" onclick="ajax_fn('pages/reports','main_content')"><i class="fas fa-chart-bar me-2"></i> Reports</a>
                    <a href="javascript:void(0);" onclick="ajax_fn('pages/notifications','main_content')"><i class="fas fa-bell me-2"></i> Notifications</a>
                    <a href="javascript:void(0);" onclick="ajax_fn('pages/user_management','main_content')"><i class="fas fa-users-cog me-2"></i> User Management</a>
                    <a href="javascript:void(0);" onclick="ajax_fn('pages/customers','main_content')"><i class="fas fa-address-book me-2"></i> Customers</a>
                    <a href="javascript:void(0);" onclick="ajax_fn('maintenance/maintenance','main_content')"><i class="fas fa-cogs me-2"></i> Maintenance</a>
                <?php } else { ?>
                    <a href="javascript:void(0);" onclick="location.reload();"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
                    <a href="javascript:void(0);" onclick="ajax_fn('pages/inventory','main_content')"><i class="fas fa-boxes me-2"></i> Pig Inventory</a>
                    <a href="javascript:void(0);" onclick="ajax_fn('pages/monitoring','main_content')"><i class="fas fa-clipboard-list me-2"></i> Daily Monitoring</a>
                    <a href="javascript:void(0);" onclick="ajax_fn('pages/reports','main_content')"><i class="fas fa-chart-bar me-2"></i> Reports</a>
                <?php } ?>
            </nav>

            <div class="user-info pt-3">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-user-circle fa-2x text-white me-2"></i>
                    <div>
                        <p id="user-name" class="mb-0 fw-semibold text-white"><?php echo $_SESSION['username']; ?></p>
                        <small id="user-role" class="text-light"><?php echo $_SESSION['account_type']; ?></small>
                    </div>
                </div>
                <button onclick="window.location.href='pages/logout.php'" class="logout-btn w-100 btn btn-sm text-start text-white">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main id="main_content">
            <?php include('pages/dashboard.php'); ?>
        </main>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>