<?php  
session_start();  
if(!isset($_SESSION["user"]))
{
 header("location:index.php");
}

// Include access control system
include('includes/access_control.php');
include('includes/unified_layout.php');
initAccessControl('payment');

// Start admin page with components
startUnifiedAdminPage('Payment Management', 'Manage payment details and transactions for RansHotel');
?>
    <div class="p-4 sm:p-6 lg:p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                    <i class="fa fa-qrcode text-white text-lg"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Payment Details</h1>
            </div>
            <p class="text-gray-600">Manage payment details and transactions for RansHotel</p>
        </div>

        <!-- Payment Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">Payment Transactions</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="dataTables-example">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bed Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Out</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No of Rooms</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meal Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room Rent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bed Rent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meals</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        include ('db.php');
                        $sql="select * from payment";
                        $re = mysqli_query($con,$sql);
                        while($row = mysqli_fetch_array($re))
                        {
                            $id = $row['id'];
                            echo"<tr class='hover:bg-gray-50'>
                                <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>".$row['title']." ".$row['fname']." ".$row['lname']."</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>".$row['troom']."</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>".$row['tbed']."</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>".$row['cin']."</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>".$row['cout']."</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>".$row['nroom']."</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>".$row['meal']."</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>₵".number_format($row['ttot'], 2)."</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>₵".number_format($row['mepr'], 2)."</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>₵".number_format($row['btot'], 2)."</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>₵".number_format($row['fintot'], 2)."</td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm font-medium'>
                                    <a href='print.php?pid=".$id."' class='inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500'>
                                        <i class='fa fa-print mr-1'></i> Print
                                    </a>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php
// End admin page with components
endUnifiedAdminPage();
?>
