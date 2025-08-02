<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 hidden bg-black bg-opacity-50 justify-center items-center z-50">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-md text-center">
        <h2 class="text-lg font-semibold mb-4">Confirm Delete?</h2>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-center space-x-2">
                <button type="button" onclick="closeModal('deleteModal')" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
            </div>
        </form>
    </div>
</div>