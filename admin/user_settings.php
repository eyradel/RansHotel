<?php
include('db.php');
include('includes/access_control.php');
include('includes/unified_layout.php');

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: index.php');
    exit();
}

// Start admin page with unified layout
startUnifiedAdminPage('User Settings', 'Manage your user profile and settings');
?>

<!-- User Settings Content -->
<div class="user-settings-container">
    <div class="settings-grid">
        <!-- User Management Card -->
        <div class="settings-card">
            <div class="card-header">
                <h2 class="card-title">Administrator Accounts</h2>
                <p class="card-subtitle">Manage user accounts and permissions</p>
            </div>
                    <div class="table-responsive">
                        <table class="simple-table" id="userTable">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM `login`";
                                $re = mysqli_query($con, $sql);
                                
                                while($row = mysqli_fetch_array($re)) {
                                    $id = $row['id'];
                                    $us = $row['usname'];
                                    $ps = $row['pass'];
                                    
                                    echo "<tr>
                                        <td>" . htmlspecialchars($id) . "</td>
                                        <td>" . htmlspecialchars($us) . "</td>
                                        <td>" . htmlspecialchars($ps) . "</td>
                                        <td>
                                            <div class='action-buttons'>
                                                <button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#updateModal' data-id='" . $id . "' data-username='" . htmlspecialchars($us) . "' data-password='" . htmlspecialchars($ps) . "'>
                                                    <i class='fa fa-edit'></i> Update
                                                </button>
                                                <a href='user_settings_delete.php?eid=" . $id . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this user?\")'>
                                                    <i class='fa fa-trash'></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Add New User Form -->
        <div class="settings-card">
            <div class="card-header">
                <h2 class="card-title">Add New Administrator</h2>
                <p class="card-subtitle">Create a new admin account</p>
            </div>
                    <form method="post" class="form-horizontal">
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" name="newus" class="form-control" placeholder="Enter username" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" name="newps" class="form-control" placeholder="Enter password" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="adduser" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Add Administrator
                            </button>
                        </div>
                    </form>
        </div>
    </div>
</div>
<?php
// Handle form submissions
if(isset($_POST['adduser'])) {
    $newus = $_POST['newus'];
    $newps = $_POST['newps'];
    
    $newsql = "INSERT INTO login (usname, pass) VALUES ('$newus', '$newps')";
    if(mysqli_query($con, $newsql)) {
        echo '<div class="alert alert-success">User added successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Error adding user: ' . mysqli_error($con) . '</div>';
    }
}
?>

<style>
/* User Settings Dashboard-Style Styling */
.user-settings-container {
    max-width: 100%;
    margin: 0;
    padding: 0;
}

.settings-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--space-8);
    margin-bottom: var(--space-8);
}

.settings-card {
    background: var(--classic-white);
    border-radius: var(--radius-xl);
    padding: var(--space-6);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--classic-gray-light);
    transition: all var(--transition-normal);
}

.settings-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.card-header {
    background: var(--classic-navy);
    color: var(--classic-white);
    padding: var(--space-6);
    border-radius: var(--radius-xl) var(--radius-xl) 0 0;
    margin: calc(-1 * var(--space-6)) calc(-1 * var(--space-6)) var(--space-6) calc(-1 * var(--space-6));
    border-bottom: 3px solid var(--classic-gold);
}

.card-title {
    font-family: 'Playfair Display', serif;
    font-size: var(--font-size-xl);
    margin: 0;
    color: var(--classic-gold);
}

.card-subtitle {
    color: rgba(255, 255, 255, 0.8);
    font-size: var(--font-size-sm);
    margin: var(--space-2) 0 0 0;
}

.simple-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: var(--space-4);
}

.simple-table th {
    background: var(--classic-navy);
    color: var(--classic-white);
    font-weight: 600;
    text-transform: uppercase;
    font-size: var(--font-size-sm);
    letter-spacing: 0.5px;
    padding: var(--space-4);
    text-align: left;
}

.simple-table td {
    vertical-align: middle;
    padding: var(--space-4);
    border-bottom: 1px solid var(--classic-gray-light);
}

.simple-table tr:hover {
    background: var(--classic-gray-light);
}

.action-buttons {
    display: flex;
    gap: var(--space-2);
    align-items: center;
}

.action-buttons .btn {
    padding: var(--space-2) var(--space-4);
    font-size: var(--font-size-sm);
    border-radius: var(--radius-lg);
    transition: all var(--transition-fast);
    border: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
}

.action-buttons .btn-primary {
    background: var(--classic-navy);
    color: var(--classic-white);
}

.action-buttons .btn-primary:hover {
    background: var(--classic-gold);
    color: var(--classic-navy);
    transform: translateY(-1px);
}

.action-buttons .btn-danger {
    background: #dc3545;
    color: var(--classic-white);
}

.action-buttons .btn-danger:hover {
    background: #c82333;
    transform: translateY(-1px);
}

.form-group {
    margin-bottom: var(--space-6);
}

.form-label {
    display: block;
    margin-bottom: var(--space-2);
    font-weight: 600;
    color: var(--classic-navy);
    font-size: var(--font-size-sm);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-control {
    width: 100%;
    padding: var(--space-3) var(--space-4);
    border: 2px solid var(--classic-gray-light);
    border-radius: var(--radius-lg);
    font-size: var(--font-size-base);
    transition: all var(--transition-fast);
    background: var(--classic-white);
}

.form-control:focus {
    outline: none;
    border-color: var(--classic-gold);
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
}

.btn-primary {
    background: var(--classic-navy);
    color: var(--classic-white);
    border: none;
    padding: var(--space-3) var(--space-6);
    border-radius: var(--radius-lg);
    font-weight: 600;
    transition: all var(--transition-fast);
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
}

.btn-primary:hover {
    background: var(--classic-gold);
    color: var(--classic-navy);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.alert {
    padding: var(--space-4);
    border-radius: var(--radius-lg);
    margin-bottom: var(--space-4);
    font-weight: 500;
}

.alert-success {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.alert-danger {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.2);
}

/* Responsive Design */
@media (max-width: 768px) {
    .settings-grid {
        gap: var(--space-4);
    }
    
    .settings-card {
        padding: var(--space-4);
    }
    
    .card-header {
        padding: var(--space-4);
        margin: calc(-1 * var(--space-4)) calc(-1 * var(--space-4)) var(--space-4) calc(-1 * var(--space-4));
    }
    
    .action-buttons {
        flex-direction: column;
        gap: var(--space-2);
    }
    
    .action-buttons .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<?php
// End admin page with unified layout
endUnifiedAdminPage();
?>
