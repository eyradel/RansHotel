<?php
session_start();
if(!isset($_SESSION["user"])) {
    header("location:index.php");
}

include('includes/access_control.php');
include('includes/unified_layout.php');
?>
<?php
// Start admin page with unified layout
startUnifiedAdminPage('Example with Unified Sidebar', 'Example page using the unified sidebar component');
?>

<div class="container">
    <h1>Example with Unified Sidebar</h1>
    <p>This page demonstrates the new unified sidebar component.</p>
    
    <div class="simple-card">
        <h2>Benefits of Unified Sidebar</h2>
        <ul>
            <li>✅ Consistent appearance across all pages</li>
            <li>✅ Single source of truth for navigation</li>
            <li>✅ Easy to maintain and update</li>
            <li>✅ Responsive design built-in</li>
            <li>✅ Professional appearance</li>
        </ul>
    </div>
    
    <div class="simple-card">
        <h2>How to Use</h2>
        <p>Simply include the unified layout helper:</p>
        <pre><code>&lt;?php
include('includes/unified_layout.php');
startUnifiedAdminPage('Page Title', 'Description');
?&gt;
&lt;!-- Your content here --&gt;
&lt;?php endUnifiedAdminPage(); ?&gt;</code></pre>
    </div>
</div>

<?php
// End admin page with unified layout
endUnifiedAdminPage();
?>
