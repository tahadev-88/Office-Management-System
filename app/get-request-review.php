<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
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
            <form id="reviewForm" class="space-y-4">
                <input type="hidden" name="request_id" value="'.$request['id'].'">
                
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <h4 class="font-poppins font-semibold text-eerie-black mb-2">'.$request['title'].'</h4>
                    <div class="grid grid-cols-3 gap-4 mb-3">
                        <div>
                            <span class="text-body-sm font-poppins text-gray-600">Employee:</span>
                            <p class="font-poppins text-body font-medium">'.$request['employee_name'].'</p>
                        </div>
                        <div>
                            <span class="text-body-sm font-poppins text-gray-600">Category:</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-body-sm font-medium bg-gray-100 text-gray-800 capitalize ml-1">
                                '.$request['category'].'
                            </span>
                        </div>
                        <div>
                            <span class="text-body-sm font-poppins text-gray-600">Priority:</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-body-sm font-medium '.$priorityColors[$request['priority']].' capitalize ml-1">
                                '.$request['priority'].'
                            </span>
                        </div>
                    </div>
                    <div>
                        <span class="text-body-sm font-poppins text-gray-600">Message:</span>
                        <p class="font-poppins text-body text-gray-700 mt-1 whitespace-pre-wrap">'.$request['message'].'</p>
                    </div>
                </div>
                
                <div>
                    <label class="block text-body-sm font-poppins font-semibold text-gray-700 mb-2">Update Status</label>
                    <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300" required>
                        <option value="pending" '.($request['status'] == 'pending' ? 'selected' : '').'>Pending</option>
                        <option value="in_review" '.($request['status'] == 'in_review' ? 'selected' : '').'>In Review</option>
                        <option value="approved" '.($request['status'] == 'approved' ? 'selected' : '').'>Approved</option>
                        <option value="rejected" '.($request['status'] == 'rejected' ? 'selected' : '').'>Rejected</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-body-sm font-poppins font-semibold text-gray-700 mb-2">Admin Response</label>
                    <textarea name="admin_response" rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300 resize-vertical" 
                              placeholder="Provide your response to the employee...">'.$request['admin_response'].'</textarea>
                </div>
                
                <div class="flex gap-4 pt-4">
                    <button type="submit" class="bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300 shadow-digitazio">
                        <i class="fa fa-save mr-2"></i>Update Request
                    </button>
                    <button type="button" onclick="closeReviewModal()" class="bg-gray-500 hover:bg-gray-600 text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300">
                        <i class="fa fa-times mr-2"></i>Cancel
                    </button>
                </div>
            </form>
            
            <script>
                document.getElementById("reviewForm").addEventListener("submit", function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    
                    fetch("app/update-request.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            closeReviewModal();
                            location.reload();
                        } else {
                            alert("Error updating request: " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Error updating request");
                    });
                });
            </script>';
            
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
