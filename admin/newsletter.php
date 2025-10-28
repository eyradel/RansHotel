<?php
session_start();
if(!isset($_SESSION["user"]))
{
    header("location:index.php");
}

include('db.php');
include('includes/access_control.php');
include('includes/unified_layout.php');
initAccessControl('newsletter');

// Handle approval/disapproval actions
if(isset($_GET['eid'])) {
$eid = $_GET['eid'];
    $approval = "Allowed";
    $napproval = "Not Allowed";

    $view = "select * from contact where id = '$eid' ";
    $re = mysqli_query($con, $view);
    while ($row = mysqli_fetch_array($re)) {
        $id = $row['approval'];
    }

    if($id == "Not Allowed") {
        $sql = "UPDATE `contact` SET `approval`= '$approval' WHERE id = '$eid' ";
        if(mysqli_query($con, $sql)) {
            echo '<script>alert("Subscriber Permission Updated Successfully!") </script>';
            header("Location: newsletter.php");
        }
    } else {
        $sql = "UPDATE `contact` SET `approval`= '$napproval' WHERE id = '$eid' ";
        if(mysqli_query($con, $sql)) {
            echo '<script>alert("Subscriber Permission Updated Successfully!") </script>';
            header("Location: newsletter.php");
        }
    }
}

// Start admin page with unified layout
startUnifiedAdminPage('Newsletter Management', 'Manage newsletter subscribers and communications');
?>

<!-- Newsletter Management Content -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- Newsletter Subscribers Card -->
            <div class="simple-card">
                <div class="card-header">
                    <h2 class="card-title">Newsletter Subscribers</h2>
                    <p class="card-subtitle">Manage newsletter subscriber permissions and communications</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="simple-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM contact ORDER BY id DESC";
                                $result = mysqli_query($con, $sql);
                                
                                if(mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)) {
                                        $statusClass = $row['approval'] == 'Allowed' ? 'text-success' : 'text-warning';
                                        $statusText = $row['approval'] == 'Allowed' ? 'Allowed' : 'Not Allowed';
                                        $actionText = $row['approval'] == 'Allowed' ? 'Disallow' : 'Allow';
                                        
                                        echo "<tr>
                                            <td>#{$row['id']}</td>
                                            <td>{$row['name']}</td>
                                            <td>{$row['email']}</td>
                                            <td>{$row['phone']}</td>
                                            <td><span class='{$statusClass}'>{$statusText}</span></td>
                                            <td>
                                                <a href='newsletter.php?eid={$row['id']}' class='btn btn-primary btn-sm'>
                                                    {$actionText}
                                                </a>
                                                <a href='newsletter_delete.php?eid={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this subscriber?\")'>
                                                    Delete
                                                </a>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No subscribers found.</td></tr>";
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

<style>
/* Newsletter Management Dashboard-Style Styling */
.simple-card {
    background: var(--classic-white);
    border-radius: var(--radius-xl);
    padding: var(--space-6);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--classic-gray-light);
    transition: all var(--transition-normal);
    margin-bottom: var(--space-6);
}

.simple-card:hover {
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

.btn {
    padding: var(--space-2) var(--space-4);
    font-size: var(--font-size-sm);
    border-radius: var(--radius-lg);
    transition: all var(--transition-fast);
    border: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
    text-decoration: none;
    margin-right: var(--space-2);
}

.btn-primary {
    background: var(--classic-navy);
    color: var(--classic-white);
}

.btn-primary:hover {
    background: var(--classic-gold);
    color: var(--classic-navy);
    transform: translateY(-1px);
}

.btn-danger {
    background: #dc3545;
    color: var(--classic-white);
}

.btn-danger:hover {
    background: #c82333;
    transform: translateY(-1px);
}

.text-success {
    color: #10b981;
    font-weight: 600;
}

.text-warning {
    color: #f59e0b;
    font-weight: 600;
}

.text-center {
    text-align: center;
}
</style>

<?php
// End admin page with unified layout
endUnifiedAdminPage();
?>