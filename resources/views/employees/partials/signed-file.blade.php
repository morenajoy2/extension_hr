<!-- Signed File Upload Modal -->
    <div id="signedModal" class="fixed inset-0 hidden bg-black bg-opacity-50 justify-center items-center z-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-4xl">
            <h2 class="text-lg font-semibold mb-4">Upload Signed File</h2>
            <form id="signedForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="mb-4">
                    <label for="signed_file" class="block text-sm">Signed File <span class="text-red-500">*</span></label>

                    <div class="flex items-center space-x-4">
                        <label 
                            for="signed_file" 
                            class="cursor-pointer px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded hover:bg-black-700 transition"
                        >
                            Upload File
                        </label>
                        <span id="signedFileName" class="text-sm text-black-600">No file selected</span>
                    </div>

                    <input type="file" name="signed_file" id="signed_file" accept=".pdf,.jpg,.jpeg,.png" required class="hidden">
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('signedModal')" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Upload</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.getElementById('signed_file').addEventListener('change', function () {
        const fileNameDisplay = document.getElementById('signedFileName');
        fileNameDisplay.textContent = this.files.length > 0 ? this.files[0].name : 'No file selected';
    });
</script>