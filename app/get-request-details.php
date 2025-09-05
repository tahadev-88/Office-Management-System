<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "../DB_connection.php";
    include "Model/Request.php";
    
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $request = get_request_by_id($conn, $id);
        
        if ($request != 0) {
            // Status badge colors
            $statusColors = [
                'pending' => 'bg-yellow-100 text-yellow-800',
                'approved' => 'bg-green-100 text-green-800',
                'rejected' => 'bg-red-100 text-red-800',
                'in_review' => 'bg-blue-100 text-blue-800'
            ];
            
            // Priority badge colors
            $priorityColors = [
                'low' => 'bg-gray-100 text-gray-800',
                'medium' => 'bg-blue-100 text-blue-800',
                'high' => 'bg-orange-100 text-orange-800',
                'urgent' => 'bg-red-100 text-red-800'
            ];
            
            $html = '
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-body-sm font-poppins font-semibold text-gray-700">Category</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-body-sm font-medium bg-gray-100 text-gray-800 capitalize mt-1">
                            '.$request['category'].'
                        </span>
                    </div>
                    <div>
                        <label class="block text-body-sm font-poppins font-semibold text-gray-700">Priority</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-body-sm font-medium '.$priorityColors[$request['priority']].' capitalize mt-1">
                            '.$request['priority'].'
                        </span>
                    </div>
                </div>
                
                <div>
                    <label class="block text-body-sm font-poppins font-semibold text-gray-700">Status</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-body-sm font-medium '.$statusColors[$request['status']].' capitalize mt-1">
                        '.str_replace('_', ' ', $request['status']).'
                    </span>
                </div>
                
                <div>
                    <label class="block text-body-sm font-poppins font-semibold text-gray-700">Title</label>
                    <p class="text-body font-poppins text-eerie-black mt-1">'.$request['title'].'</p>
                </div>
                
                <div>
                    <label class="block text-body-sm font-poppins font-semibold text-gray-700">Message</label>
                    <p class="text-body font-poppins text-gray-600 mt-1 whitespace-pre-wrap">'.$request['message'].'</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-body-sm font-poppins font-semibold text-gray-700">Submitted</label>
                        <p class="text-body font-poppins text-gray-600 mt-1">'.date('M d, Y \a\t g:i A', strtotime($request['created_at'])).'</p>
                    </div>
                    '.($request['reviewed_at'] ? '
                    <div>
                        <label class="block text-body-sm font-poppins font-semibold text-gray-700">Reviewed</label>
                        <p class="text-body font-poppins text-gray-600 mt-1">'.date('M d, Y \a\t g:i A', strtotime($request['reviewed_at'])).'</p>
                    </div>' : '').'
                </div>
                
                '.($request['admin_response'] ? '
                <div>
                    <label class="block text-body-sm font-poppins font-semibold text-gray-700">Admin Response</label>
                    <div class="bg-gray-50 rounded-lg p-3 mt-1">
                        <p class="text-body font-poppins text-gray-600 whitespace-pre-wrap">'.$request['admin_response'].'</p>
                        '.($request['admin_name'] ? '<p class="text-body-sm font-poppins text-gray-500 mt-2">- '.$request['admin_name'].'</p>' : '').'
                    </div>
                </div>' : '').'
            </div>';
            
            echo json_encode(['success' => true, 'html' => $html]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Request not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
}
?>
